<?php
    
    namespace Fei\Service\Audit\Entity;
    
    
    use Fei\Service\Logger\Entity\NotificationTransformer as LoggerNotificationTransformer;
    
    class NotificationTransformer extends LoggerNotificationTransformer
    {
        
        public function transform(Notification $notification)
        {
            
            $contextItems = array();
            
            foreach ($notification->getContext() as $contextItem)
            {
                $contextItems[$contextItem->getKey()] = $contextItem->getValue();
            }
            
            return array(
                'id'          => (int) $notification->getId(),
                'reported_at' => $notification->getReportedAt()->format(\DateTime::ISO8601),
                'level'       => (int) $notification->getLevel(),
                'flags'       => (int) $notification->getFlags(),
                'namespace'   => $notification->getNamespace(),
                'message'     => $notification->getMessage(),
                'backtrace'   => $notification->getBackTrace(),
                'user'        => $notification->getUser(),
                'server'      => $notification->getServer(),
                'command'     => $notification->getCommand(),
                'origin'      => $notification->getOrigin(),
                'category'    => $notification->getCategory(),
                'env'         => $notification->getEnv(),
                'context'     => $contextItems,
            );
        }
    }
