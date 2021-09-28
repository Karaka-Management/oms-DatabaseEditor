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

use Modules\DatabaseEditor\Controller\ApiController;
use Modules\DatabaseEditor\Models\PermissionState;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^.*/dbeditor/query.*$' => [
        [
            'dest'       => '\Modules\DatabaseEditor\Controller\ApiController:apiQueryExecute',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionState::DATABASE_EDITOR,
            ],
        ],
        [
            'dest'       => '\Modules\DatabaseEditor\Controller\ApiController:apiQueryCreate',
            'verb'       => RouteVerb::PUT,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionState::DATABASE_EDITOR,
            ],
        ],
    ],
    '^.*/dbeditor/connection.*$' => [
        [
            'dest'       => '\Modules\DatabaseEditor\Controller\ApiController:apiConnectionTest',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionState::DATABASE_EDITOR,
            ],
        ],
    ],
];
