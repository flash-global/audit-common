<?php

    namespace Fei\Service\Audit\Entity;


    use League\Fractal\TransformerAbstract;
    use Fei\Service\Logger\Entity\ContextTransformer as LoggerContextTransformer;

    class ContextTransformer extends LoggerContextTransformer
    {

        public function transform(Context $context)
        {

            return array(
                'id'  => (int) $context->getId(),
                'key' => $context->getKey(),
                'value' => $context->getValue()
            );
        }
    }
