<?php

namespace Fei\Service\Audit\Validator;

use Fei\Service\Audit\Entity\AuditEvent;
use ObjectivePHP\Validation\HeapValidationChain;
use ObjectivePHP\Validation\Rule\Callback;
use ObjectivePHP\Validation\Rule\StringLength;

/**
 * Class AuditEventValidator
 *
 * @package Fei\Service\Audit\Validator
 */
class AuditEventValidator extends HeapValidationChain
{
    public function init()
    {
        $levelLabelsKeys    = array_keys(AuditEvent::getLevelLabels());
        $categoryLabelsKeys = array_keys(AuditEvent::getCategoryLabels());

        $this->registerRule('reported_at', new Callback(function($value) {
            return $value instanceof \DateTime;
        }));
        $this->registerRule('level', new Callback(function($value) use ($levelLabelsKeys) {
            return in_array($value, $levelLabelsKeys);
        }));

        $this->registerRule('namespace', new StringLength(1, 255));
        $this->registerRule('message', new StringLength(1, 255));

        $this->registerRule('backtrace', new Callback(function($value) {
            return is_null($value) || (new StringLength(0, 255))->validate($value);
        }));
        $this->registerRule('user', new Callback(function($value) {
            return is_null($value) || (new StringLength(0, 255))->validate($value);
        }));
        $this->registerRule('server', new Callback(function($value) {
            return is_null($value) || (new StringLength(0, 255))->validate($value);
        }));
        $this->registerRule('command', new Callback(function($value) {
            return is_null($value) || (new StringLength(0, 255))->validate($value);
        }));

        $this->registerRule('origin', new Callback(function($value) {
            return in_array($value, array('http', 'cli', 'cron'));
        }));
        $this->registerRule('category', new Callback(function($value) use ($categoryLabelsKeys) {
            return in_array($value, $categoryLabelsKeys);
        }));
        $this->registerRule('env', new StringLength(1, 255));
    }

    /**
     * @param mixed $entity
     * @param null $context
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function validate($entity, $context = null) : bool
    {
        if (!$entity instanceof AuditEvent) {
            throw new \Exception('The Entity to validate must be an instance of \Fei\Service\Audit\Entity\AuditEvent');
        }

        return parent::validate($entity, $context);
    }
}
