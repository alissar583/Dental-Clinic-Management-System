<?php

namespace App\Traits;

use App\Helpers\ResponseHelper;

/**
 * 
 */
trait ResponseTrait
{
    public function response($result, $status = null)
    {
        if (!$result['success']) {
            return ResponseHelper::error($result['message']);
        } else {
            return ResponseHelper::success($result['data'], $status);
        }
    }
}
