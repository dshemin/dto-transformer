<?php
/**
 * Transformer.php
 *
 * @author  Shemin Dmitry <freedemster@yandex.ru>
 * @license https://opensource.org/licenses/MIT MIT
 * @since   0.0.1
 * @version 0.0.1
 */

namespace Freedemster\DTO\Transformer;

use Freedemster\DTO\Exceptions\DTOTransformException;
use Freedemster\DTO\Metadata\ClassMetadataInterface;
use Freedemster\DTO\Metadata\PropertyMetadataInterface;

/**
 * Class Transformer
 *
 * Implementation of TransformerInterface.
 *
 * @package Freedemster\DTO\Transformer
 */
class Transformer implements TransformerInterface
{

    /**
     * Transform object into Data Transfer Object.
     *
     * Crete DTO instance for specified object.
     *
     * @param object $object Transformed Object.
     * @param ClassMetadataInterface $metadata A ClassMetadataInterface instance.
     *
     * @return object
     *
     * @throws DTOTransformException Try to transform unsupported object.
     */
    public function objectToDTO($object, ClassMetadataInterface $metadata)
    {
        // Check specified object class name and supported class name from
        // metadata.
        $actual = get_class($object);
        $expected = $metadata->getOriginalClass();
        if ($actual !== $expected) {
            throw DTOTransformException::unsupportedObject($expected, $actual);
        }

        // Create Data Transfer Object for specified mapping.
        $dtoClass = $metadata->getDTOClass();
        $dto = new $dtoClass();

        // Process all properties and set dto fields.
        $filterFn = function (PropertyMetadataInterface $property) {
            return $property->canRead();
        };
        /** @var PropertyMetadataInterface[] $properties */
        $properties = array_filter($metadata->getProperties(), $filterFn);

        foreach ($properties as $property) {
            $dto->{$property->getDTOName()} = $property->get($object);
        }

        return $dto;
    }

    /**
     * Transform Data Transfer Object into object.
     *
     * @param object $dto Data Transfer Object.
     * @param ClassMetadataInterface $metadata A ClassMetadataInterface instance.
     *
     * @return object A original object.
     *
     * @throws DTOTransformException Try to transform unsupported DTO.
     */
    public function DTOToObject($dto, ClassMetadataInterface $metadata)
    {
        // Check specified Data Transfer Object class name and supported class
        // name from metadata.
        $actual = get_class($dto);
        $expected = $metadata->getDTOClass();
        if ($actual !== $expected) {
            throw DTOTransformException::unsupportedObject($expected, $actual);
        }

        // Create original object for specified cass metadata.
        $object = $metadata->getOriginalReflection()
            ->newInstanceWithoutConstructor();

        $filterFn = function (PropertyMetadataInterface $property) {
            return $property->canWrite();
        };
        /** @var PropertyMetadataInterface[] $properties */
        $properties = array_filter($metadata->getProperties(), $filterFn);

        foreach ($properties as $property) {
            $property->set($object, $dto->{$property->getDTOName()});
        }

        return $object;
    }
}
