<?php

/*
 * Activity logger addon for Bear Framework
 * https://github.com/ivopetkov/activity-logger-bearframework-addon
 * Copyright (c) 2018 Ivo Petkov
 * Free to use under the MIT license.
 */

namespace IvoPetkov\BearFrameworkAddons;

use BearFramework\App;
use \BearFramework\Models\ModelsRepository;

/**
 *
 */
class ActivityLogger extends ModelsRepository
{

    public function __construct()
    {
        $this->setModel(\IvoPetkov\BearFrameworkAddons\ActivityLogger\Entry::class);
        $this->useAppDataDriver('ivopetkov-activity-logger');
    }

    /**
     * 
     * @param string $type
     * @param array $data
     * @return \IvoPetkov\BearFrameworkAddons\ActivityLogger\Entry
     */
    public function log(string $type, array $data = []): \IvoPetkov\BearFrameworkAddons\ActivityLogger\Entry
    {
        $entry = $this->make();
        $entry->type = $type;
        $entry->data = $data;
        $this->set($entry);
        return $entry;
    }

}
