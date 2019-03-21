<?php

declare(strict_types=1);

namespace Horat1us\Yii\ConstRange\Tests;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Horat1us\Yii\ConstRange;
use yii\base;

/**
 * Class ValidatorTest
 * @package Horat1us\Yii\ConstRange\Tests
 */
class ValidatorTest extends TestCase
{
    public const TYPE_A = 'A';
    public const TYPE_B = 'B';
    public const TYPE_C = 'C';
    public const TYPE_NUMBER = '1';

    /** @var ConstRange\Validator|MockObject */
    protected $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new ConstRange\Validator([
            'targetClass' => static::class,
            'prefix' => 'TYPE_',
        ]);
    }

    public function testValidateValueWithoutPrefix(): void
    {
        $this->validator->targetClass = static::class;
        $this->validator->prefix = null;

        $this->expectException(base\NotSupportedException::class);
        $this->expectExceptionMessage(
            'Horat1us\Yii\ConstRange\Validator does not support validateValue() without specifying range.'
        );

        $this->validator->validate(static::TYPE_A);
    }

    public function testValidateValueWithoutClass(): void
    {
        $this->validator->targetClass = null;
        $this->validator->prefix = 'PREFIX_';

        $this->expectException(base\NotSupportedException::class);
        $this->expectExceptionMessage(
            'Horat1us\Yii\ConstRange\Validator does not support validateValue() without specifying target class.'
        );

        $this->validator->validate(static::TYPE_A);
    }

    public function testValidateValueFailure(): void
    {
        $this->validator->prefix = 'TYPE_';
        $this->validator->targetClass = static::class;

        $this->assertFalse(
            $this->validator->validate((int)static::TYPE_NUMBER)
        );
    }

    public function testValidateValueFailureWhenExcept(): void
    {
        $this->validator->prefix = 'TYPE_A';
        $this->validator->except = [static::TYPE_A];

        $this->assertFalse(
            $this->validator->validate(static::TYPE_A)
        );
    }

    public function testValidateSuccess(): void
    {
        $this->assertTrue(
            $this->validator->validate(static::TYPE_A)
        );
    }

    public function testValidateSuccessWithFilter(): void
    {
        $this->validator->filter = 'strval';
        $this->assertTrue(
            $this->validator->validate((int)static::TYPE_NUMBER)
        );
    }

    public function testValidateAttribute(): void
    {
        $this->validator->targetClass = null;
        $this->validator->prefix = null;

        $model = new base\DynamicModel([
            'type',
        ]);

        $this->validator->validateAttribute($model, 'type');
        $this->assertTrue($model->hasErrors('type'));

        $this->assertNull($this->validator->targetClass);
        $this->assertNull($this->validator->prefix);
    }
}
