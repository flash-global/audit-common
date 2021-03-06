<?php

namespace Tests\Fei\Service\Audit\Entity;

use Fei\Service\Audit\Entity\AuditEvent;
use PHPUnit\Framework\TestCase;


class AuditEventTest extends TestCase
{
    public function testAccessors()
    {
        $this->testOneAccessors('id', '2938c3a3-36fc-4805-865d-f05df39ca435');
        $this->testOneAccessors('reportedAt', new \DateTime('2017-04-15'));
        $this->testOneAccessors('level', AuditEvent::LVL_WARNING);
        $this->testOneAccessors('namespace', '/test-namespace');
        $this->testOneAccessors('message', 'test-message');
        $this->testOneAccessors('backTrace', '{}');
        $this->testOneAccessors('user', 'test-user');
        $this->testOneAccessors('server', 'test-server');
        $this->testOneAccessors('command', 'http://bob.net/bob');
        $this->testOneAccessors('origin', 'http');
        $this->testOneAccessors('category', AuditEvent::AUDIT);
        $this->testOneAccessors('env', 'test-env');
        $this->testOneAccessors('entityCollection', 'audit_events');
    }

    public function testOthers()
    {
        $auditEventTest = new AuditEvent();
        $auditEventTest->setLevel(AuditEvent::LVL_WARNING);
        $auditEventTest->setOrigin('http');
        $auditEventTest->setCategory(AuditEvent::AUDIT);

        $this->assertEquals('Warning', $auditEventTest->getLevelLabel());
        $this->assertEquals('Audit', $auditEventTest->getCategoryLabel());
        $this->assertEquals('Origin', $auditEventTest->getCommandLabel());
    }

    protected function testOneAccessors($name, $expected)
    {
        $setter = 'set' . ucfirst($name);
        $getter = 'get' . ucfirst($name);
        $auditEventTest = new AuditEvent();
        $auditEventTest->$setter($expected);
        $this->assertEquals($auditEventTest->$getter(), $expected);
        $this->assertAttributeEquals($auditEventTest->$getter(), $name, $auditEventTest);
    }
}
