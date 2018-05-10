<?php

/*
 * Activity logger addon for Bear Framework
 * https://github.com/ivopetkov/activity-logger-bearframework-addon
 * Copyright (c) 2018 Ivo Petkov
 * Free to use under the MIT license.
 */

namespace IvoPetkov\BearFrameworkAddons\ActivityLogger;

/**
 * @property ?string $key
 * @property ?string $type
 * @property ?DateTime $date
 * @property ?string $title
 * @property ?string $description
 * @property array $data
 */
class Entry
{

    use \IvoPetkov\DataObjectTrait;
    use \IvoPetkov\DataObjectToArrayTrait;
    use \IvoPetkov\DataObjectToJSONTrait;
    use \IvoPetkov\DataObjectFromArrayTrait;
    use \IvoPetkov\DataObjectFromJSONTrait;

    function __construct()
    {
        $this->defineProperty('key', [
            'type' => '?string',
            'set' => function($value) {
                if (preg_match('/^[0-9a-z]+$/', $value) === false) {
                    throw new Exception('The key can contain only lowercase letters and numbers');
                }
                return $value;
            }
        ]);
        $this->defineProperty('type', [
            'type' => '?string'
        ]);
        $this->defineProperty('date', [
            'type' => '?DateTime'
        ]);
        $this->defineProperty('title', [
            'type' => '?string'
        ]);
        $this->defineProperty('description', [
            'type' => '?string'
        ]);
        $this->defineProperty('data', [
            'type' => 'array'
        ]);
    }

}
