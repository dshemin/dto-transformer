<?php
/**
 * DTOTransformException.php
 *
 * @author  Shemin Dmitry <freedemster@yandex.ru>
 * @license https://opensource.org/licenses/MIT MIT
 * @since   0.0.1
 * @version 0.0.1
 */

namespace Freedemster\DTO\Exceptions;

/**
 * Class DTOTransformException
 * Data Transfer Object exception related to transformation issues.
 *
 * @package Freedemster
 */
class DTOTransformException extends DTOException
{

    /**
     * Rise then user try to transform not supported object.
     *
     * @param string $expected Expected full qualified class name.
     * @param string $actual   Actual full qualified class name.
     *
     * @return DTOTransformException
     */
    public static function unsupportedObject($expected, $actual)
    {
        return new DTOTransformException(
            "Can't transform '{$actual}' object, expects '{$expected}'."
        );
    }
}
