<?php

namespace Tests\Fei\Service\Audit\Validator;

use Fei\Service\Audit\Entity\AuditEvent;
use Fei\Service\Audit\Validator\AuditEventValidator;
use PHPUnit\Framework\TestCase;

class AuditEventValidatorTest extends TestCase
{
    public function testValidationFail()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('The Entity to validate must be an instance of \Fei\Service\Audit\Entity\AuditEvent');


        $validator  = new AuditEventValidator();
        $auditEvent = new AuditEventValidator();

        $validator->validate($auditEvent);
    }

    /**
     * @param bool $expectedResult
     * @param array $data
     *
     * @dataProvider provider
     */
    public function testValidation(bool $expectedResult, array $data)
    {
        $validator  = new AuditEventValidator();
        $auditEvent = new AuditEvent();

        $auditEvent->setReportedAt($data[0]);
        $auditEvent->setLevel($data[1]);
        //$auditEvent->setFlags($data[2]);
        $auditEvent->setNamespace($data[3]);
        $auditEvent->setMessage($data[4]);
        $auditEvent->setBackTrace($data[5]);
        $auditEvent->setUser($data[6]);
        $auditEvent->setServer($data[7]);
        $auditEvent->setCommand($data[8]);
        $auditEvent->setOrigin($data[9]);
        $auditEvent->setCategory($data[10]);
        $auditEvent->setEnv($data[11]);
        $auditEvent->setContext($data[12], $data[13]);

        $result = $validator->validate($auditEvent);
        $this->assertEquals($expectedResult, $result);
    }

    public function provider() : array
    {
        return [
            [true, [
                '2017-04-15', AuditEvent::LVL_WARNING, 2, '/test-namespace', 'test-message', '{}',
                'test-user', 'test-server', 'http://bob.net/bob', 'http', AuditEvent::AUDIT,
                'test-env', 'test', 'boom'
            ]],
            [true, [
                '2017-04-15', AuditEvent::LVL_WARNING, 2, '/test-namespace', 'test-message', '{}',
                'test-user', 'test-server', 'http://bob.net/bob', 'http', AuditEvent::AUDIT,
                'test-env', 'test', 'boom'
            ]],
            [true, [
                '2017-04-15', AuditEvent::LVL_WARNING, 2, '/test-namespace', 'test-message', '{}',
                '', 'test-server', 'http://bob.net/bob', 'http', AuditEvent::AUDIT,
                'test-env', 'test', 'boom'
            ]],
            [false, [
                '2017-04-15', 'SHOULD BE AN INTEGER', 2, '/test-namespace', 'test-message', '{}',
                'test-user', 'test-server', 'http://bob.net/bob', 'http', AuditEvent::AUDIT,
                'test-env', 'test', 'boom'
            ]],
            [true, [
                '2017-04-15', AuditEvent::LVL_WARNING, 2, '/test-namespace', 'test-message', '',
                '', '', 'http://bob.net/bob', 'http', AuditEvent::AUDIT,
                'test-env', 'test', 'boom'
            ]],
            [true, [
                '2017-04-15', AuditEvent::LVL_WARNING, 2, '/test-namespace', 'test-message', null,
                null, null, 'http://bob.net/bob', 'http', AuditEvent::AUDIT,
                'test-env', 'test', 'boom'
            ]],
        ];
    }
}