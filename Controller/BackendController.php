<?php
/**
 * Karaka
 *
 * PHP Version 8.1
 *
 * @package   Modules\DatabaseEditor
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://karaka.app
 */
declare(strict_types=1);

namespace Modules\DatabaseEditor\Controller;

use Modules\DatabaseEditor\Models\QueryMapper;
use phpOMS\Contract\RenderableInterface;
use phpOMS\Message\RequestAbstract;
use phpOMS\Message\ResponseAbstract;
use phpOMS\Views\View;

/**
 * Database controller class.
 *
 * @package Modules\DatabaseEditor
 * @license OMS License 1.0
 * @link    https://karaka.app
 * @since   1.0.0
 * @codeCoverageIgnore
 */
final class BackendController extends Controller
{
    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewDatabaseEditorEditor(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/DatabaseEditor/Theme/Backend/database-editor');
        $view->addData('nav', $this->app->moduleManager->get('Navigation')->createNavigationMid(1007401001, $request, $response));

        $query = QueryMapper::get()->where('id', (int) ($request->getData('id') ?? 0))->execute();
        $view->addData('query', $query);

        return $view;
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewDatabaseEditorCreate(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/DatabaseEditor/Theme/Backend/database-editor');
        $view->addData('nav', $this->app->moduleManager->get('Navigation')->createNavigationMid(1007401001, $request, $response));

        return $view;
    }

    /**
     * Routing end-point for application behaviour.
     *
     * @param RequestAbstract  $request  Request
     * @param ResponseAbstract $response Response
     * @param mixed            $data     Generic data
     *
     * @return RenderableInterface
     *
     * @since 1.0.0
     * @codeCoverageIgnore
     */
    public function viewDatabaseEditorList(RequestAbstract $request, ResponseAbstract $response, mixed $data = null) : RenderableInterface
    {
        $view = new View($this->app->l11nManager, $request, $response);
        $view->setTemplate('/Modules/DatabaseEditor/Theme/Backend/database-list');
        $view->addData('nav', $this->app->moduleManager->get('Navigation')->createNavigationMid(1007401001, $request, $response));

        $queries = QueryMapper::getAll()->execute();
        $view->addData('queries', $queries);

        return $view;
    }
}
