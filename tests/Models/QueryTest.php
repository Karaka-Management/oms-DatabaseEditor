<?php
/**
 * Karaka
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

use Modules\DatabaseEditor\Models\Query;
use phpOMS\DataStorage\Database\DatabaseType;

/**
 * @internal
 */
final class QueryTest extends \PHPUnit\Framework\TestCase
{
    private Query $query;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->query = new Query();
    }

    /**
     * @covers Modules\DatabaseEditor\Models\Query
     * @group module
     */
    public function testDefault() : void
    {
        self::assertEquals(0, $this->query->getId());
        self::assertEquals('', $this->query->title);
        self::assertEquals('', $this->query->type);
        self::assertEquals('', $this->query->host);
        self::assertEquals(0, $this->query->port);
        self::assertEquals('', $this->query->db);
        self::assertEquals('', $this->query->query);
        self::assertEquals('', $this->query->result);
        self::assertInstanceOf('\Modules\Admin\Models\NullAccount', $this->query->createdBy);
        self::assertInstanceOf('\DateTimeImmutable', $this->query->createdAt);
    }

    /**
     * @covers Modules\DatabaseEditor\Models\Query
     * @group module
     */
    public function testSerialize() : void
    {
        $this->query->title  = 'Test title';
        $this->query->type   = DatabaseType::SQLITE;
        $this->query->host   = '127.0.0.1';
        $this->query->port   = 2;
        $this->query->db     = 'test.sqlite';
        $this->query->query  = 'SELECT * FROM test';
        $this->query->result = 'Result';

        $result = $this->query->jsonSerialize();
        unset($result['createdAt']);

        self::assertEquals(
            [
                'id'     => 0,
                'title'  => 'Test title',
                'type'   => DatabaseType::SQLITE,
                'host'   => '127.0.0.1',
                'port'   => 2,
                'db'     => 'test.sqlite',
                'query'  => 'SELECT * FROM test',
                'result' => 'Result',
            ],
            $result
        );
    }
}
