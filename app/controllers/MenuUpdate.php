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
use Altum\Title;

class MenuUpdate extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('update.menu')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('dashboard');
        }

        $menu_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$menu = db()->where('menu_id', $menu_id)->where('user_id', $this->user->user_id)->getOne('menus')) {
            redirect('dashboard');
        }

        $store = db()->where('store_id', $menu->store_id)->where('user_id', $this->user->user_id)->getOne('stores');
        
        $store_health_services = array();
        $store_health_services = db()->where('store_id', $menu->store_id)->get('store_health_services');
        
        /* Generate the store full URL base */
        $store->full_url = (new \Altum\Models\Store())->get_store_full_url($store, $this->user);

        if(!empty($_POST)) {
            $_POST['url'] = !empty($_POST['url']) ? get_slug(query_clean($_POST['url'])) : false;
            $_POST['name'] = trim(query_clean($_POST['name']));
            $_POST['description'] = trim(query_clean($_POST['description']));
            $_POST['is_enabled'] = (int) isset($_POST['is_enabled']);
            $_POST['welcome_msg'] = isset($_POST['welcome_msg']) ? $_POST['welcome_msg'] : '';
            $_POST['directions'] =  isset($_POST['directions']) ? $_POST['directions'] : '';
            $_POST['bring_along'] =  isset($_POST['bring_along']) ? $_POST['bring_along'] : '';
            $_POST['rules'] =  isset($_POST['rules']) ? $_POST['rules'] : '';
            $_POST['network'] =  isset($_POST['network']) ? $_POST['network'] : '';
            $_POST['network_password'] =  isset($_POST['network_password']) ? $_POST['network_password'] : '';
            //$_POST['add_notes'] =  !empty(strip_tags(trim($_POST['add_notes']))) ? strip_tags(trim($_POST['add_notes'])) : ''; 
            $_POST['add_notes'] = isset($_POST['add_notes']) && !empty(strip_tags(trim($_POST['add_notes']))) ? strip_tags(trim($_POST['add_notes'])) : '';

            $_POST['emergency'] =  isset($_POST['emergency']) ? $_POST['emergency'] : ''; 
            $_POST['emergency_call'] =  isset($_POST['emergency_call']) ? $_POST['emergency_call'] : ''; 
            $_POST['emergency_add_notes'] =  !empty(strip_tags(trim($_POST['emergency_add_notes']))) ? strip_tags(trim($_POST['emergency_add_notes'])) : '';  
            $_POST['check_in'] =  isset($_POST['check_in']) ? $_POST['check_in'] : ''; 
            $_POST['check_in_unit'] =  isset($_POST['check_in_unit']) ? $_POST['check_in_unit'] : ''; 
            $_POST['check_out'] =  isset($_POST['check_out']) ? $_POST['check_out'] : ''; 
            $_POST['check_out_unit'] =  isset($_POST['check_out_unit']) ? $_POST['check_out_unit'] : ''; 
            $_POST['check_in_out_add_notes_val'] =  !empty(strip_tags(trim($_POST['check_in_out_add_notes']))) ? strip_tags(trim($_POST['check_in_out_add_notes'])) : '';          
            
            

            //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

            /* Check for any errors */
            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            /* Check for duplicate url if needed */
            if($_POST['url'] && $_POST['url'] != $menu->url) {

                if(db()->where('store_id', $store->store_id)->where('url', $_POST['url'])->getOne('menus', ['menu_id'])) {
                    Alerts::add_error(l('menu.error_message.url_exists'));
                }

            }

            $image = \Altum\Uploads::process_upload($menu->image, 'menu_images', 'image', 'image_remove', settings()->stores->menu_image_size_limit);

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                if(!$_POST['url']) {
                    $_POST['url'] = string_generate(10);

                    /* Generate random url if not specified */
                    while(db()->where('store_id', $store->store_id)->where('url', $_POST['url'])->getOne('menus', ['menu_id'])) {
                        $_POST['url'] = string_generate(10);
                    }
                }
                
                if($menu->menu_ref_id == 1){
                    $updateData = [];
                    $allowedFields = ['url', 'name', 'description', 'is_enabled', 'welcome_msg']; 
                }
                if($menu->menu_ref_id == 2){
                    $updateData = [];
                    $allowedFields = ['url', 'name', 'description', 'is_enabled', 'directions'];
                }
                if($menu->menu_ref_id == 3){
                    $updateData = [];
                    $allowedFields = ['url', 'name', 'description', 'is_enabled', 'bring_along'];
                }
                if($menu->menu_ref_id == 5){
                    $updateData = [];
                    $allowedFields = ['url', 'name', 'description', 'is_enabled', 'rules'];
                }
                if($menu->menu_ref_id == 7){
                    $updateData = [];
                    $allowedFields = ['url', 'name', 'description', 'is_enabled', 'network','network_password','add_notes'];
                }

                if($menu->menu_ref_id == 8){
                    $updateData = [];
                    $allowedFields = ['url', 'name', 'description', 'is_enabled', 'emergency','emergency_call','emergency_add_notes'];
                    echo '<pre>';
                    // print_r($postdata);
                    $emergency = json_encode($_POST['emergency']);
                    $emergency_call = json_encode($_POST['emergency_call']);
                    // print_r($emergency_call);
                    $emergency_add_notes = json_encode($_POST['emergency_add_notes_val']);
                    $total_rows = sizeof($_POST['emergency']);
                    // $update_emergenncy = $this->update_details($_POST,$store);
                    
                    $q = "UPDATE `menus` SET 
                        `emergency` = '".$emergency."', 
                        `emergency_call` = '".$emergency_call."', 
                        `emergency_add_notes` = '".$emergency_add_notes."' 
                        WHERE `menu_id` = '".$menu->menu_id."' AND
                         `user_id` = '".$this->user->user_id."' ";

                    $sql = database()->query($q);

                    /* Set a nice success message */
                    Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . $_POST['name'] . '</strong>'));

                    redirect('menu-update/' . $menu->menu_id);

                }
                if($menu->menu_ref_id == 4){
                    $updateData = [];
                    $allowedFields = ['url', 'name', 'description', 'is_enabled', 'check_in','check_in_unit','check_out','check_out_unit','check_in_out_add_notes'];
                }
                if($menu->menu_ref_id == 9){
                    $updateData = [];
                    $allowedFields = ['url', 'name', 'description'];
                    $update_store_health_service_data = $this->update_details($_POST,$store);
                }

                foreach ($_POST as $key => $value) {
                    if (in_array($key, $allowedFields)) {
                        $updateData[$key] = trim($value);
                    }
                }
                $updateData['last_datetime'] = \Altum\Date::$date;
                db()->where('menu_id', $menu->menu_id)->update('menus', $updateData);


                /* Clear the cache */
                \Altum\Cache::$adapter->deleteItemsByTag('store_id=' . $store->store_id);

                

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . $_POST['name'] . '</strong>'));

                redirect('menu-update/' . $menu->menu_id);
            }

        }

        /* Establish the account sub menu view */
        $data = [
            'menu_id' => $menu->menu_id,
            'resource_name' => $menu->name,
            'external_url' => $store->full_url . $menu->url            
        ];
        $app_sub_menu = new \Altum\View('partials/app_sub_menu', (array) $this);
        $this->add_view_content('app_sub_menu', $app_sub_menu->run($data));

        /* Set a custom title */
        Title::set(sprintf(l('menu_update.title'), $menu->name));

        /* Prepare the View */
        $data = [
            'store' => $store,
            'menu' => $menu,
            'store_health_services' => $store_health_services
        ];

        $view = new \Altum\View('menu-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function update_details($postdata,$store){  
        db()->where('store_id', $store->store_id)->delete('store_health_services');

        if(isset($_POST['healthe_service_name'])) {
            $total_rows = count($_POST['healthe_service_name']);
            for($i=0;$i<$total_rows;$i++){
                $data = array(
                    'healthe_service_name' => $_POST['healthe_service_name'][$i],
                    'speciality' => $_POST['speciality'][$i],
                    'website' => $_POST['website'][$i],
                    'contact_no' => $_POST['contact_no'][$i],
                    'contact_email' => $_POST['contact_email'][$i],
                    'walking_distance' => $_POST['walking_distance'][$i],
                    'driving_distance' => $_POST['driving_distance'][$i],
                    'store_id' => $store->store_id
                );
                $data['store_id'] = $store->store_id;
                $data['created_at'] = \Altum\Date::$date;
                $store_health_services_updateData = db()->insert('store_health_services', $data);
            } 
        }
       

        
                // $data = array(
                //     'healthe_service_name' => $_POST['healthe_service_name'],
                //     'speciality' => $_POST['speciality'],
                //     'website' => $_POST['website'],
                //     'contact_no' => $_POST['contact_no'],
                //     'contact_email' => $_POST['contact_email'],
                //     'walking_distance' => $_POST['walking_distance'],
                //     'driving_distance' => $_POST['driving_distance'],
                // );
                // //add data for health services:start
                // if($_POST['store_health_services_id'] != ""){
                //     $store_health_services_updateData = $data;
                //     $store_health_services_updateData['last_datetime'] = \Altum\Date::$date;
                    
                //     db()->where('id', $_POST['store_health_services_id'])->update('store_health_services', $store_health_services_updateData);
                // }else{
                //     $data['store_id'] = $store->store_id;
                //     $data['created_at'] = \Altum\Date::$date;
                //     $store_health_services_updateData = db()->insert('store_health_services', $data);
                // }                
                //add data for health services:end
    }

    public function update_emergency_details($postdata,$store){  
        // db()->where('store_id', $store->store_id)->delete('store_health_services');
        echo '<pre>';
        print_r($postdata);
        print_r($_POST['emergency']);
        exit;

        $total_rows = count($_POST['emergency']);
        for($i=0;$i<$total_rows;$i++){
            $data = array(
                'emergency' => $_POST['emergency'][$i],
                'emergency_call' => $_POST['emergency_call'][$i],
                'emergency_add_notes_val' => $_POST['emergency_add_notes_val'][$i]
            );
            $data['store_id'] = $store->store_id;
            $data['created_at'] = \Altum\Date::$date;
            $store_health_services_updateData = db()->insert('store_health_services', $data);
        } 
    }

}
