<?php
/**
 * Jingga
 *
 * PHP Version 8.1
 *
 * @package   Modules\DatabaseEditor\Models
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\DatabaseEditor\Models;

use Modules\Admin\Models\Account;
use Modules\Admin\Models\NullAccount;

/**
 * Query.
 *
 * @package Modules\DatabaseEditor\Models
 * @license OMS License 2.0
 * @link    https://jingga.app
 * @since   1.0.0
 */
class Query
{
    /**
     * Query ID.
     *
     * @var int
     * @since 1.0.0
     */
    public int $id = 0;

    /**
     * Title
     *
     * @var string
     * @since 1.0.0
     */
    public string $title = '';

    /**
     * Type
     *
     * @var string
     * @since 1.0.0
     */
    public string $type = '';

    /**
     * Host
     *
     * @var string
     * @since 1.0.0
     */
    public string $host = '';

    /**
     * Port
     *
     * @var int
     * @since 1.0.0
     */
    public int $port = 0;

    /**
     * Db
     *
     * @var string
     * @since 1.0.0
     */
    public string $db = '';

    /**
     * Query
     *
     * @var string
     * @since 1.0.0
     */
    public string $query = '';

    /**
     * Result
     *
     * @var string
     * @since 1.0.0
     */
    public string $result = '';

    /**
     * Creator.
     *
     * @var Account
     * @since 1.0.0
     */
    public Account $createdBy;

    /**
     * Created.
     *
     * @var \DateTimeImmutable
     * @since 1.0.0
     */
    public \DateTimeImmutable $createdAt;

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        $this->createdBy = new NullAccount();
        $this->createdAt = new \DateTimeImmutable('now');
    }

    /**
     * {@inheritdoc}
     */
    public function toArray() : array
    {
        return [
            'id'        => $this->id,
            'title'     => $this->title,
            'type'      => $this->type,
            'host'      => $this->host,
            'port'      => $this->port,
            'db'        => $this->db,
            'query'     => $this->query,
            'result'    => $this->result,
            'createdAt' => $this->createdAt,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize() : mixed
    {
        return $this->toArray();
    }
}
