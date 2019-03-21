<?php

declare(strict_types=1);

namespace Horat1us\Yii\ConstRange;

/**
 * Class ArrayObject
 * @package Horat1us\Yii\ConstRange
 */
class ArrayObject extends \ArrayObject
{
    /** @var array[] */
    private static $cache = [];

    /**
     * @throws \InvalidArgumentException
     */
    public function __construct(
        string $class,
        string $prefix = '',
        array $exclude = [],
        int $flags = 0,
        string $iterator_class = "ArrayIterator"
    ) {
        if (array_key_exists($class, static::$cache)) {
            $constants = static::$cache[$class];
        } else {
            $reflection = new \ReflectionClass($class);
            static::$cache[$class] = $constants = $reflection->getConstants();
        }

        $constants = array_diff($constants, $exclude);

        foreach ($constants as $name => $value) {
            if ($prefix !== '' && strpos($name, $prefix) !== 0) {
                unset($constants[$name]);
            }
        }

        parent::__construct($constants, $flags, $iterator_class);
    }
}
