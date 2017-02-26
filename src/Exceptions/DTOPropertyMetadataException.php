<?php
/**
 * DTOPropertyMetadataException.php
 *
 * @author  Shemin Dmitry <freedemster@yandex.ru>
 * @license https://opensource.org/licenses/MIT MIT
 * @since   0.0.1
 * @version 0.0.1
 */

namespace Freedemster\DTO\Exceptions;

/**
 * Class DTOPropertyMetadataException
 * Data Transfer Object exception related to property metadata issues.
 *
 * @package Freedemster
 */
class DTOPropertyMetadataException extends DTOException
{

    /**
     * Rise than user try to set invalid access type.
     *
     * @return DTOPropertyMetadataException
     */
    public static function invalidAccessType()
    {
        return new DTOPropertyMetadataException(
            'Invalid access type, expects one of: ACCESS_RW, ACCESS_READ or ACCESS_WRITE.'
        );
    }

    /**
     * Rise than user try to set invalid property or method name.
     *
     * @return DTOPropertyMetadataException
     */
    public static function invalidPropertyOrMethod()
    {
        return new DTOPropertyMetadataException(
            'Expected string which represent property or method name.'
        );
    }

    /**
     * Rise than user try to set invalid callback.
     *
     * @param string $name Property or method name.
     *
     * @return DTOPropertyMetadataException
     */
    public static function unknownPropertyOrMethod($name)
    {
        return new DTOPropertyMetadataException(
            "Can't find property or method with name '{$name}'."
        );
    }

    /**
     * Rise then user try to get value of property with write only access.
     *
     * @return DTOPropertyMetadataException
     */
    public static function writeonly()
    {
        return new DTOPropertyMetadataException(
            'This property has only write access.'
        );
    }

    /**
     * Rise then user try to set value of property with readonly access.
     *
     * @return DTOPropertyMetadataException
     */
    public static function readonly()
    {
        return new DTOPropertyMetadataException(
            'This property has only read access.'
        );
    }

    /**
     * Rise than user try to get property.
     *
     * @param \Exception $cause Cause of fails.
     *
     * @return DTOPropertyMetadataException
     */
    public static function canNotGet(\Exception $cause)
    {
        return new DTOPropertyMetadataException(
            "Can't get property because of: {$cause->getMessage()}",
            $cause->getCode(),
            $cause
        );
    }

    /**
     * Rise than user try to set property.
     *
     * @param \Exception $cause Cause of fails.
     *
     * @return DTOPropertyMetadataException
     */
    public static function canNotSet(\Exception $cause)
    {
        return new DTOPropertyMetadataException(
            "Can't set property because of: {$cause->getMessage()}",
            $cause->getCode(),
            $cause
        );
    }
}
