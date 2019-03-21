<?php

declare(strict_types=1);

namespace Horat1us\Yii\ConstRange;

use yii\validators;
use yii\base;

/**
 * Class Validator
 * @package Horat1us\Yii\ConstRange
 */
class Validator extends validators\RangeValidator
{
    /**
     * @var string|null class constant name prefix
     * Uppercase attribute name  with `_` suffix will be used by default: `PREFIX_`
     */
    public $prefix = null;

    /** @var string class with const range (model class will be used by default) */
    public $targetClass;

    /** @var \Closure|callable will be used to transform value before validation */
    public $filter;

    /** @var string[] constants values to exclude from validation */
    public $exclude = [];

    public $strict = true;

    public $range = [];

    /**
     * @param mixed $value
     * @return array|null
     * @throws base\NotSupportedException
     * @throws \ReflectionException
     */
    protected function validateValue($value): ?array
    {
        if (is_null($this->prefix)) {
            throw new base\NotSupportedException(
                static::class . ' does not support validateValue() without specifying range.'
            );
        }

        if (is_null($this->targetClass)) {
            throw new base\NotSupportedException(
                static::class . ' does not support validateValue() without specifying target class.'
            );
        }

        if (is_callable($this->filter)) {
            $value = call_user_func($this->filter, $value);
        }

        $this->range = (new ArrayObject($this->targetClass, $this->prefix, $this->except))
            ->getArrayCopy();

        return parent::validateValue($value);
    }

    public function validateAttribute($model, $attribute): void
    {
        if (is_null($this->prefix)) {
            $this->prefix = strtoupper($attribute) . '_';
            $prefix = true;
        }
        if (is_null($this->targetClass)) {
            $this->targetClass = get_class($model);
            $targetClass = true;
        }

        parent::validateAttribute($model, $attribute);

        if (!empty($prefix)) {
            $this->prefix = null;
        }
        if (!empty($targetClass)) {
            $this->targetClass = null;
        }
    }
}
