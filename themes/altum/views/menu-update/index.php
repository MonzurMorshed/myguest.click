<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li>
                <a href="<?= url('dashboard') ?>"><?= l('dashboard.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
            </li>
            <li>
                <a href="<?= url('store/' . $data->store->store_id) ?>"><?= l('store.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
            </li>
            <!-- <li>
                <a href="<?= url('menu/' . $data->menu->menu_id) ?>"><?= l('menu.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
            </li> -->
            <li class="active" aria-current="page"><?= l('menu_update.breadcrumb') ?></li>
        </ol>
    </nav>
<?php endif ?>

   <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h4 text-truncate mb-0"><i class="fas fa-fw fa-xs fa-list mr-1"></i> <?= sprintf(l('global.update_x'), $data->menu->name) ?></h1>

       <div class="d-flex align-items-center col-auto p-0">
            <div>
                <button
                        id="url_copy"
                        type="button"
                        class="btn btn-link text-secondary"
                        data-toggle="tooltip"
                        title="<?= l('global.clipboard_copy') ?>"
                        aria-label="<?= l('global.clipboard_copy') ?>"
                        data-copy="<?= l('global.clipboard_copy') ?>"
                        data-copied="<?= l('global.clipboard_copied') ?>"
                        data-clipboard-text="<?= $data->store->full_url . $data->menu->url ?>"
                >
                    <i class="fas fa-fw fa-sm fa-copy"></i>
                </button>
            </div>

           <?= include_view(THEME_PATH . 'views/menu/menu_dropdown_button.php', ['id' => $data->menu->menu_id, 'resource_name' => $data->menu->name]) ?>
       </div>
    </div>

    <p class="text-truncate">
        <a href="<?= $data->store->full_url . $data->menu->url ?>" target="_blank" rel="noreferrer">
            <i class="fas fa-fw fa-sm fa-external-link-alt text-muted mr-1"></i> <?= remove_url_protocol_from_url($data->store->full_url . $data->menu->url) ?>
        </a>
    </p>

    <form action="" method="post" role="form" enctype="multipart/form-data" id="menuupdate_form">
        <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

        <label for="url"><i class="fas fa-fw fa-sm fa-bolt text-muted mr-1"></i> <?= l('menu.input.url') ?></label>
        <div class="mb-3">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><?= $data->store->full_url ?></span>
                </div>
                <input type="text" id="url" name="url" class="form-control" value="<?= $data->menu->url ?>" placeholder="<?= l('menu.input.url_placeholder') ?>" />
            </div>
            <small class="form-text text-muted"><?= l('menu.input.url_help') ?></small>
        </div>

        <div class="form-group">
            <label for="name"><i class="fas fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('menu.input.name') ?></label>
            <input type="text" id="name" name="name" class="form-control" value="<?= $data->menu->name ?>" placeholder="<?= l('menu.input.name_placeholder') ?>" required="required" />
        </div>

        <div class="form-group">
            <label for="description"><i class="fas fa-fw fa-sm fa-pen text-muted mr-1"></i> <?= l('menu.input.description') ?></label>
            <input type="text" id="description" name="description" class="form-control" value="<?= $data->menu->description ?>" />
            <small class="form-text text-muted"><?= l('menu.input.description_help') ?></small>
        </div>

        <div class="form-group">
            <label for="image"><i class="fas fa-fw fa-sm fa-image text-muted mr-1"></i> <?= l('menu.input.image') ?></label>
            <?php if(!empty($data->menu->image)): ?>
                <div class="row">
                    <div class="my-2 col-6 col-xl-4">
                        <img src="<?= \Altum\Uploads::get_full_url('menu_images') . $data->menu->image ?>" class="img-fluid" loading="lazy" />
                    </div>
                </div>
                <div class="custom-control custom-checkbox my-2">
                    <input id="image_remove" name="image_remove" type="checkbox" class="custom-control-input" onchange="this.checked ? document.querySelector('#image').classList.add('d-none') : document.querySelector('#image').classList.remove('d-none')">
                    <label class="custom-control-label" for="image_remove">
                        <span class="text-muted"><?= l('global.delete_file') ?></span>
                    </label>
                </div>
            <?php endif ?>
            <input id="image" type="file" name="image" accept="<?= \Altum\Uploads::get_whitelisted_file_extensions_accept('menu_images') ?>" class="form-control-file altum-file-input" />
            <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('menu_images')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->stores->menu_image_size_limit) ?></small>
        </div>
         
        
        <?php if($data->menu->menu_ref_id == 1){ 
                $savedWelcomeMsg = ""; if($data->menu->welcome_msg != ""){ $savedWelcomeMsg = $data->menu->welcome_msg; } ?>
                <input type="hidden" name="welcomemsg" id="welcomemsg" value="<?php echo $savedWelcomeMsg; ?>">
                <div class="form-group">
                    <label for="content"><i class="fas fa-fw fa-sm fa-message text-muted mr-1"></i> <?= l('menu.input.welcome_msg_main') ?></label>
                    <div id="welcome_msg"></div>
                    <small class="form-text text-muted"><?= l('menu.input.welcome_msg') ?></small>
                    <input type="hidden" id="welcome_msg_input" name="welcome_msg">
                </div>
        <?php } ?>
        
        <?php if($data->menu->menu_ref_id == 2){ 
              $savedDirections = ""; if($data->menu->directions != ""){ $savedDirections = $data->menu->directions; } ?>
              <input type="hidden" name="directions_val" id="directions_val" value="<?php echo $savedDirections; ?>">
              <div class="form-group">
                <label for="directions"><i class="fas fa-fw fa-sm fa-road text-muted mr-1"></i> <?= l('menu.input.directions_main') ?></label>
                <div id="directions"></div>
                <small class="form-text text-muted"><?= l('menu.input.directions') ?></small>
                <input type="hidden" id="directions_input" name="directions">
              </div>
        <?php } ?>
         
        <?php if($data->menu->menu_ref_id == 3){
                $savedBringAlong = ""; if($data->menu->bring_along != ""){ $savedBringAlong = $data->menu->bring_along; } ?>
                <input type="hidden" name="bring_along_val" id="bring_along_val" value="<?php echo $savedBringAlong; ?>">
                <div class="form-group">
                    <label for="bring_along"><i class="fas fa-fw fa-sm fa-shopping-bag text-muted mr-1"></i> <?= l('menu.input.bring_along_main') ?></label>
                    <div id="bring_along"></div>
                    <small class="form-text text-muted"><?= l('menu.input.bring_along') ?></small>
                    <input type="hidden" id="bring_along_input" name="bring_along">
                </div>
        <?php } ?>        
        
        <?php if($data->menu->menu_ref_id == 5){
                $savedRules = ""; if($data->menu->rules != ""){ $savedRules = $data->menu->rules; } ?>
                <input type="hidden" name="rules_val" id="rules_val" value="<?php echo $savedRules; ?>">
                <div class="form-group">
                    <label for="rules"><i class="fas fa-fw fa-sm fa-list-alt text-muted mr-1"></i> <?= l('menu.input.rules_main') ?></label>
                    <div id="rules"></div>
                    <small class="form-text text-muted"><?= l('menu.input.rules') ?></small>
                    <input type="hidden" id="rules_input" name="rules">
                </div>
        <?php } ?> 
        
        <?php if($data->menu->menu_ref_id == 7){ ?>
                <div class="form-group">
                    <label for="network"><i class="fas fa-fw fa-sm fa-wifi text-muted mr-1"></i> <?= l('menu.input.network') ?></label>
                    <input type="text" id="network" name="network" class="form-control" value="<?= $data->menu->network ?>" />
                </div>
                <div class="form-group">
                    <label for="network_password"><i class="fas fa-fw fa-sm fa-lock text-muted mr-1"></i> <?= l('menu.input.network_password') ?></label>
                    <input type="text" id="network_password" name="network_password" class="form-control" value="<?= $data->menu->network_password ?>" />
                </div>
                <?php $savedNotes = ""; if($data->menu->add_notes != ""){ $savedNotes = $data->menu->add_notes; } ?>
                <input type="hidden" name="add_notes_val" id="add_notes_val" value="<?php echo $savedNotes; ?>">
                <div class="form-group">
                    <label for="add_notes"><i class="fas fa-fw fa-sm fa-list-alt text-muted mr-1"></i> <?= l('menu.input.add_notes') ?></label>
                    <div id="add_notes"></div>
                    <input type="hidden" id="add_notes_input" name="add_notes">
                </div>
        <?php } ?> 

        <?php if($data->menu->menu_ref_id == 8){ //echo '<pre>'; print_r($data);
        ?>
            <?php 
                // echo '<pre>';
                // print_r($data->menu);
                // exit;
                $total = count(json_decode($data->menu->emergency_call,true));
                $emergency = json_decode($data->menu->emergency,true); 
                $emergency_call = json_decode($data->menu->emergency_call,true);
                $emergency_add_notes = json_decode($data->menu->emergency_add_notes,true);

                for($i = 0; $i < $total; $i++) {
            ?>

                <div id="emergency_div">
                    <div class="form-group">
                        <label for="emergency"><i class="fas fa-fw fa-sm fa-clock text-muted mr-1"></i> <?= l('menu.input.emergency') ?></label>
                        <input type="text" id="emergency" name="emergency[]" class="form-control" value="<?= $emergency[$i] ?>" />
                    </div>
                    <div class="form-group">
                        <label for="emergency_call"><i class="fas fa-fw fa-sm fa-phone text-muted mr-1"></i> <?= l('menu.input.emergency_call') ?></label>
                        <input type="text" id="emergency_call" name="emergency_call[]" class="form-control" value="<?= $emergency_call[$i] ?>" />
                    </div>
                    <?php $savedEmergencyNotes = ""; if($data->menu->emergency_add_notes != ""){ $savedEmergencyNotes = $data->menu->emergency_add_notes; } ?>
                    <input type="hidden" name="emergency_add_notes_val[]" id="emergency_add_notes_val" value="<?php echo $savedEmergencyNotes; ?>">
                    <div class="form-group">
                        <label for="emergency_add_notes"><i class="fas fa-fw fa-sm fa-list-alt text-muted mr-1"></i> <?= l('menu.input.emergency_add_notes') ?></label>
                        <div id="emergency_add_notes"></div>
                        <input type="hidden" id="emergency_add_notes_input[]" name="emergency_add_notes">
                    </div>
                </div>
                <?php } ?>
                <hr class="separaator" />
        <?php } ?> 
            <div class="form-group">                   
                <div class="input-group">
                    <div class="input-group">
                        <button type="button" class="btn btn-primary  mr-2" id="add_emergency">Add Another</button>                                    
                    </div>
                </div>
            </div>
            
        <?php if($data->menu->menu_ref_id == 9){ ?>
            <?php 
                $total_rows = count($data->store_health_services);
                if($total_rows == 0){
                    $total_rows = 1;
                }
                for($i=0;$i<$total_rows;$i++){ ?>
                    <div id="health_services_div">
                        <div class="form-group">
                            <label for="healthe_service_name"><i class="fas fa-fw fa-sm fa-ambulance text-muted mr-1"></i> <?= l('menu.input.healthe_service_name') ?></label>
                            <input type="text" id="healthe_service_name" name="healthe_service_name[]" class="form-control" value="<?= ($data->store_health_services[$i]->healthe_service_name) ? $data->store_health_services[$i]->healthe_service_name : "" ?>" required="required"/>
                            <input type="hidden" name="store_health_services_id[]" id="store_health_services_id" value="<?= $data->store_health_services[$i]->id ?>">
                        </div>
                        <div class="form-group">
                            <label for="speciality"><i class="fas fa-fw fa-sm fa-medkit text-muted mr-1"></i> <?= l('menu.input.healthe_service_speciality') ?></label>
                            <input type="text" id="speciality" name="speciality[]" class="form-control" value="<?= ($data->store_health_services[$i]->speciality) ? $data->store_health_services[$i]->speciality : "" ?>" />
                        </div>
                        <div class="form-group">
                            <label for="website"><i class="fas fa-fw fa-sm fa-globe text-muted mr-1"></i> <?= l('menu.input.healthe_service_website') ?></label>
                            <input type="text" id="website" name="website[]" class="form-control" value="<?= ($data->store_health_services[$i]->website)? $data->store_health_services[$i]->website : "" ?>" />
                        </div>
                        <div class="form-group">
                            <label for="contact_no"><i class="fas fa-fw fa-sm fa-phone text-muted mr-1"></i> <?= l('menu.input.healthe_service_contact_no') ?></label>
                            <input type="text" id="contact_no" name="contact_no[]" class="form-control" value="<?= ($data->store_health_services[$i]->contact_no) ? $data->store_health_services[$i]->contact_no : "" ?>" required="required" />
                        </div>
                        <div class="form-group">
                            <label for="contact_email"><i class="fas fa-fw fa-sm fa-envelope text-muted mr-1"></i> <?= l('menu.input.healthe_service_contact_email') ?></label>
                            <input type="text" id="contact_email" name="contact_email[]" class="form-control" value="<?= ($data->store_health_services[$i]->contact_email) ? $data->store_health_services[$i]->contact_email : "" ?>" />
                        </div>
                        <div class="form-group">
                            <label for="walking_distance"><i class="fas fa-fw fa-sm fa-walking text-muted mr-1"></i> <?= l('menu.input.healthe_service_walking_distance') ?></label>
                            <input type="text" id="walking_distance" name="walking_distance[]" class="form-control" value="<?= ($data->store_health_services[$i]->walking_distance) ? $data->store_health_services[$i]->walking_distance : "" ?>" />
                        </div>
                        <div class="form-group">
                            <label for="driving_distance"><i class="fas fa-fw fa-sm fa-road text-muted mr-1"></i> <?= l('menu.input.healthe_service_driving_distance') ?></label>
                            <input type="text" id="driving_distance" name="driving_distance[]" class="form-control" value="<?= ($data->store_health_services[$i]->driving_distance) ? $data->store_health_services[$i]->driving_distance : "" ?>" />                            
                        </div>
                        <?php if($total_rows > 1){ ?>
                            <div class="form-group">                   
                                <div class="input-group">
                                    <div class="input-group">
                                    <button type="button" class="btn btn-danger" id="remove_health_service" data-id="<?= $data->store_health_services[$i]->id ?>">Remove</button>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>     
                    </div>
                    <hr class="separator">
               <?php }  ?>                 
                        <div class="form-group">                   
                            <div class="input-group">
                                <div class="input-group">
                                    <button type="button" class="btn btn-primary  mr-2" id="add_health_service">Add Another</button>                                    
                                </div>
                            </div>
                        </div>
            
        <?php } ?>

        <?php if($data->menu->menu_ref_id == 4){ ?>
            <div class="form-group">
                <div class="row align-items-center">
                    <div class="col-md-2">
                        <label for="check_in"><i class="fas fa-fw fa-sm fa-clock text-muted mr-1"></i> <?= l('menu.input.check_in') ?></label>
                    </div>
                    <div class="col-md-2">
                        <select id="check_in" name="check_in" class="form-control form-control-sm">
                            <!-- <?php for($i=1;$i<=12;$i++){ ?>
                                <option value="<?php echo $i; ?>" <?php if($data->menu->check_in == $i){ echo "selected"; } ?>><?php echo $i; ?></option>
                            <?php } ?> -->
                            <?php
                            for ($i = 1; $i <= 12; $i++) {
                                for ($j = 0; $j <= 30; $j += 30) {
                                    $hour = str_pad($i, 2, "0", STR_PAD_LEFT);
                                    $minute = str_pad($j, 2, "0", STR_PAD_LEFT);
                                    $time = "$hour:$minute";
                            ?>
                                    <option value="<?php echo $time; ?>" <?php if ($data->menu->check_in == $time) { echo "selected"; } ?>><?php echo $time; ?></option>
                            <?php
                                }
                            }                            
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select id="check_in_unit" name="check_in_unit" class="form-control form-control-sm">
                            <option value="AM" <?php if($data->menu->check_in_unit == "AM"){ echo "selected"; } ?>>AM</option>
                            <option value="PM" <?php if($data->menu->check_in_unit == "PM"){ echo "selected"; } ?>>PM</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="check_out"><i class="fas fa-fw fa-sm fa-clock text-muted mr-1"></i> <?= l('menu.input.check_out') ?></label>
                    </div>
                    <div class="col-md-2">
                        <select id="check_out" name="check_out" class="form-control form-control-sm">
                            <!-- <?php for($i=1;$i<=12;$i++){ ?>
                                <option value="<?php echo $i; ?>" <?php if($data->menu->check_out == $i){ echo "selected"; } ?>><?php echo $i; ?></option>
                            <?php } ?> -->
                            <?php
                            for ($i = 1; $i <= 12; $i++) {
                                for ($j = 0; $j <= 30; $j += 30) {
                                    $hour = str_pad($i, 2, "0", STR_PAD_LEFT);
                                    $minute = str_pad($j, 2, "0", STR_PAD_LEFT);
                                    $time = "$hour:$minute";
                            ?>
                                    <option value="<?php echo $time; ?>" <?php if ($data->menu->check_out == $time) { echo "selected"; } ?>><?php echo $time; ?></option>
                            <?php
                                }
                            }                            
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select id="check_out_unit" name="check_out_unit" class="form-control form-control-sm">
                            <option value="AM" <?php if($data->menu->check_out_unit == "AM"){ echo "selected"; } ?>>AM</option>
                            <option value="PM" <?php if($data->menu->check_out_unit == "PM"){ echo "selected"; } ?>>PM</option>
                        </select>
                    </div>
                </div>
            </div>

                <?php $savedCheckInOutNotes = ""; if($data->menu->check_in_out_add_notes != ""){ $savedCheckInOutNotes = $data->menu->check_in_out_add_notes; } ?>
                <input type="hidden" name="check_in_out_add_notes_val" id="check_in_out_add_notes_val" value="<?php echo $savedCheckInOutNotes; ?>">
                <div class="form-group">
                    <label for="check_in_out_add_notes"><i class="fas fa-fw fa-sm fa-list-alt text-muted mr-1"></i> <?= l('menu.input.check_in_out_add_notes') ?></label>
                    <div id="check_in_out_add_notes"></div>
                    <input type="hidden" id="check_in_out_add_notes_input" name="check_in_out_add_notes">
                </div>
        <?php } ?>


        <div class="form-group custom-control custom-switch">
            <input id="is_enabled" name="is_enabled" type="checkbox" class="custom-control-input" <?= $data->menu->is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="is_enabled"><?= l('menu.input.is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('menu.input.is_enabled_help') ?></small>
        </div>

        <button type="submit" name="submit" class="btn btn-block btn-primary"><?= l('global.update') ?></button>
    </form>

</div>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>


<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_form.php', [
    'name' => 'menu',
    'resource_id' => 'menu_id',
    'has_dynamic_resource_name' => true,
    'path' => 'menu/delete'
]), 'modals'); ?>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/duplicate_modal.php', ['modal_id' => 'menu_duplicate_modal', 'resource_id' => 'menu_id', 'path' => 'menu/duplicate']), 'modals'); ?>
