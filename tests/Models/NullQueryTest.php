<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace Modules\DatabaseEditor\tests\Models;

use Modules\DatabaseEditor\Models\NullQuery;

/**
 * @internal
 */
final class NullQueryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @covers Modules\DatabaseEditor\Models\NullQuery
     * @group framework
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\Modules\DatabaseEditor\Models\Query', new NullQuery());
    }

    /**
     * @covers Modules\DatabaseEditor\Models\NullQuery
     * @group framework
     */
    public function testId() : void
    {
        $null = new NullQuery(2);
        self::assertEquals(2, $null->getId());
    }
}