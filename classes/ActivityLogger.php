<?php

/*
 * Activity logger addon for Bear Framework
 * https://github.com/ivopetkov/activity-logger-bearframework-addon
 * Copyright (c) 2018 Ivo Petkov
 * Free to use under the MIT license.
 */

namespace IvoPetkov\BearFrameworkAddons;

use BearFramework\App;

/**
 * 
 */
class ActivityLogger
{

    private $cache = [];
    private $namespace = '';

    /**
     * 
     * @param string $namespace
     * @return IvoPetkov\BearFrameworkAddons\ActivityLogger
     */
    public function createContext(string $namespace): \IvoPetkov\BearFrameworkAddons\ActivityLogger
    {
        $oldNamespace = $this->namespace;
        $this->namespace = trim(trim($oldNamespace, '/') . '.' . trim($namespace, '/'), '/');
        $clone = clone($this);
        $this->namespace = $oldNamespace;
        return $clone;
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

    /**
     * 
     * @return \IvoPetkov\BearFrameworkAddons\ActivityLogger\Entry
     */
    public function make(string $type = null, array $data = []): \IvoPetkov\BearFrameworkAddons\ActivityLogger\Entry
    {
        if (!isset($this->cache['entry'])) {
            $this->cache['entry'] = new \IvoPetkov\BearFrameworkAddons\ActivityLogger\Entry();
        }
        $entry = clone($this->cache['entry']);
        if ($type !== null) {
            $entry->type = $type;
        }
        $entry->key = md5(uniqid());
        $entry->date = new \DateTime();
        $entry->data = $data;
        return $entry;
    }

    /**
     * 
     * @param \IvoPetkov\BearFrameworkAddons\ActivityLogger\Entry $entry
     */
    public function set(\IvoPetkov\BearFrameworkAddons\ActivityLogger\Entry $entry): void
    {
        $app = App::get();
        if (strlen($entry->key) === 0) {
            throw new Exception('Key cannot be empty');
        }
        if ($entry->date === null) {
            throw new Exception('Date cannot be empty');
        }
        $app->data->set($app->data->make($this->getDataKey($entry->key), $entry->toJSON()));
    }

    /**
     * 
     * @param string $key
     * @return bool
     */
    public function exists(string $key): bool
    {
        $app = App::get();
        $key = strtolower($key);
        return $app->data->exists($this->getDataKey($key));
    }

    /**
     * 
     * @param string $key
     */
    public function delete(string $key): void
    {
        $app = App::get();
        $app->data->delete($this->getDataKey($key));
    }

    /**
     * 
     * @param string $key
     * @return null|\IvoPetkov\BearFrameworkAddons\ActivityLogger\Entry
     */
    public function get(string $key): ?\IvoPetkov\BearFrameworkAddons\ActivityLogger\Entry
    {
        $app = App::get();
        $key = strtolower($key);
        $rawData = $app->data->getValue($this->getDataKey($key));
        if ($rawData !== null) {
            return $this->makeFromRawData($rawData);
        }
        return null;
    }

    /**
     * 
     * @param string $rawData
     * @return ?\IvoPetkov\BearFrameworkAddons\ActivityLogger\Entry
     */
    private function makeFromRawData(string $rawData): ?\IvoPetkov\BearFrameworkAddons\ActivityLogger\Entry
    {
        return \IvoPetkov\BearFrameworkAddons\ActivityLogger\Entry::fromJSON($rawData);
    }

    /**
     * 
     * @return \IvoPetkov\DataList
     */
    public function getList(): \IvoPetkov\DataList
    {
        return new \IvoPetkov\DataList(function() {
            $app = App::get();

            $list = $app->data->getList()
                    ->filterBy('key', $this->getNamespaceDataKeyPrefix() . 'entry/', 'startWith');
            $result = [];
            foreach ($list as $dataItem) {
                $result[] = $this->makeFromRawData($dataItem->value);
            }
            return $result;
        });
    }

    /**
     * 
     * @return string
     */
    private function getNamespaceDataKeyPrefix(): string
    {
        $namespaceMD5 = md5($this->namespace);
        return md5('ivopetkov-activity-logger') . '-activity-logger/' . substr($namespaceMD5, 0, 3) . '/' . substr($namespaceMD5, 3, 3) . '/' . substr($namespaceMD5, 6) . '/';
    }

    /**
     * 
     * @param string $key
     * @return string
     */
    private function getDataKey(string $key): string
    {
        $keyMD5 = md5($key);
        return $this->getNamespaceDataKeyPrefix() . 'entry/' . substr($keyMD5, 0, 3) . '/' . substr($keyMD5, 3, 3) . '/' . substr($keyMD5, 6);
    }

}
