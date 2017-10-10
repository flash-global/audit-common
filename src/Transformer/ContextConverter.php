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
        $result = [];
        foreach ($context as $item) {
            if (is_array($item)
                && array_key_exists('key', $item)
                && !is_array($item['key'])
                && array_key_exists('value', $item)
            ) {
                $result[$item['key']] = $item['value'];
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
