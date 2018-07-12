<?php

/*
 * Activity logger addon for Bear Framework
 * https://github.com/ivopetkov/activity-logger-bearframework-addon
 * Copyright (c) Ivo Petkov
 * Free to use under the MIT license.
 */

/**
 * @runTestsInSeparateProcesses
 */
class ActivityLoggerTest extends BearFramework\AddonTests\PHPUnitTestCase
{

    /**
     * 
     */
    public function testLog()
    {
        $app = $this->getApp();
        $activityLogger = $app->activityLogger->makeContext('user-123');

        $activityLogger->log('account', ['action' => 'login']);
        sleep(1);
        $activityLogger->log('account', ['action' => 'logout']);
        $entry = $activityLogger->make();
        $entry->type = 'account';
        $entry->data = ['action' => 'register'];
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
        $activityLogger = $app->activityLogger->makeContext('user-123');

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

    /**
     * 
     */
    public function testContexts()
    {
        $app = $this->getApp();
        $activityLogger1 = $app->activityLogger->makeContext('user-1');
        $activityLogger1->log('account', ['action' => 'pay-order-1']);

        $activityLogger2 = $app->activityLogger->makeContext('user-2');
        $activityLogger2->log('account', ['action' => 'pay-order-2']);

        $this->assertTrue($activityLogger1->getList()->length === 1);
        $this->assertTrue($activityLogger1->toArray()[0]['data']['action'] === 'pay-order-1');

        $this->assertTrue($activityLogger2->getList()->length === 1);
        $this->assertTrue($activityLogger2->toArray()[0]['data']['action'] === 'pay-order-2');
    }

}
