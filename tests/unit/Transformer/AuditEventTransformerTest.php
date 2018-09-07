<?php

namespace Tests\Fei\Service\Audit\Transformer;

use Fei\Service\Audit\Transformer\AuditEventTransformer;
use Fei\Service\Audit\Entity\AuditEvent;
use Fei\Service\Audit\Transformer\AuditEventTransformerV1;
use PHPUnit\Framework\TestCase;

class AuditEventTransformerTest extends TestCase
{
    public function testTransform()
    {
        $transformer = new AuditEventTransformer();
        $audit = new AuditEvent();

        $date = new \DateTime();

        $audit->setId('2938c3a3-36fc-4805-865d-f05df39ca435');
        $audit->setReportedAt($date);
        $audit->setLevel(AuditEvent::LVL_WARNING);
        $audit->setNamespace('/test-namespace');
        $audit->setMessage('test-message');
        $audit->setBackTrace('{}');
        $audit->setUser('test-user');
        $audit->setServer('test-server');
        $audit->setCommand('http://bob.net/bob');
        $audit->setOrigin('http');
        $audit->setCategory(AuditEvent::AUDIT);
        $audit->setEnv('test-env');
        $audit->setContext('test', 'aa');

        $this->assertEquals(
            [
                'id'          => '2938c3a3-36fc-4805-865d-f05df39ca435',
                'reported_at' => $date->format(\DateTime::ISO8601),
                'level'       => AuditEvent::LVL_WARNING,
                'namespace'   => '/test-namespace',
                'message'     => 'test-message',
                'backtrace'   => '{}',
                'user'        => 'test-user',
                'server'      => 'test-server',
                'command'     => 'http://bob.net/bob',
                'origin'      => 'http',
                'category'    => AuditEvent::AUDIT,
                'env'         => 'test-env',
                'context'     => [
                    'test' => 'aa'
                ]
            ],
            $transformer->transform($audit)
        );
    }

    public function testTransformerV1()
    {
        $transformer = new AuditEventTransformerV1();
        $audit = new AuditEvent();

        $date = new \DateTime();

        $audit->setId('2938c3a3-36fc-4805-865d-f05df39ca435');
        $audit->setReportedAt($date);
        $audit->setLevel(AuditEvent::LVL_WARNING);
        $audit->setNamespace('/test-namespace');
        $audit->setMessage('test-message');
        $audit->setBackTrace('{}');
        $audit->setUser('test-user');
        $audit->setServer('test-server');
        $audit->setCommand('http://bob.net/bob');
        $audit->setOrigin('http');
        $audit->setCategory(AuditEvent::AUDIT);
        $audit->setEnv('test-env');
        $audit->setContext('test', 'aa');

        $this->assertEquals(
            [
                'id'          => '2938c3a3-36fc-4805-865d-f05df39ca435',
                'reported_at' => $date->format(\DateTime::ISO8601),
                'level'       => AuditEvent::LVL_WARNING,
                'namespace'   => '/test-namespace',
                'message'     => 'test-message',
                'backtrace'   => '{}',
                'user'        => 'test-user',
                'server'      => 'test-server',
                'command'     => 'http://bob.net/bob',
                'origin'      => 'http',
                'category'    => AuditEvent::AUDIT,
                'env'         => 'test-env',
                'context'     => [
                    [
                        'key' => 'test',
                        'value' => 'aa'
                    ]
                ]
            ],
            $transformer->transform($audit)
        );
    }
}
