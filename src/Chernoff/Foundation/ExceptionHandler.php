<?php

namespace Chernoff\Foundation;

use Chernoff\ORM\Models\Admin;

/**
 * Class Resolver
 * @package Chernoff\Foundation
 */
class ExceptionHandler
{
    /**
     * @param \Exception $e
     */
    public function handle(\Exception $e)
    {
        $admin = Admin::firstOrNew(array('roleid' => 1));

        if ($admin->exists) {
            localAPI('logactivity', array('description' => $e->getMessage()), $admin->username);
        }
    }
}
