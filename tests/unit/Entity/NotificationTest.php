<?php

namespace Tests\Fei\Service\Audit\Entity;

use Codeception\Test\Unit;
use Fei\Service\Audit\Entity\Context;
use Fei\Service\Audit\Entity\AuditEvent;

class AuditEventTest extends Unit
{
    public function testHydration()
    {
        $auditEventData = array(
            'message' => 'test message',
            'namespace' => '/test',
            'origin' => 'cli',
            'context' => array(
                array('key' => 'test', 'value' => 'test value'),
                array('key' => 'test2', 'value' => 'test value2')
            )
        );

        $auditEvent = new AuditEvent($auditEventData);

        $this->assertCount(2, $auditEvent->getContext());

        $context = $auditEvent->getContext()->first();
        $this->assertInstanceOf(Context::class, $context);
        $this->assertEquals('test', $context->getKey());
        $this->assertEquals('test value', $context->getValue());
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
                new Context(['id' => 1, 'key' => 'a key', 'value' => 'a value']),
                new Context(['id' => 2, 'key' => 'a another key', 'value' => 'a another value'])
            ]);

        $serialized = json_encode($auditEvent->toArray());

        $return = new AuditEvent();
        $return = $return->hydrate(json_decode($serialized, true));

        $this->assertEquals($auditEvent, $return);
    }
}
