<?php defined('ALTUMCODE') || die() ?>

<?php defined('ALTUMCODE') || die() ?>

<nav class="navbar navbar-light bg-white fixed-top store-navbar d-lg-none">
    <div class="container">
        <a class="navbar-brand text-truncate col p-0" href="<?= $data->store->full_url ?>">
            <?php if($data->store->logo): ?>
                <img src="<?= \Altum\Uploads::get_full_url('store_logos') . $data->store->logo ?>" class="img-fluid store-navbar-logo mr-3" alt="<?= $data->store->name ?>" loading="lazy" />
            <?php endif ?>

            <?= $data->store->name ?>
        </a>

        <?php if($this->store->cart_is_enabled): ?>
        <ul class="navbar-nav col-auto p-0">
            <li class="nav-item">
                <a class="nav-link store-cart-link" href="<?= $data->store->full_url . '?page=cart' ?>">
                    <div class="svg-md d-inline-block"><?= include_view(ASSETS_PATH . 'images/s/shopping-cart.svg') ?></div>
                    <span class="badge badge-danger badge-pill"></span>
                </a>
            </li>
        </ul>
        <?php endif ?>
    </div>
</nav>

<div class="container mt-5 d-none d-lg-block">
    <div class="d-flex justify-content-between align-items-center">
        <div class="d-flex position-relative">
            <?php if($data->store->logo): ?>
                <div class="mr-4">
                    <img src="<?= \Altum\Uploads::get_full_url('store_logos') . $data->store->logo ?>" class="img-fluid store-logo" alt="<?= $data->store->name ?>" loading="lazy" />
                </div>
            <?php endif ?>

            <div class="d-flex flex-column">
                <a href="<?= $data->store->full_url ?>" class="stretched-link">
                    <span class="h1 mb-0 store-title"><?= $data->store->name ?></span>
                </a>

                <?php if($data->store->description): ?>
                    <span class="store-description">
                        <?= $data->store->description ?>
                    </span>
                <?php endif ?>
            </div>
        </div>

        <?php if($this->store->cart_is_enabled): ?>
        <a href="<?= $data->store->full_url . '?page=cart' ?>" class="btn btn-outline-primary store-cart-link">
            <div class="svg-md d-inline-block"><?= include_view(ASSETS_PATH . 'images/s/shopping-cart.svg') ?></div>
            <?= l('s_cart.menu') ?>
            <span class="badge badge-danger badge-pill"></span>
        </a>
        <?php endif ?>
    </div>
</div>

<?php /* Only display the cover background if there is an image and only on the store index */ ?>
<?php if(!empty($data->store->image) && \Altum\Router::$controller_key == 'store'): ?>
<div class="container mt-8 mb-5 my-lg-5">
    <a href="<?= $data->store->full_url ?>">
        <div class="store-cover-wrapper">
            <div
                class="store-cover-background"
                style="<?= !empty($data->store->image) ? 'background-image: url(\'' . \Altum\Uploads::get_full_url('store_images') . $data->store->image . '\')' : null ?>"
            ></div>
        </div>
    </a>
</div>
<?php else: ?>
    <div class="container my-2 ">&nbsp;</div>
<?php endif ?>

<?php if($this->store->cart_is_enabled): ?>
<?php ob_start() ?>
<script>
    'use strict';

    let cart_count = () => {

        let cart_name = <?= json_encode($data->store->store_id . '_cart') ?>;

        let cart = localStorage.getItem(cart_name) ? JSON.parse(localStorage.getItem(cart_name)) : [];

        document.querySelectorAll('.store-cart-link').forEach(element => {

            if(cart.length) {
                element.querySelector('span').innerText = cart.length;
            } else {
                element.querySelector('span').innerText = '';
            }

        });

    }

    cart_count();

    /* Listen for changes on the localstorage on other potential tabs */
    window.addEventListener('storage', () => {
        cart_count();
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
<?php endif ?>


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

        <?php $total = count(json_decode($data->menu->emergency_call,true)); ?>
        <?php
            $emergency = json_decode($data->menu->emergency,true); 
            $emergency_call = json_decode($data->menu->emergency_call,true);
            $emergency_add_notes = json_decode($data->menu->emergency_add_notes,true);
        ?>

        <div class="container">
            <div class="row">
                <?php for($i = 0; $i < $total; $i++) { ?>
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <?php if($data->menu->emergency != ""): ?>
                                    <h5 class="card-title">Emergency Service</h5>
                                    <p class="card-text"><?= $emergency[$i] ?></p>
                                <?php endif ?> 
                                <?php if($data->menu->emergency_call != ""): ?>
                                  
                                    <p class="card-text"><strong>Emergency Number</strong> <?= $emergency_call[$i] ?></p>
                                <?php endif ?>    
                                
                                <?php if($data->menu->emergency_add_notes != ""): ?>
                                    
                                    <p class="card-text"><strong>Additional Details</strong> <?= $emergency_add_notes[$i] ?></p>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>    
    <?php }
        if($data->menu->menu_ref_id == 9) { 
            if(!empty($data->health_services)){ ?>
               <div class="container">
                    <div class="row">
                        <?php foreach ($data->health_services as $services): ?>
                            <div class="col-md-6 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $services->healthe_service_name; ?></h5>
                                        <p class="card-text"><strong>Speciality:</strong> <?php echo $services->speciality; ?></p>
                                        <p class="card-text"><strong>Website:</strong> <a href="<?php echo $services->website; ?>"><?php echo $services->website; ?></a></p>
                                        <p class="card-text"><strong>Contact No:</strong> <?php echo $services->contact_no; ?></p>
                                        <p class="card-text"><strong>Contact Email:</strong> <?php echo $services->contact_email; ?></p>
                                        <p class="card-text"><strong>Walking Distance:</strong> <?php echo $services->walking_distance; ?> Minutes</p>
                                        <p class="card-text"><strong>Driving Distance:</strong> <?php echo $services->driving_distance; ?> Minutes</p>
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
