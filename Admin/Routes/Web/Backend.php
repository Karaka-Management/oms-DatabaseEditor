<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   Modules
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

use Modules\DatabaseEditor\Controller\BackendController;
use Modules\DatabaseEditor\Models\PermissionState;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^.*/dbeditor/list.*$' => [
        [
            'dest'       => '\Modules\DatabaseEditor\Controller\BackendController:viewDatabaseEditorList',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionState::DATABASE_EDITOR,
            ],
        ],
    ],
    '^.*/dbeditor/editor.*$' => [
        [
            'dest'       => '\Modules\DatabaseEditor\Controller\BackendController:viewDatabaseEditorEditor',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => BackendController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionState::DATABASE_EDITOR,
            ],
        ],
    ],
];
