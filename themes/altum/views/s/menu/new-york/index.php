<?php defined('ALTUMCODE') || die() ?>

<?= $this->views['header'] ?>

<div class="container <?= $this->store->cart_is_enabled ? 'mt-7' : 'mt-5' ?> mt-md-5">

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li>
                <a href="<?= $data->store->full_url ?>"><?= l('s_store.breadcrumb') ?></a> <div class="svg-sm text-muted d-inline-block"><?= include_view(ASSETS_PATH . 'images/s/chevron-right.svg') ?></div>
            </li>
            <li class="active" aria-current="page"><?= $data->menu->name ?></li>
        </ol>
    </nav>
<?php endif ?>
       
    <h1 class="h3"><?= $data->menu->name ?></h1>
    <p class="text-muted"><?= $data->menu->description ?></p>
    

    <?php $details = getdata($data); ?>
     
    <div class="d-flex flex-column flex-wrap flex-lg-row my-5">
        <?php foreach($data->categories as $category): ?>
        <a href="<?= $data->store->full_url . $data->menu->url . '#category_' . $category->category_id ?>" class="col-12 col-lg-2 mb-3 mr-3 text-truncate btn btn-sm btn-link bg-primary-100">
            <?= $category->name ?>
        </a>
        <?php endforeach ?>
    </div>

    <div class="">
        <?php foreach($data->categories as $category): ?>

        <h2 class="h4" id="<?= 'category_' . $category->category_id ?>"><?= $category->name ?></h2>
        <p class="text-muted"><?= $category->description ?></p>

        <div class="row">
            <?php foreach($data->items as $item): ?>
                <?php if($category->category_id != $item->category_id) continue ?>
                <div class="col-12 col-lg-6 my-3">
                    <div class="d-flex position-relative h-100 rounded p-3 bg-gray-50">
                        <?php if(!empty($item->image)): ?>
                        <div class="store-item-image-wrapper mr-4">
                            <a href="<?= $data->store->full_url . $data->menu->url . '/' . $category->url . '/' . $item->url ?>">
                                <img src="<?= \Altum\Uploads::get_full_url('item_images') . $item->image ?>" class="store-item-image-background" loading="lazy" />
                            </a>
                        </div>
                        <?php endif ?>

                        <div class="d-flex flex-column justify-content-between w-100">
                            <div>
                                <h3 class="h5 mb-1">
                                    <a href="<?= $data->store->full_url . $data->menu->url . '/' . $category->url . '/' . $item->url ?>">
                                        <?= $item->name ?>
                                    </a>
                                </h3>

                                <p class="mt-1 text-muted"><?= string_truncate($item->description, 100) ?></p>
                            </div>

                            <div class="mt-3">
                                <div>
                                    <span class="h5 text-black">
                                        <?= nr($item->price, 2) ?>
                                    </span>
                                    <span class="text-muted">
                                        <?= $data->store->currency ?>
                                    </span>
                                </div>

                                <?php if($this->store->cart_is_enabled): ?>
                                    <div class="mt-3">
                                        <?php if($item->variants_is_enabled): ?>
                                            <a href="<?= $data->store->full_url . $data->menu->url . '/' . $category->url . '/' . $item->url ?>" class="btn btn-block btn-sm btn-primary">
                                                <div class="svg-sm d-inline-block"><?= include_view(ASSETS_PATH . 'images/s/shopping-cart.svg') ?></div>
                                                <?= l('s_item.configure') ?>
                                            </a>
                                        <?php else: ?>
                                            <button
                                                    type="button"
                                                    class="add_to_cart btn btn-block btn-sm btn-primary"

                                                    data-item-price="<?= $item->price ?>"
                                                    data-item-id="<?= $item->item_id ?>"
                                                    data-item-name="<?= $item->name ?>"
                                                    data-item-full-url="<?= $data->store->full_url . $data->menu->url . '/' . $category->url . '/' . $item->url ?>"
                                                    data-item-full-image="<?= $item->image ? \Altum\Uploads::get_full_url('item_images') . $item->image : null ?>"
                                            >

                                                <div class="add_to_cart_not_added">
                                                    <div class="svg-sm d-inline-block"><?= include_view(ASSETS_PATH . 'images/s/shopping-cart.svg') ?></div>
                                                    <?= l('s_item.add_to_cart') ?>
                                                </div>

                                                <div class="add_to_cart_added d-none">
                                                    <div class="svg-sm d-inline-block"><?= include_view(ASSETS_PATH . 'images/s/check-circle.svg') ?></div>
                                                    <?= l('s_item.added_to_cart') ?>
                                                </div>

                                            </button>
                                        <?php endif ?>
                                    </div>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>

        <?php endforeach ?>
    </div>

