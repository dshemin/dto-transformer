<?php
/**
 * DTOClassMetadataException.php
 *
 * @author  Shemin Dmitry <freedemster@yandex.ru>
 * @license https://opensource.org/licenses/MIT MIT
 * @since   0.0.1
 * @version 0.0.1
 */

namespace Freedemster\DTO\Exceptions;

/**
 * Class DTOClassMetadataException
 * Data Transfer Object exception related to class metadata issues.
 *
 * @package Freedemster
 */
class DTOClassMetadataException extends DTOException
{

    /**
     * Rise then unknown full qualified class name provided.
     *
     * @param string $fqcn Not founded full qualified class name.
     *
     * @return DTOClassMetadataException
     */
    public static function unknownClass($fqcn)
    {
        return new DTOClassMetadataException(
            "Can't find class by '{$fqcn}' name."
        );
    }
}
