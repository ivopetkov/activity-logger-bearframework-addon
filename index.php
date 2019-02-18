<?php

/*
 * Activity logger addon for Bear Framework
 * https://github.com/ivopetkov/activity-logger-bearframework-addon
 * Copyright (c) Ivo Petkov
 * Free to use under the MIT license.
 */

use BearFramework\App;

$app = App::get();
$context = $app->contexts->get(__FILE__);

$context->classes
        ->add('IvoPetkov\BearFrameworkAddons\ActivityLogger', 'classes/ActivityLogger.php')
        ->add('IvoPetkov\BearFrameworkAddons\ActivityLoggerFactory', 'classes/ActivityLoggerFactory.php')
        ->add('IvoPetkov\BearFrameworkAddons\ActivityLogger\*', 'classes/ActivityLogger/*.php');

$app->shortcuts
        ->add('activityLogger', function() {
            return new \IvoPetkov\BearFrameworkAddons\ActivityLoggerFactory();
        });
