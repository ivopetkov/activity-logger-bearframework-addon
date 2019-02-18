<?php

/*
 * Activity logger addon for Bear Framework
 * https://github.com/ivopetkov/activity-logger-bearframework-addon
 * Copyright (c) Ivo Petkov
 * Free to use under the MIT license.
 */

namespace IvoPetkov\BearFrameworkAddons;

use \BearFramework\Models\ModelsRepository;

/**
 *
 */
class ActivityLoggerFactory
{

    /**
     * 
     * @param type $contextID
     * @return \IvoPetkov\BearFrameworkAddons\ActivityLogger
     */
    public function makeContext($contextID): \IvoPetkov\BearFrameworkAddons\ActivityLogger
    {
        return new \IvoPetkov\BearFrameworkAddons\ActivityLogger($contextID);
    }

}
