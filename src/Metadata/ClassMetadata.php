<?php
/**
 * ClassMetadata.php
 *
 * @author  Shemin Dmitry <freedemster@yandex.ru>
 * @license https://opensource.org/licenses/MIT MIT
 * @since   0.0.1
 * @version 0.0.1
 */

namespace Freedemster\DTO\Metadata;

use Freedemster\DTO\Exceptions\DTOClassMetadataException;

/**
 * Class ClassMetadata
 *
 * Implementation of ClassMetadataInterface.
 *
 * @package Freedemster\DTO\Metadata
 */
class ClassMetadata implements ClassMetadataInterface
{

    /**
     * Original full qualified class name.
     *
     * @var string
     */
    private $originalClass;

    /**
     * Reflection of original object class.
     *
     * @var \ReflectionClass
     */
    private $originalReflection;

    /**
     * Data Transfer Object full qualified name.
     *
     * @var string
     */
    private $DTOClass;

    /**
     * Array of properties metadata.
     *
     * @var PropertyMetadataInterface[]
     */
    private $properties = [];

    /**
     * ClassMetadata constructor.
     *
     * @param string $originalClass Original object full qualified class name.
     * @param string $DTOClass      Data Transfer Object full qualified class name.
     *
     * @throws DTOClassMetadataException If can't find specified original class
     * or Data Transfer Object class.
     */
    public function __construct($originalClass, $DTOClass)
    {
        $this->setOriginalClass($originalClass);
        $this->setDTOClass($DTOClass);
    }

    /**
     * Named constructor for fluid interface.
     *
     * @param string $originalClass Original object full qualified class name.
     * @param string $DTOClass      Data Transfer Object full qualified class name.
     * @param array  $properties    Array of PropertyMetadataInterface's.
     *
     * @return ClassMetadata
     */
    public static function create($originalClass, $DTOClass, array $properties = [])
    {
        return new ClassMetadata($originalClass, $DTOClass, $properties);
    }

    /**
     * Set original object full qualified class name.
     *
     * @param string $fqcn Full qualified class name.
     *
     * @return ClassMetadata
     *
     * @throws DTOClassMetadataException If can't find specified class.
     */
    public function setOriginalClass($fqcn)
    {
        if (! class_exists($fqcn)) {
            throw DTOClassMetadataException::unknownClass($fqcn);
        }
        $this->originalReflection = null;
        $this->originalClass = $fqcn;

        return $this;
    }

    /**
     * Original object full qualified class name.
     *
     * @return string full qualified class name.
     */
    public function getOriginalClass()
    {
        return $this->originalClass;
    }

    /**
     * Return reflection class of original object.
     *
     * @return \ReflectionClass
     */
    public function getOriginalReflection()
    {
        if ($this->originalReflection === null) {
            $this->originalReflection = new \ReflectionClass(
                $this->originalClass
            );
        }

        return $this->originalReflection;
    }

    /**
     * Set Data Transfer Object full qualified class name.
     *
     * @param $fqcn
     *
     * @return ClassMetadata
     *
     * @throws DTOClassMetadataException If can't find specified class.
     */
    public function setDTOClass($fqcn)
    {
        if (! class_exists($fqcn)) {
            throw DTOClassMetadataException::unknownClass($fqcn);
        }
        $this->DTOClass = $fqcn;

        return $this;
    }

    /**
     * Data Transfer Object full qualified class name.
     *
     * @return string full qualified class name.
     */
    public function getDTOClass()
    {
        return $this->DTOClass;
    }

    /**
     * Add property metadata.
     *
     * @param string $originalName Property name in original object.
     * @param string $DTOName      Property name in Data Transfer Object. Use
     *                             original name if not provided.
     *
     * @return PropertyMetadata
     */
    public function addProperty($originalName, $DTOName = null)
    {
        $property = new PropertyMetadata($this, $originalName, $DTOName);
        $this->properties[] = $property;

        return $property;
    }

    /**
     * Get all properties metadata's.
     *
     * @return PropertyMetadataInterface[]
     */
    public function getProperties()
    {
        return $this->properties;
    }
}
