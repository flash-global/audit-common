<?php
    
namespace Fei\Service\Audit\Transformer;

use Fei\Service\Audit\Entity\AuditEvent;
use League\Fractal\TransformerAbstract;

/**
 * Class AuditEventTransformer
 *
 * @package Fei\Service\Audit\Entity
 */
class AuditEventTransformer extends TransformerAbstract
{

    /**
     * @param AuditEvent $auditEvent
     *
     * @return array
     */
    public function transform(AuditEvent $auditEvent): array
    {
        return array(
            'id'          => $auditEvent->getId(),
            'reported_at' => $auditEvent->getReportedAt()->format(\DateTime::ISO8601),
            'level'       => (int) $auditEvent->getLevel(),
            'namespace'   => $auditEvent->getNamespace(),
            'message'     => $auditEvent->getMessage(),
            'backtrace'   => $auditEvent->getBackTrace(),
            'user'        => $auditEvent->getUser(),
            'server'      => $auditEvent->getServer(),
            'command'     => $auditEvent->getCommand(),
            'origin'      => $auditEvent->getOrigin(),
            'category'    => (int) $auditEvent->getCategory(),
            'env'         => $auditEvent->getEnv(),
            'context'     => $auditEvent->getContext(),
        );
    }
}
