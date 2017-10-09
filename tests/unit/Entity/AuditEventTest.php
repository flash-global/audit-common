<?php

namespace Tests\Fei\Service\Audit\Entity;

use Codeception\Test\Unit;
use Fei\Service\Audit\Entity\AuditEventTransformer;
use Fei\Service\Audit\Entity\Context;
use Fei\Service\Audit\Entity\AuditEvent;
use Fei\Service\Audit\Entity\ContextTransformer;

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


        $data = ['context' => '{"key" : "value"}'];
        $auditEvent = new AuditEvent();
        $return = $auditEvent->hydrate($data);

        $this->assertEquals($auditEvent, $return);


        $context = new Context();
        $context->setValue('value');
        $context->setKey('key');
        $auditEvent = new AuditEvent();
        $auditEvent->setContext($context);
        $this->assertEquals($context, $auditEvent->getContext()[0]);

        $context = [0 => ['key' => 'key', 'value' => 'value', 'id' => 25]];
        $auditEvent = new AuditEvent();
        $auditEvent->setContext($context);
        /** @var Context $ctx */
        $ctx = $auditEvent->getContext()[0];
        $this->assertEquals($context, [0 => ['key' => $ctx->getKey(), 'value' => $ctx->getValue(), 'id' => $ctx->getId()]]);

        $context = ['key' => 'value'];
        $auditEvent = new AuditEvent();
        $auditEvent->setContext($context);
        /** @var Context $ctx */
        $ctx = $auditEvent->getContext()[0];
        $this->assertEquals($context, [$ctx->getKey() => $ctx->getValue()]);
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

    public function testTranformer()
    {
        $date = new \DateTime();

        $auditEventData = array(
            'id' => 25,
            'level' => 2,
            'flags' => 0,
            'message' => 'test message',
            'backtrace' => "['Backtrace']",
            'user' => null,
            'server' => 'server',
            'command' => 'command',
            'category' => 2,
            'env' => 'n/c',
            'namespace' => '/test',
            'origin' => 'cli',
            'reported_at' => $date,
            'context' => array(
                array('key' => 'test', 'value' => 'test value'),
                array('key' => 'test2', 'value' => 'test value2')
            )
        );

        $auditEvent  = new AuditEvent($auditEventData);

        $expected = $auditEventData;
        $expected['reported_at'] = $date->format(\DateTime::ISO8601);
        $expected['context'] = [
            'test' => 'test value',
            'test2' => 'test value2'
        ];

        $transformer = new AuditEventTransformer();
        $transformed = $transformer->transform($auditEvent);

        $this->assertEquals($transformed, $expected);
    }

    public function testContextTransformer()
    {
        $context = new Context();
        $context->setKey('test');
        $context->setValue('value');

        $transformer = new ContextTransformer();
        $transformed = $transformer->transform($context);

        $expected = [
            'id' => 0,
            'key' => 'test',
            'value' => 'value'
        ];

        $this->assertEquals($transformed, $expected);
    }

    public function testSetOrigin()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('NotificationEndpoint origin has to be either "http", "cron" or "cli"');
        $auditEventTest = new AuditEvent();
        $auditEventTest->setOrigin('https');
    }

}
