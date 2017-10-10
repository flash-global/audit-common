<?php

namespace Fei\Service\Audit\Transformer;

use Fei\Service\Audit\Entity\AuditEvent;
use League\Fractal\TransformerAbstract;

/**
 * Class AuditEventTransformerV1
 *
 * @package Fei\Service\Audit\Transformer
 */
class AuditEventTransformerV1 extends TransformerAbstract
{
    /**
     * @param AuditEvent $auditEvent
     *
     * @return array
     */
    public function transform(AuditEvent $auditEvent)
    {
        return array(
            'id'          => (int) $auditEvent->getId(),
            'reported_at' => $auditEvent->getReportedAt()->format(\DateTime::ISO8601),
            'level'       => (int) $auditEvent->getLevel(),
            //'flags'       => (int) $auditEvent->getFlags(),
            'namespace'   => $auditEvent->getNamespace(),
            'message'     => $auditEvent->getMessage(),
            'backtrace'   => $auditEvent->getBackTrace(),
            'user'        => $auditEvent->getUser(),
            'server'      => $auditEvent->getServer(),
            'command'     => $auditEvent->getCommand(),
            'origin'      => $auditEvent->getOrigin(),
            'category'    => (int) $auditEvent->getCategory(),
            'env'         => $auditEvent->getEnv(),
            'context'     => ContextConverter::fromV2ToV1($auditEvent->getContext()),
        );
    }
}
