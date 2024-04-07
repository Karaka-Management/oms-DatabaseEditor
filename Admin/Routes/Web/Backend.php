<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use Modules\DatabaseEditor\Controller\BackendController;
use Modules\DatabaseEditor\Models\PermissionCategory;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^/dbeditor/list(\?.*$|$)' => [
        [
            'dest'       => '\Modules\DatabaseEditor\Controller\BackendController:viewDatabaseEditorList',
            'verb'       => RouteVerb::GET,
            'active' => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::DATABASE_EDITOR,
            ],
        ],
    ],
    '^/dbeditor/editor(\?.*$|$)' => [
        [
            'dest'       => '\Modules\DatabaseEditor\Controller\BackendController:viewDatabaseEditorEditor',
            'verb'       => RouteVerb::GET,
            'active' => true,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::DATABASE_EDITOR,
            ],
        ],
    ],
];
