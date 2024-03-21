<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

class Dashboard extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        /* Get available custom domains */
        $domains = (new \Altum\Models\Domain())->get_available_domains_by_user($this->user, false);

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['is_enabled'], ['name'], ['last_datetime', 'datetime', 'pageviews', 'name', 'orders']));
        $filters->set_default_order_by('store_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = \Altum\Cache::cache_function_result('stores_total?user_id=' . $this->user->user_id, null, function() {
            return db()->where('user_id', $this->user->user_id)->getValue('stores', 'count(*)');
        });
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('dashboard?' . $filters->get_get() . '&page=%d')));

        /* Get the stores */
        $stores = [];
        $stores_result = database()->query("
            SELECT
                *
            FROM
                `stores`
            WHERE
                `user_id` = {$this->user->user_id}
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}

            {$paginator->get_sql_limit()}
        ");

        /* Get the menus */
        $menus = [];

        while($row = $stores_result->fetch_object()) {

            /* Generate the store full URL base */
            $row->full_url = (new \Altum\Models\Store())->get_store_full_url($row, $this->user, $domains);

            $stores[] = $row;

            $menus_result = database()->query("
                SELECT
                    user_id,store_id,sum(pageviews) AS pageviews
                FROM
                    `menus`
                WHERE
                    `store_id` = {$row->store_id}
                    AND `user_id` = {$this->user->user_id}
                ORDER BY `order`
                
            ");
            while($row = $menus_result->fetch_object()) {
                $menus[] = $row;
            }

              
        }

        $totalGuestBookPageView = 0;

        foreach ($menus as $key => $value) {
            // print_r($value);
            $totalGuestBookPageView += $value->pageviews;
            $d = database()->query("
                UPDATE
                    `stores`
                SET  pageviews = {$value->pageviews}
                WHERE
                    `store_id` = {$value->store_id}
                    AND `user_id` = {$value->user_id}");
            
        }
        // echo '<pre>Data ';
        // print_r($d);
        // exit; 

        /* Get some extra data for the widgets */
        $stores_statistics = \Altum\Cache::cache_function_result('stores_statistics?user_id=' . $this->user->user_id, null, function() {
            return database()->query("SELECT COUNT(*) AS `stores`, SUM(`pageviews`) AS `pageviews`, SUM(`orders`) AS `orders` FROM `stores` WHERE `user_id` = {$this->user->user_id}")->fetch_object();
        }, 60 * 60 * 12);

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Prepare the View */
        $data = [
            'stores' => $stores,
            'total_stores' => $total_rows,
            'pagination' => $pagination,
            'filters' => $filters,
            'stores_statistics' => $stores_statistics,
        ];

        $data['stores_statistics']->pageviews = $totalGuestBookPageView;

        // print_r($totalGuestBookPageView);
        // echo '<pre>';
        // print_r($data['stores_statistics']->pageviews);
        // exit;


        $view = new \Altum\View('dashboard/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
