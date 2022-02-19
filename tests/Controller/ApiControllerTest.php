<?php
/**
 * Karaka
 *
 * PHP Version 8.0
 *
 * @package   tests
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace Modules\DatabaseEditor\tests\Controller;

use Model\CoreSettings;
use Modules\Admin\Models\AccountPermission;
use phpOMS\Account\Account;
use phpOMS\Account\AccountManager;
use phpOMS\Account\PermissionType;
use phpOMS\Application\ApplicationAbstract;
use phpOMS\DataStorage\Database\DatabaseStatus;
use phpOMS\DataStorage\Database\DatabaseType;
use phpOMS\DataStorage\Session\HttpSession;
use phpOMS\Dispatcher\Dispatcher;
use phpOMS\Event\EventManager;
use phpOMS\Message\Http\HttpRequest;
use phpOMS\Message\Http\HttpResponse;
use phpOMS\Message\Http\RequestStatusCode;
use phpOMS\Module\ModuleAbstract;
use phpOMS\Module\ModuleManager;
use phpOMS\Router\WebRouter;
use phpOMS\Uri\HttpUri;
use phpOMS\Utils\TestUtils;

/**
 * @testdox Modules\DatabaseEditor\tests\Controller\ApiControllerTest: DatabaseEditor api controller
 *
 * @internal
 */
final class ApiControllerTest extends \PHPUnit\Framework\TestCase
{
    protected ApplicationAbstract $app;

    /**
     * @var \Modules\DatabaseEditor\Controller\ApiController
     */
    protected ModuleAbstract $module;

    /**
     * {@inheritdoc}
     */
    protected function setUp() : void
    {
        $this->app = new class() extends ApplicationAbstract
        {
            protected string $appName = 'Api';
        };

        $this->app->dbPool         = $GLOBALS['dbpool'];
        $this->app->orgId          = 1;
        $this->app->accountManager = new AccountManager($GLOBALS['session']);
        $this->app->appSettings    = new CoreSettings();
        $this->app->moduleManager  = new ModuleManager($this->app, __DIR__ . '/../../../../Modules/');
        $this->app->dispatcher     = new Dispatcher($this->app);
        $this->app->eventManager   = new EventManager($this->app->dispatcher);
        $this->app->eventManager->importFromFile(__DIR__ . '/../../../../Web/Api/Hooks.php');
        $this->app->sessionManager = new HttpSession(36000);

        $account = new Account();
        TestUtils::setMember($account, 'id', 1);

        $permission = new AccountPermission();
        $permission->setUnit(1);
        $permission->setApp('backend');
        $permission->setPermission(
            PermissionType::READ
            | PermissionType::CREATE
            | PermissionType::MODIFY
            | PermissionType::DELETE
            | PermissionType::PERMISSION
        );

        $account->addPermission($permission);

        $this->app->accountManager->add($account);
        $this->app->router = new WebRouter();

        $this->module = $this->app->moduleManager->get('DatabaseEditor');

        TestUtils::setMember($this->module, 'app', $this->app);
    }

    /**
     * @covers Modules\DatabaseEditor\Controller\ApiController
     * @group module
     */
    public function testApiQueryCreate() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('title', 'Title');
        $request->setData('type', DatabaseType::SQLITE);
        $request->setData('database', __DIR__ . '/../../../../phpOMS/Localization/Defaults/localization.sqlite');
        $request->setData('port', '');
        $request->setData('host', '');
        $request->setData('login', '');
        $request->setData('password', '');
        $request->setData('query', 'SELECT * FROM country;');
        $request->setData('result', "A;B;C;\nUS;USA;01\nDE;GER;49");

        $this->module->apiQueryCreate($request, $response);
        self::assertGreaterThan(0, $response->get('')['response']->getId());
    }

    /**
     * @covers Modules\DatabaseEditor\Controller\ApiController
     * @group module
     */
    public function testApiQueryCreateInvalidData() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('invalid', '1');

        $this->module->apiQueryCreate($request, $response);
        self::assertEquals(RequestStatusCode::R_400, $response->header->status);
    }

    /**
     * @covers Modules\DatabaseEditor\Controller\ApiController
     * @group module
     */
    public function testApiQueryConnection() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('type', DatabaseType::SQLITE);
        $request->setData('database', __DIR__ . '/../../../../phpOMS/Localization/Defaults/localization.sqlite');
        $request->setData('port', '');
        $request->setData('host', '');
        $request->setData('login', '');
        $request->setData('password', '');

        $this->module->apiConnectionTest($request, $response);
        self::assertEquals(DatabaseStatus::OK, $response->get('')['response']);
    }

    /**
     * @covers Modules\DatabaseEditor\Controller\ApiController
     * @group module
     */
    public function testApiQueryConnectionInvalidData() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('invalid', '1');

        $this->module->apiConnectionTest($request, $response);
        self::assertEquals(RequestStatusCode::R_400, $response->header->status);
    }

    /**
     * @covers Modules\DatabaseEditor\Controller\ApiController
     * @group module
     */
    public function testApiQueryConnectionInvalidConnection() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('invalid', '1');
        $request->setData('type', DatabaseType::SQLITE);
        $request->setData('database', __DIR__ . '/../../../../plization');

        $this->module->apiConnectionTest($request, $response);
        self::assertGreaterThan(0, $response->get('')['response']);
    }

    /**
     * @covers Modules\DatabaseEditor\Controller\ApiController
     * @group module
     */
    public function testApiQueryExecute() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('query', 'SELECT * FROM country;');
        $request->setData('type', DatabaseType::SQLITE);
        $request->setData('database', __DIR__ . '/../../../../phpOMS/Localization/Defaults/localization.sqlite');
        $request->setData('port', '');
        $request->setData('host', '');
        $request->setData('login', '');
        $request->setData('password', '');

        $this->module->apiQueryExecute($request, $response);
        self::assertGreaterThan(50, \count($response->get('')['response']));
    }

    /**
     * @covers Modules\DatabaseEditor\Controller\ApiController
     * @group module
     */
    public function testApiQueryExecuteInvalidData() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('invalid', '1');

        $this->module->apiQueryExecute($request, $response);
        self::assertEquals(RequestStatusCode::R_400, $response->header->status);
    }

    /**
     * @covers Modules\DatabaseEditor\Controller\ApiController
     * @group module
     */
    public function testApiQueryExecuteInvalidConnection() : void
    {
        $response = new HttpResponse();
        $request  = new HttpRequest(new HttpUri(''));

        $request->header->account = 1;
        $request->setData('invalid', '1');
        $request->setData('type', DatabaseType::SQLITE);
        $request->setData('database', __DIR__ . '/../../../../plization');

        $this->module->apiQueryExecute($request, $response);
        self::assertGreaterThan(0, $response->get('')->toArray()['validation']['status']);
    }
}
