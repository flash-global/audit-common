<?php

namespace Fei\Service\Audit\Transformer;

/**
 * Class ContextConverter
 *
 * Context V1 format:
 * [
 *      ['key' => 'key1', 'value' => 'value1'],
 *      ['key' => 'key2', 'value' => 'value2']
 * ]
 *
 * Context V2 format:
 * [
 *      'key1' => 'value1',
 *      'key2' => 'value2'
 * ]
 *
 * @package Fei\Service\Audit\Service
 */
class ContextConverter
{
    /**
     * @param array $context
     *
     * @return array
     */
    public static function fromV1ToV2(array $context): array
    {
        if (array_key_exists('key', $context)
            && !is_array($context['key'])
            && array_key_exists('value', $context)
        ) {
            $context = [$context];
        }

        if (count($context) == 1 && !is_int(key($context))) {
            $context = [$context];
        }

        $result = [];
        foreach ($context as $item) {
            if (is_array($item)
                && array_key_exists('key', $item)
                && !is_array($item['key'])
                && array_key_exists('value', $item)
            ) {
                $result[$item['key']] = $item['value'];
            } elseif (is_array($item) && count($item) == 1 && !isset($result[key($item)])) {
                $result[key($item)] = current($item);
            }
        }

        return $result;
    }

    public static function fromV2ToV1(array $context): array
    {
        $result = [];
        foreach ($context as $key => $value) {
            $result[] = ['key' => $key, 'value' => $value];
        }

        return $result;
    }
}
