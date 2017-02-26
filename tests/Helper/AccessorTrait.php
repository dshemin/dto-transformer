<?php

namespace Freedemster\DTO\Tests\Helper;

/**
 * Class AccessorTrait
 * @package Freedemster\DTO\Tests\Helper
 */
trait AccessorTrait
{

    /**
     * Get property with any access level from specified object.
     *
     * @param object $object Some object.
     * @param string $name   Property name.
     *
     * @return mixed
     */
    public function get($object, $name)
    {
        $getter = function () use ($name) {
            return $this->{$name};
        };
        $getter = $getter->bindTo($object, $object);

        return $getter();
    }

    /**
     * Set property with any access level to specified object.
     *
     * @param object $object Some object.
     * @param string $name   Property name.
     * @param mixed  $value  New property value.
     *
     * @return void
     */
    public function set($object, $name, $value)
    {
        $setter = function () use ($name, $value) {
            $this->{$name} = $value;
        };
        $setter = $setter->bindTo($object, $object);

        $setter();
    }
}
