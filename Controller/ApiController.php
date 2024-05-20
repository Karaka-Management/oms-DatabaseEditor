<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\Admin
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace Modules\DatabaseEditor\Controller;

use Modules\Admin\Models\NullAccount;
use Modules\DatabaseEditor\Models\Query;
use Modules\DatabaseEditor\Models\QueryMapper;
use phpOMS\DataStorage\Database\Connection\ConnectionFactory;
use phpOMS\DataStorage\Database\DatabaseStatus;
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
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 *
 * @todo Implement basic functionality / queries in UI
 *      https://github.com/Karaka-Management/oms-DatabaseEditor/issues/2
 */
final class ApiController extends Controller
{
    /**
     * Api method for modifying settings
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiQueryCreate(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateQueryCreate($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        $query = $this->createQueryFromRequest($request);
        $this->createModel($request->header->account, $query, QueryMapper::class, 'query', $request->getOrigin());
        $this->createStandardCreateResponse($request, $response, $query);
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
        if (($val['title'] = !$request->hasData('title'))
            || ($val['type'] = !$request->hasData('type'))
            || ($val['database'] = !$request->hasData('database'))
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
        $query->title     = $request->getDataString('title') ?? '';
        $query->type      = $request->getDataString('type') ?? '';
        $query->host      = $request->getDataString('host') ?? '';
        $query->port      = $request->getDataInt('port') ?? 0;
        $query->db        = $request->getDataString('database') ?? '';
        $query->query     = $request->getDataString('query') ?? '';
        $query->result    = $request->getDataString('result') ?? '';
        $query->createdBy = new NullAccount($request->header->account);

        return $query;
    }

    /**
     * Api method for modifying settings
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiQueryExecute(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateDatabaseConnection($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        /** @var array{db:string, host:string, port:int, login:string, password:string, database:string} $config */
        $config = $this->createDbConfigFromRequest($request);
        $con    = ConnectionFactory::create($config);
        $con->connect();

        if ($con->getStatus() !== DatabaseStatus::OK) {
            $response->set($request->uri->__toString(), new FormValidation(['status' => $con->getStatus()]));
            $response->header->status = RequestStatusCode::R_400;

            return;
        }

        $builder = new Builder($con);
        $builder->raw($request->getDataString('query') ?? '');

        $this->createStandardReturnResponse($request, $response, $builder->execute()?->fetchAll());
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
    private function validateDatabaseConnection(RequestAbstract $request) : array
    {
        $val = [];
        if (($val['type'] = !$request->hasData('type'))
            || ($val['database'] = !$request->hasData('database'))
        ) {
            return $val;
        }

        return [];
    }

    /**
     * Api method in order to test database connection
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param array            $data     Generic data
     *
     * @return void
     *
     * @api
     *
     * @since 1.0.0
     */
    public function apiConnectionTest(RequestAbstract $request, ResponseAbstract $response, array $data = []) : void
    {
        if (!empty($val = $this->validateDatabaseConnection($request))) {
            $response->header->status = RequestStatusCode::R_400;
            $this->createInvalidCreateResponse($request, $response, $val);

            return;
        }

        /** @var array{db:string, host:string, port:int, login:string, password:string, database:string} $config */
        $config = $this->createDbConfigFromRequest($request);
        $con    = ConnectionFactory::create($config);
        $con->connect();

        $isOk = $con->getStatus() === DatabaseStatus::OK;
        $this->fillJsonResponse($request, $response, $isOk ? NotificationLevel::OK : NotificationLevel::ERROR, 'Query', 'Query response', $con->getStatus());
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
            'db'       => $request->getDataString('type') ?? '',
            'host'     => $request->getDataString('host') ?? '',
            'port'     => $request->getDataString('port') ?? '',
            'database' => $request->getDataString('database') ?? '',
            'login'    => $request->getDataString('user') ?? '',
            'password' => $request->getDataString('password') ?? '',
        ];
    }
}
