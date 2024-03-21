<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Alerts;

class StoreCreate extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('create.stores')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('dashboard');
        }

        /* Check for the plan limit */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `stores` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total ?? 0;

        if($this->user->plan_settings->stores_limit != -1 && $total_rows >= $this->user->plan_settings->stores_limit) {
            Alerts::add_info(l('global.info_message.plan_feature_limit'));
            redirect('dashboard');
        }

        /* Get available custom domains */
        $domains = (new \Altum\Models\Domain())->get_available_domains_by_user($this->user);

        if(!empty($_POST)) {
            $_POST['url'] = !empty($_POST['url']) && $this->user->plan_settings->custom_url_is_enabled ? get_slug(query_clean($_POST['url'])) : false;
            $_POST['name'] = trim(query_clean($_POST['name']));
            $_POST['description'] = trim(query_clean($_POST['description']));
            $_POST['address'] = trim(query_clean($_POST['address']));
            $_POST['currency'] = ($_POST['currency']) ? trim(query_clean($_POST['currency'])) : '';
            $_POST['timezone'] = in_array($_POST['timezone'], \DateTimeZone::listIdentifiers()) ? query_clean($_POST['timezone']) : settings()->main->default_timezone;

            $_POST['domain_id'] = isset($_POST['domain_id']) && isset($domains[$_POST['domain_id']]) ? (!empty($_POST['domain_id']) ? (int) $_POST['domain_id'] : null) : null;
            $_POST['is_main_store'] = isset($_POST['is_main_store']) && isset($domains[$_POST['domain_id']]) && $domains[$_POST['domain_id']]->type == 0;

            //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

            /* Check for any errors */
            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            /* Check for duplicate url if needed */
            if($_POST['url']) {

                $domain_id_where = $_POST['domain_id'] ? "AND `domain_id` = {$_POST['domain_id']}" : "AND `domain_id` IS NULL";
                $is_existing_store = database()->query("SELECT `store_id` FROM `stores` WHERE `url` = '{$_POST['url']}' {$domain_id_where}")->num_rows;

                if($is_existing_store) {
                    Alerts::add_error(l('store.error_message.url_exists'));
                }

                /* Make sure the custom url meets the requirements */
                if(mb_strlen($_POST['url']) < ($this->user->plan_settings->url_minimum_characters ?? 1)) {
                    Alerts::add_error(sprintf(l('store.error_message.url_minimum_characters'), ($this->user->plan_settings->url_minimum_characters ?? 1)));
                }

                if(mb_strlen($_POST['url']) > ($this->user->plan_settings->url_maximum_characters ?? 64)) {
                    Alerts::add_error(sprintf(l('store.error_message.url_maximum_characters'), ($this->user->plan_settings->url_maximum_characters ?? 64)));
                }
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $details = json_encode([
                    'address' => $_POST['address'],
                    'phone' => '',
                    'website' => '',
                    'email' => '',
                    'hours' => [
                        '1' => [
                            'is_enabled' => 1,
                            'hours' => '',
                        ],
                        '2' => [
                            'is_enabled' => 1,
                            'hours' => '',
                        ],
                        '3' => [
                            'is_enabled' => 1,
                            'hours' => '',
                        ],
                        '4' => [
                            'is_enabled' => 1,
                            'hours' => '',
                        ],
                        '5' => [
                            'is_enabled' => 1,
                            'hours' => '',
                        ],
                        '6' => [
                            'is_enabled' => 1,
                            'hours' => '',
                        ],
                        '7' => [
                            'is_enabled' => 1,
                            'hours' => '',
                        ]
                    ]
                ]);
                $settings = json_encode([
                    'title' => null,
                    'meta_description' => null,
                    'meta_keywords' => null,
                    'font_family' => 'default',
                    'font_size' => 16,
                    'display_share_buttons' => true,
                ]);
                $theme = 'new-york';

                if(!$_POST['url']) {
                    $is_existing_store = true;

                    /* Generate random url if not specified */
                    while($is_existing_store) {
                        $_POST['url'] = mb_strtolower(string_generate(settings()->stores->random_url_length ?? 7));

                        $domain_id_where = $_POST['domain_id'] ? "AND `domain_id` = {$_POST['domain_id']}" : "AND `domain_id` IS NULL";
                        $is_existing_store = database()->query("SELECT `store_id` FROM `stores` WHERE `url` = '{$_POST['url']}' {$domain_id_where}")->num_rows;
                    }

                }

                /* Prepare the statement and execute query */
                $store_id = db()->insert('stores', [
                    'user_id' => $this->user->user_id,
                    'domain_id' => $_POST['domain_id'],
                    'url' => $_POST['url'],
                    'name' => $_POST['name'],
                    'description' => $_POST['description'],
                    'settings' => $settings,
                    'details' => $details,
                    'currency' => $_POST['currency'],
                    'theme' => $theme,
                    'timezone' => $_POST['timezone'],
                    'email_reports_last_datetime' => \Altum\Date::$date,
                    'datetime' => \Altum\Date::$date,
                ]);

                /* Update custom domain if needed */
                if($_POST['is_main_store']) {
                    db()->where('domain_id', $_POST['domain_id'])->update('domains', [
                        'store_id' => $store_id,
                        'last_datetime' => \Altum\Date::$date,
                    ]);

                    /* Clear the cache */
                    \Altum\Cache::$adapter->deleteItemsByTag('domain_id=' . $_POST['domain_id']);
                }

                /* Clear the cache */
                \Altum\Cache::$adapter->deleteItem('stores_total?user_id=' . $this->user->user_id);

                //insert data in menu:start
                $stmt = database()->prepare("INSERT INTO `menus`(`store_id`, `user_id`, `menu_ref_id`, `url`, `name`,  `datetime`) VALUES (?, ?, ?, ?, ?, ?)");

                /* Define data array */
                $data = array(
                    array(1,"welcome", "Welcome message"),
                    array(2,"directions", "Directions"),
                    array(3,"bring-along", "Bring along"),
                    array(4,"check-in-out", "Check In/Out"),
                    array(5,"rules", "Rules"),
                    array(6,"amenities", "Amenities"),
                    array(7,"wifi", "WiFi Access"),
                    array(8,"emergency", "Emergency"),
                    array(9,"health-services", "Health Services"),
                    array(10,"food-beverage", "Food & Beverage"),
                    array(11,"tours-activities", "Tours & Activities"),
                    array(12,"car-rentals", "Car rentals"),
                    array(13,"shopping", "Shopping Centers"),
                    array(14,"feedback", "Feedback"),
                    array(15,"taxi-shuttle", "Taxi & Shuttle")
                );

                /* Bind parameters */
                $stmt->bind_param('ssssss', $store_id, $this->user->user_id, $menu_ref_id,$url, $name, \Altum\Date::$date);

                /* Insert data from array */
                foreach ($data as $row) {
                    list($menu_ref_id, $url, $name) = $row;
                    $stmt->execute();
                }

                /* Close statement */
                $stmt->close();

                //insert data in menu:end

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . $_POST['name'] . '</strong>'));

                redirect('store/' . $store_id);
            }

        }

        /* Set default values */
        $values = [
            'url' => $_POST['url'] ?? '',
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'address' => $_POST['address'] ?? '',
            'currency' => $_POST['currency'] ?? '',
            'timezone' => $_POST['timezone'] ?? '',
            'domain_id' => $_POST['domain_id'] ?? '',
            'is_main_store' => $_POST['is_main_store'] ?? '',
        ];

        /* Prepare the View */
        $data = [
            'domains' => $domains,
            'values' => $values
        ];

        $view = new \Altum\View('store-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
