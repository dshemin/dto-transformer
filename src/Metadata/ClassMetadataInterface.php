<?php
/**
 * ClassMetadataInterface.php
 *
 * @author  Shemin Dmitry <freedemster@yandex.ru>
 * @license https://opensource.org/licenses/MIT MIT
 * @since   0.0.1
 * @version 0.0.1
 */

namespace Freedemster\DTO\Metadata;

/**
 * Interface ClassMetadataInterface
 *
 * Interface for class metadata which contains necessary information for creating
 * DTO instance from class.
 *
 * @package Freedemster\DTO\Metadata
 */
interface ClassMetadataInterface
{

    /**
     * Original object full qualified class name.
     *
     * @return string full qualified class name.
     */
    public function getOriginalClass();

    /**
     * Return reflection class of original object.
     *
     * @return \ReflectionClass
     */
    public function getOriginalReflection();

    /**
     * Data Transfer Object full qualified class name.
     *
     * @return string full qualified class name.
     */
    public function getDTOClass();

    /**
     * Get all properties metadata's.
     *
     * @return PropertyMetadataInterface[]
     */
    public function getProperties();
}
