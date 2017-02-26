<?php
/**
 * PropertyMetadataInterface.php
 *
 * @author  Shemin Dmitry <freedemster@yandex.ru>
 * @license https://opensource.org/licenses/MIT MIT
 * @since   0.0.1
 * @version 0.0.1
 */

namespace Freedemster\DTO\Metadata;

use Freedemster\DTO\Exceptions\DTOPropertyMetadataException;

/**
 * Interface PropertyMetadataInterface
 *
 * Interface for class property metadata which contains information about single
 * class property.
 *
 * @package Freedemster\DTO\Metadata
 */
interface PropertyMetadataInterface
{

    /**
     * Data Transfer Object property name.
     *
     * @return string
     */
    public function getDTOName();

    /**
     * Checks that this property can be read from original object.
     *
     * @return boolean
     */
    public function canRead();

    /**
     * Checks that this property can be write to original object.
     *
     * @return boolean
     */
    public function canWrite();

    /**
     * Function used for getting value from original object.
     *
     * This function called while transforming original object to Data Transfer
     * object.
     *
     * @param object $object Original object.
     *
     * @return mixed
     *
     * @throws DTOPropertyMetadataException Can't get value from object.
     */
    public function get($object);

    /**
     * Function used for setting value to original object.
     *
     * This function called while transforming Data Transfer object to original
     * object.
     *
     * @param object $object Original object.
     * @param mixed  $value  New property value.
     *
     * @return PropertyMetadataInterface
     *
     * @throws DTOPropertyMetadataException Can't set value to object.
     */
    public function set($object, $value);
}
