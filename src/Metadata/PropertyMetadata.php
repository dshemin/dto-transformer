<?php
/**
 * PropertyMetadata.php
 *
 * @author  Shemin Dmitry <freedemster@yandex.ru>
 * @license https://opensource.org/licenses/MIT MIT
 * @since   0.0.1
 * @version 0.0.1
 */

namespace Freedemster\DTO\Metadata;

use Freedemster\DTO\Exceptions\DTOPropertyMetadataException;

/**
 * Class PropertyMetadata
 *
 * Implementation of PropertyMetadataInterface.
 *
 * @package Freedemster\DTO\Metadata
 */
class PropertyMetadata implements PropertyMetadataInterface
{

    /**
     * Property is used for reading and writing.
     *
     * With this access type property using in transformation from original to
     * Data Transfer Object and back.
     */
    const ACCESS_RW = 0;

    /**
     * Property is used only for reading.
     *
     * With this access type property using only in transformation from original
     * to Data Transfer Object.
     */
    const ACCESS_READ = 1;

    /**
     * Property is used only for writing.
     *
     * With this access type property using only in transformation from Data
     * Transfer Object to original.
     */
    const ACCESS_WRITE = 2;

    /**
     * Class metadata.
     *
     * @var ClassMetadataInterface
     */
    private $metadata;

    /**
     * Property name in Data Transfer Object.
     *
     * @var string
     */
    private $DTOName;

    /**
     * Access type to this property in original object.
     *
     * @var boolean
     */
    private $accessType;

    /**
     * Function used for getting value from original object.
     * By default using closure that just return value.
     *
     * @var \Closure
     */
    private $getter;

    /**
     * Function used for setting value to original object.
     * By default using closure that just set value.
     *
     * @var \Closure
     */
    private $setter;

    /**
     * PropertyMetadata constructor.
     *
     * Specified originalName will be used for getting/setting property from/to
     * original object.
     *
     * @param ClassMetadataInterface $metadata     A ClassMetadataInterface
     *                                             instance.
     * @param string                 $originalName Property name in original
     *                                             object.
     * @param string                 $DTOName      Property name in Data Transfer
     *                                             Object. Use original name if
     *                                             not provided.
     */
    public function __construct(
        ClassMetadataInterface $metadata,
        $originalName,
        $DTOName = null
    ) {
        if ($DTOName === null) {
            $DTOName = $originalName;
        }
        $this->metadata = $metadata;
        $this->DTOName = $DTOName;

        // Initialize default getter and setter.
        // By default we assume that use create property metadata for actual
        // property.
        $this->setGetter($originalName);
        $this->setSetter($originalName);
    }

    /**
     * Named constructor for fluid interface.
     *
     * @param ClassMetadataInterface $metadata     A ClassMetadataInterface
     *                                             instance.
     * @param string                 $originalName Property name in original
     *                                             object.
     * @param string                 $DTOName      Property name in Data Transfer
     *                                             Object. Use original name if
     *                                             not provided.
     *
     * @return PropertyMetadata
     */
    public static function create(
        ClassMetadataInterface $metadata,
        $originalName,
        $DTOName = null
    ) {
        return new PropertyMetadata($metadata, $originalName, $DTOName);
    }

    /**
     * Set Data Transfer Object property name.
     *
     * @param string $name Data Transfer Object property name.
     *
     * @return PropertyMetadata
     */
    public function setDTOName($name)
    {
        $this->DTOName = $name;

        return $this;
    }

    /**
     * Data Transfer Object property name.
     *
     * @return string
     */
    public function getDTOName()
    {
        return $this->DTOName;
    }

    /**
     * Set property access type.
     *
     * @param integer $accessType One of PropertyMetadata ACCESS_* const.
     *
     * @return PropertyMetadata
     *
     * @throws DTOPropertyMetadataException Invalid access type, must be one of:
     * ACCESS_RW, ACCESS_READ or ACCESS_WRITE
     */
    public function setAccessType($accessType)
    {
        $available = [
            self::ACCESS_RW,
            self::ACCESS_READ,
            self::ACCESS_WRITE,
        ];

        if (! in_array($accessType, $available, true)) {
            throw DTOPropertyMetadataException::invalidAccessType();
        }

        $this->accessType = $accessType;

        return $this;
    }

    /**
     * Checks that this property can be read from original object.
     *
     * @return boolean
     */
    public function canRead()
    {
        return $this->accessType !== self::ACCESS_WRITE;
    }

    /**
     * Checks that this property can be write to original object.
     *
     * @return boolean
     */
    public function canWrite()
    {
        return $this->accessType !== self::ACCESS_READ;
    }

    /**
     * Set property getter function.
     *
     * @param string $name Method name used for getting value from original
     *                     object or property name itself.
     *
     * @return PropertyMetadata
     */
    public function setGetter($name)
    {
        if (! is_string($name)) {
            throw DTOPropertyMetadataException::invalidPropertyOrMethod();
        }

        $reflection = $this->metadata->getOriginalReflection();

        if ($reflection->hasProperty($name)) {
            // Class got property with specified name, so we create closure for
            // getting this property.
            $this->getter = function () use ($name) {
                return $this->{$name};
            };

            return $this;
        } elseif ($reflection->hasMethod($name)) {
            // Class got method with specified name, so we create closure for
            // calling this method which return property value.
            $this->getter = function () use ($name) {
                return call_user_func([ $this, $name ]);
            };

            return $this;
        }

        throw DTOPropertyMetadataException::unknownPropertyOrMethod($name);
    }

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
    public function get($object)
    {
        if (! $this->canRead()) {
            // Try to get property which has write only access.
            throw DTOPropertyMetadataException::writeonly();
        }

        $getter = $this->getter->bindTo($object, $object);

        return $getter();
    }

    /**
     * Set property setter function.
     *
     * @param string $name Method name used for setting value from original
     *                     object or property name itself.
     *
     * @return PropertyMetadata
     */
    public function setSetter($name)
    {
        if (! is_string($name)) {
            throw DTOPropertyMetadataException::invalidPropertyOrMethod();
        }

        $reflection = $this->metadata->getOriginalReflection();

        if ($reflection->hasProperty($name)) {
            // Class got property with specified name, so we create closure for
            // setting this property. Value will pass in 'set' method.
            $this->setter = function ($value) use ($name) {
                return $this->{$name} = $value;
            };

            return $this;
        } elseif ($reflection->hasMethod($name)) {
            // Class got method with specified name, so we create closure for
            // calling this method which return property value. Value will pass
            // in 'set' method.
            $this->setter = function ($value) use ($name) {
                return call_user_func([ $this, $name ], $value);
            };

            return $this;
        }

        throw DTOPropertyMetadataException::unknownPropertyOrMethod($name);
    }

    /**
     * Function used for setting value to original object.
     *
     * This function called while transforming Data Transfer object to original
     * object.
     *
     * @param object $object Original object.
     * @param mixed $value New property value.
     *
     * @return PropertyMetadataInterface
     *
     * @throws DTOPropertyMetadataException Can't set value to object.
     */
    public function set($object, $value)
    {
        if (! $this->canWrite()) {
            // Try to get property which has readonly access.
            throw DTOPropertyMetadataException::readonly();
        }

        $setter = $this->setter->bindTo($object, $object);
        $setter($value);

        return $this;
    }
}
