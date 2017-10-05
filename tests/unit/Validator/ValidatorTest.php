<?php

namespace Tests\Fei\Service\Audit\Validator;

use Codeception\Test\Unit;
use Fei\Service\Audit\Entity\AuditEvent;
use Fei\Service\Audit\Validator\AuditEventValidator;


class ValidatorTest extends Unit
{
    public function testValidation()
    {
        $validator  = new AuditEventValidator();
        $auditEvent = new AuditEvent();
        $auditEvent->setMessage('test')
            ->setLevel(2)
            ->setNamespace('namespace')
            ->setOrigin('http')
            ->setReportedAt('2016-10-10');

        $valid = $validator->validate($auditEvent);
        $this->assertEquals($valid, true);
    }

    public function testFail()
    {
        $validator  = new AuditEventValidator();
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('NotificationEndpoint origin has to be either "http", "cron" or "cli"');


        $this->assertEquals($validator->validateLevel(567768), false);
        $this->assertEquals($validator->validateMessage(''), false);
        $this->assertEquals($validator->validateNamespace(''), false);
        $this->assertEquals($validator->validateOrigin('ezf'), false);
        $this->assertEquals($validator->validateOrigin(''), false);
        $this->assertEquals($validator->validateReportedAt(''), false);


        $validator  = new AuditEventValidator();
        $mock = $this->createMock('Fei\Entity\AbstractEntity');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Entity to validate must be an instance of "AuditEvent"');

        $validator->validate($mock);


    }
}