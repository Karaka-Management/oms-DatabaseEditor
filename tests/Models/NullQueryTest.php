<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
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
     * @group module
     */
    public function testNull() : void
    {
        self::assertInstanceOf('\Modules\DatabaseEditor\Models\Query', new NullQuery());
    }

    /**
     * @covers Modules\DatabaseEditor\Models\NullQuery
     * @group module
     */
    public function testId() : void
    {
        $null = new NullQuery(2);
        self::assertEquals(2, $null->id);
    }

    /**
     * @covers Modules\DatabaseEditor\Models\NullQuery
     * @group module
     */
    public function testJsonSerialize() : void
    {
        $null = new NullQuery(2);
        self::assertEquals(['id' => 2], $null->jsonSerialize());
    }
}