</div>

<?php
    function getdata($data){
         
    if($data->menu->menu_ref_id == 1) {  ?>
        <?php if($data->menu->welcome_msg != ""): ?>
            <p class="text-muted"><?= $data->menu->welcome_msg ?></p>
        <?php endif ?>
    <?php } ?>

    <?php if($data->menu->menu_ref_id == 2) {  ?>
        <?php if($data->menu->directions != ""): ?>
             <p class="text-muted"><?= $data->menu->directions ?></p>
        <?php endif ?>
    <?php } ?>

    <?php if($data->menu->menu_ref_id == 3) {  ?>
        <?php if($data->menu->bring_along != ""): ?>
            <p class="text-muted"><?= $data->menu->bring_along ?></p>
        <?php endif ?>    
    <?php } ?>

    <?php if($data->menu->menu_ref_id == 4) {  ?>
        <?php if($data->menu->check_in > 0): ?>
            <p class="text-muted">Check In Time: <?= $data->menu->check_in." ".$data->menu->check_in_unit ?> </p>
        <?php endif ?> 
        <?php if($data->menu->check_out > 0): ?>   
            <p class="text-muted">Check Out Time: <?= $data->menu->check_out." ".$data->menu->check_out_unit ?></p>
        <?php endif ?>  
        <?php if($data->menu->check_in_out_add_notes != ""): ?>
            <label>Additional details</label>
            <p class="text-muted"><?= $data->menu->check_in_out_add_notes ?></p>
        <?php endif ?>  
    <?php } ?>

    <?php if($data->menu->menu_ref_id == 5) {  ?>
        <?php if($data->menu->rules != ""): ?>
            <p class="text-muted"><?= $data->menu->rules ?></p>
        <?php endif ?>     
    <?php } ?>

    <?php if($data->menu->menu_ref_id == 7) {  ?>

        <?php if($data->menu->network != ""): ?>
            <label>WiFi Network</label>
            <p class="text-muted"><?= $data->menu->network ?></p>
        <?php endif ?> 
        <?php if($data->menu->network_password != ""): ?>
            <label>WiFi Password</label>
            <p class="text-muted"><?= $data->menu->network_password ?></p>
        <?php endif ?>    
        
        <?php if($data->menu->add_notes != ""): ?>
            <label>Additional details</label>
            <p class="text-muted"><?= $data->menu->add_notes ?></p>
        <?php endif ?>    
    <?php }

    if($data->menu->menu_ref_id == 8) {  ?>

        <?php if($data->menu->emergency != ""): ?>
            <label>Emergency Service</label>
            <p class="text-muted"><?= $data->menu->emergency ?></p>
        <?php endif ?> 
        <?php if($data->menu->emergency_call != ""): ?>
            <label>Emergency Number</label>
            <p class="text-muted"><?= $data->menu->emergency_call ?></p>
        <?php endif ?>    
        
        <?php if($data->menu->emergency_add_notes != ""): ?>
            <label>Additional Details</label>
            <p class="text-muted"><?= $data->menu->emergency_add_notes ?></p>
        <?php endif ?>    
    <?php }
        if($data->menu->menu_ref_id == 9) { 
            if(!empty($data->health_services)){ ?>
               <div class="container">
                    <div class="row">
                        <?php foreach ($data->health_services as $services): ?>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $services->healthe_service_name; ?></h5>
                                        <p class="card-text"><strong>Speciality:</strong> <?php echo $services->speciality; ?></p>
                                        <p class="card-text"><strong>Website:</strong> <a href="<?php echo $services->website; ?>"><?php echo $services->website; ?></a></p>
                                        <p class="card-text"><strong>Contact No:</strong> <?php echo $services->contact_no; ?></p>
                                        <p class="card-text"><strong>Contact Email:</strong> <?php echo $services->contact_email; ?></p>
                                        <p class="card-text"><strong>Walking Distance:</strong> <?php echo $services->walking_distance; ?></p>
                                        <p class="card-text"><strong>Driving Distance:</strong> <?php echo $services->driving_distance; ?></p>
                                        <p class="card-text"><strong>Created At:</strong> <?php echo $services->created_at; ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

    <?php }
            
        }
    }
 ?>
<?= include_view(THEME_PATH . 'views/s/partials/js_quick_add_to_cart.php', ['store' => $data->store]) ?>

<?php if($data->store->settings->display_share_buttons): ?>
<?= include_view(THEME_PATH . 'views/s/partials/share.php', ['external_url' => $data->store->full_url . $data->menu->url]) ?>
<?php endif ?>
