<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   Modules\Admin
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

namespace Modules\DatabaseEditor\Controller;

use Modules\Admin\Models\NullAccount;
use Modules\DatabaseEditor\Models\Query;
use Modules\DatabaseEditor\Models\QueryMapper;
use phpOMS\DataStorage\Database\Connection\ConnectionFactory;
use phpOMS\DataStorage\Database\Query\Builder;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Message\NotificationLevel;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Model\Message\FormValidation;

/**
 * Admin controller class.
 *
 * This class is responsible for the basic admin activities such as managing accounts, groups, permissions and modules.
 *
 * @package Modules\Admin
 * @license OMS License 1.0
 * @link    https://orange-management.org
 * @since   1.0.0
 */
final class ApiController extends Controller
{
    /**
     * Api method for modifying settings
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiQueryCreate(RequestAbstract $request, ResponseAbstract $response, $data = null) : void
    {
        if (!empty($val = $this->validateQueryCreate($request))) {
            $response->set($request->uri->__toString(), new FormValidation($val));
            $response->header->status = RequestStatusCode::R_400;

            return;
        }

        $query = $this->createQueryFromRequest($request);
        $this->createModel($request->header->account, $query, QueryMapper::class, 'query', $request->getOrigin());
        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Query', 'Query successfully created.', $query);
    }

    /**
     * Validate query create request
     *
     * @param RequestAbstract $request Request
     *
     * @return array<string, bool> Returns the validation array of the request
     *
     * @since 1.0.0
     */
    private function validateQueryCreate(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['title'] = empty($request->getData('title')))
            || ($val['type'] = empty($request->getData('type')))
            || ($val['host'] = empty($request->getData('host')))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Method to create query from request.
     *
     * @param RequestAbstract $request Request
     *
     * @return Query Returns the created query from the request
     *
     * @since 1.0.0
     */
    private function createQueryFromRequest(RequestAbstract $request) : Query
    {
        $query            = new Query();
        $query->title     = (string) ($request->getData('title') ?? '');
        $query->type      = (string) ($request->getData('type') ?? '');
        $query->host      = (string) ($request->getData('host') ?? '');
        $query->port      = (int) ($request->getData('port') ?? 0);
        $query->db        = (string) ($request->getData('db') ?? '');
        $query->query     = (string) ($request->getData('query') ?? '');
        $query->result    = (string) ($request->getData('result') ?? '');
        $query->createdBy = new NullAccount($request->header->account);

        return $query;
    }

    /**
     * Api method for modifying settings
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiQueryExecute(RequestAbstract $request, ResponseAbstract $response, $data = null) : void
    {
        /** @var array{db:string, host:string, port:int, login:string, password:string, database:string} $config */
        $config  = $this->createDbConfigFromRequest($request);
        $con     = ConnectionFactory::create($config);
        $builder = new Builder($con);
        $builder->raw($request->getData('query') ?? '');

        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Query', 'Query response', $builder->execute());
    }

    /**
     * Api method in order to test database connection
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiConnectionTest(RequestAbstract $request, ResponseAbstract $response, $data = null) : void
    {
        /** @var array{db:string, host:string, port:int, login:string, password:string, database:string} $config */
        $config = $this->createDbConfigFromRequest($request);
        $con    = ConnectionFactory::create($config);

        $this->fillJsonResponse($request, $response, NotificationLevel::OK, 'Query', 'Query response', $con->getStatus());
    }

    /**
     * Create database connection config from request data
     *
     * @param RequestAbstract $request Request
     *
     * @return array
     *
     * @since 1.0.0
     */
    private function createDbConfigFromRequest(RequestAbstract $request) : array
    {
        return [
            'db'       => $request->getData('type') ?? '',
            'host'     => $request->getData('host') ?? '',
            'port'     => $request->getData('port') ?? '',
            'database' => $request->getData('database') ?? '',
            'login'    => $request->getData('login') ?? '',
            'password' => $request->getData('password') ?? '',
        ];
    }
}
