<?php

    namespace Fei\Service\Audit\Entity;


    use League\Fractal\TransformerAbstract;

    class ContextTransformer extends TransformerAbstract
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
