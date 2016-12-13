<?php
    
    namespace Fei\Service\Audit\Entity;
    
    
    use League\Fractal\TransformerAbstract;
    
    class AuditEventTransformer extends TransformerAbstract
    {
        
        public function transform(AuditEvent $auditEvent)
        {
            
            $contextItems = array();
            
            foreach ($auditEvent->getContext() as $contextItem)
            {
                $contextItems[$contextItem->getKey()] = $contextItem->getValue();
            }
            
            return array(
                'id'          => (int) $auditEvent->getId(),
                'reported_at' => $auditEvent->getReportedAt()->format(\DateTime::ISO8601),
                'level'       => (int) $auditEvent->getLevel(),
                'flags'       => (int) $auditEvent->getFlags(),
                'namespace'   => $auditEvent->getNamespace(),
                'message'     => $auditEvent->getMessage(),
                'backtrace'   => $auditEvent->getBackTrace(),
                'user'        => $auditEvent->getUser(),
                'server'      => $auditEvent->getServer(),
                'command'     => $auditEvent->getCommand(),
                'origin'      => $auditEvent->getOrigin(),
                'category'    => $auditEvent->getCategory(),
                'env'         => $auditEvent->getEnv(),
                'context'     => $contextItems,
            );
        }
    }
