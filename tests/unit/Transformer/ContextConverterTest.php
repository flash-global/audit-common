<?php

namespace Tests\Fei\Service\Audit\Transformer;

use Fei\Service\Audit\Transformer\ContextConverter;
use PHPUnit\Framework\TestCase;

/**
 * Class ContextConverterTest
 *
 * @package Tests\Fei\Service\Audit\Service
 */
class ContextConverterTest extends TestCase
{
    /**
     * @dataProvider dataForTestContextV1FormatIsConvertedToV2Format
     *
     * @param array $context
     * @param array $expected
     */
    public function testContextV1FormatIsConvertedToV2Format(array $context, array $expected)
    {
        $this->assertEquals($expected, ContextConverter::fromV1ToV2($context));
    }

    public function dataForTestContextV1FormatIsConvertedToV2Format()
    {
        return [
            [[], []],
            [
                [['key' => 'key1', 'value' => 'value1']], ['key1' => 'value1']
            ],
            [
                [
                    ['key' => 'key1', 'value' => 'value1'],
                    ['key' => 'key2', 'value' => 'value2']
                ],
                ['key1' => 'value1', 'key2' => 'value2']
            ],
            [
                [
                    ['key' => 'key1', 'value' => 'value1'],
                    ['key2' => 'value2']
                ],
                ['key1' => 'value1']
            ],
            [
                [
                    ['key' => 'key1', 'value' => 'value1'],
                    ['key1' => 'value2']
                ],
                ['key1' => 'value1']
            ]
        ];
    }

    /**
     * @dataProvider dataForTestContextV2FormatIsConvertedToV1Format
     *
     * @param array $context
     * @param array $expected
     */
    public function testContextV2FormatIsConvertedToV1Format(array $context, array $expected)
    {
        $this->assertEquals($expected, ContextConverter::fromV2ToV1($context));
    }

    public function dataForTestContextV2FormatIsConvertedToV1Format()
    {
        return [
            [[], []],
            [
                ['key1' => 'value1'], [['key' => 'key1', 'value' => 'value1']]
            ],
            [
                ['key1' => 'value1', 'key2' => 'value2'],
                [
                    ['key' => 'key1', 'value' => 'value1'],
                    ['key' => 'key2', 'value' => 'value2']
                ]
            ],
            [
                ['key1' => 'value1', ['key' => 'key1', 'value' => 'value2']],
                [
                    ['key' => 'key1', 'value' => 'value1'],
                    ['key' => 0, 'value' => ['key' => 'key1', 'value' => 'value2']]
                ]
            ]
        ];
    }
}
