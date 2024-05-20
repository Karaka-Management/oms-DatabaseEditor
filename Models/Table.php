<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\DatabaseEditor\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\DatabaseEditor\Models;

/**
 * Table.
 *
 * @package Modules\DatabaseEditor\Models
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Table
{
    public string $name = '';

    public array $fields = [];
}
