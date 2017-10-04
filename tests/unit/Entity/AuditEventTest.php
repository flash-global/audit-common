<?php

namespace Tests\Fei\Service\Audit\Entity;

use Codeception\Test\Unit;
use Fei\Service\Audit\Entity\AuditEvent;

class AuditEventTest extends Unit
{
    public function testHydration()
    {
        $auditEventData = array(
            'message' => 'test message',
            'namespace' => '/test',
            'origin' => 'cli',
            'context' => [
                'test' => 'test value',
                'test2' => 'test value2'
            ]
        );

        $auditEvent = new AuditEvent($auditEventData);

        $this->assertCount(2, $auditEvent->getContext());

        $context = $auditEvent->getContext();

        $keys    = array_keys($context);
        $this->assertEquals('test', reset($keys));
        $this->assertEquals('test value', reset($context));
    }

    public function testEmptySerialization()
    {
        $auditEvent = new AuditEvent();
        $auditEvent->setOrigin('cron');

        $serialized = json_encode($auditEvent->toArray());

        $return = new AuditEvent();
        $return = $return->hydrate(json_decode($serialized, true));

        $this->assertEquals($auditEvent, $return);
    }

    public function testSerialization()
    {
        $auditEvent = new AuditEvent();
        $auditEvent
            ->setId(1)
            ->setReportedAt('2016-07-18')
            ->setLevel(AuditEvent::LVL_DEBUG)
            ->setFlags(1)
            ->setNamespace('/Test/AnotherTest')
            ->setMessage('This is a message')
            ->setBackTrace(['A back trace']) //FIXME The string `[]` doesn't work here
            ->setUser('A user')
            ->setServer('serverName')
            ->setCommand('acommand')
            ->setOrigin('http')
            ->setCategory(AuditEvent::AUDIT)
            ->setEnv('test')
            ->setContext([
                'a key' => 'a value',
                'a another key' => 'a another value'
            ]);

        $serialized = json_encode($auditEvent->toArray());

        $return = new AuditEvent();
        $return = $return->hydrate(json_decode($serialized, true));

        $this->assertEquals($auditEvent, $return);
    }
}
