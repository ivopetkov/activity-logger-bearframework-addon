<?php

/*
 * Activity logger addon for Bear Framework
 * https://github.com/ivopetkov/activity-logger-bearframework-addon
 * Copyright (c) 2018 Ivo Petkov
 * Free to use under the MIT license.
 */

/**
 * @runTestsInSeparateProcesses
 */
class ActivityLoggerTest extends BearFrameworkAddonTestCase
{

    /**
     * 
     */
    public function testLog()
    {
        $app = $this->getApp();
        $activityLogger = $app->activityLogger->createContext('user:123');

        $activityLogger->log('account', ['action' => 'login']);
        sleep(1);
        $activityLogger->log('account', ['action' => 'logout']);
        $entry = $activityLogger->make('account', ['action' => 'register']);
        $entry->date = \DateTime::createFromFormat('U', time() - 10);
        $activityLogger->set($entry);
        $list = $activityLogger->getList()
                ->sortBy('date', 'asc');
        $this->assertTrue($list[0]->data['action'] === 'register');
        $this->assertTrue($list[1]->data['action'] === 'login');
        $this->assertTrue($list[2]->data['action'] === 'logout');
    }

    /**
     * 
     */
    public function testExistsAndDelete()
    {
        $app = $this->getApp();
        $activityLogger = $app->activityLogger->createContext('user:123');

        $this->assertTrue($activityLogger->getList()->length === 0);
        $entry1 = $activityLogger->log('account', ['action' => 'login']);
        $entry2 = $activityLogger->log('account', ['action' => 'logout']);
        $this->assertTrue($activityLogger->getList()->length === 2);
        $this->assertTrue($activityLogger->exists($entry1->key));
        $this->assertTrue($activityLogger->exists($entry2->key));
        $this->assertTrue($activityLogger->get($entry1->key)->key === $entry1->key);
        $this->assertTrue($activityLogger->get($entry2->key)->key === $entry2->key);
        $activityLogger->delete($entry1->key);
        $this->assertTrue($activityLogger->exists($entry1->key) === false);
        $this->assertTrue($activityLogger->get($entry1->key) === null);
        $this->assertTrue($activityLogger->getList()->length === 1);
        $activityLogger->delete($entry2->key);
        $this->assertTrue($activityLogger->exists($entry2->key) === false);
        $this->assertTrue($activityLogger->get($entry2->key) === null);
        $this->assertTrue($activityLogger->getList()->length === 0);
    }

}
