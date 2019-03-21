<?php

declare(strict_types=1);

namespace Horat1us\Yii\ConstRange\Tests;

use PHPUnit\Framework\TestCase;
use Horat1us\Yii\ConstRange;

/**
 * Class ArrayObject
 * @package Horat1us\Yii\ConstRange\Tests
 */
class ArrayObjectTest extends TestCase
{
    public const TYPE_A = 'A';
    public const TYPE_B = 'B';
    public const NOT_TYPE_C = 'D';

    public function testNoPrefix(): void
    {
        $object = new ConstRange\ArrayObject(static::class);
        $object->getArrayCopy(); // testing for cache usage using coverage report

        $this->assertEquals(
            ['TYPE_A' => static::TYPE_A, 'TYPE_B' => static::TYPE_B, 'NOT_TYPE_C' => static::NOT_TYPE_C,],
            $object->getArrayCopy()
        );
    }

    public function testExcludeValues(): void
    {
        $object = new ConstRange\ArrayObject(static::class, '', [static::TYPE_B,]);

        $this->assertEquals(
            ['TYPE_A' => static::TYPE_A, 'NOT_TYPE_C' => static::NOT_TYPE_C,],
            $object->getArrayCopy()
        );
    }

    public function testExcludeAnotherPrefixes(): void
    {
        $object = new ConstRange\ArrayObject(static::class, 'TYPE_');

        $this->assertEquals(
            ['TYPE_A' => static::TYPE_A, 'TYPE_B' => static::TYPE_B,],
            $object->getArrayCopy()
        );
    }
}
