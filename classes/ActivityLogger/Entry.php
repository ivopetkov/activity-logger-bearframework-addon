<?php

/*
 * Activity logger addon for Bear Framework
 * https://github.com/ivopetkov/activity-logger-bearframework-addon
 * Copyright (c) Ivo Petkov
 * Free to use under the MIT license.
 */

namespace IvoPetkov\BearFrameworkAddons\ActivityLogger;

use \BearFramework\Models\Model;

/**
 * @property string $key
 * @property ?string $type
 * @property DateTime $date
 * @property ?string $title
 * @property ?string $description
 * @property array $data
 */
class Entry extends Model
{

    function __construct()
    {
        $this
                ->defineProperty('key', [
                    'type' => 'string',
                    'init' => function() {
                        return md5(uniqid());
                    }
                ])
                ->defineProperty('type', [
                    'type' => '?string'
                ])
                ->defineProperty('date', [
                    'type' => 'DateTime'
                ])
                ->defineProperty('title', [
                    'type' => '?string'
                ])
                ->defineProperty('description', [
                    'type' => '?string'
                ])
                ->defineProperty('data', [
                    'type' => 'array'
        ]);
    }

}
