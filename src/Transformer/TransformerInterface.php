<?php
/**
 * TransformerInterface.php
 *
 * @author  Shemin Dmitry <freedemster@yandex.ru>
 * @license https://opensource.org/licenses/MIT MIT
 * @since   0.0.1
 * @version 0.0.1
 */

namespace Freedemster\DTO\Transformer;

use Freedemster\DTO\Exceptions\DTOTransformException;
use Freedemster\DTO\Metadata\ClassMetadataInterface;

/**
 * Interface TransformerInterface
 *
 * Transform object into Data Transfer Object and back.
 *
 * @package Freedemster\DTO\Transformer
 */
interface TransformerInterface
{

    /**
     * Transform object into Data Transfer Object.
     *
     * Crete DTO instance for specified object.
     *
     * @param object                 $object   Transformed Object.
     * @param ClassMetadataInterface $metadata A ClassMetadataInterface instance.
     *
     * @return object Data Transfer Object.
     *
     * @throws DTOTransformException Try to transform unsupported object.
     */
    public function objectToDTO($object, ClassMetadataInterface $metadata);

    /**
     * Transform Data Transfer Object into object.
     *
     * @param object                 $dto      Data Transfer Object.
     * @param ClassMetadataInterface $metadata A ClassMetadataInterface instance.
     *
     * @return object A original object.
     *
     * @throws DTOTransformException Try to transform unsupported DTO.
     */
    public function DTOToObject($dto, ClassMetadataInterface $metadata);
}
