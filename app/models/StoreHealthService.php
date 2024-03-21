<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Models;

class StoreHealthService extends Model {

      public function delete($store_health_service_id) {
        /* Delete the store */
        db()->where('id', $store_health_service_id)->delete('store_health_services');
    }

}
