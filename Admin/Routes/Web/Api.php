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

use Modules\DatabaseEditor\Controller\ApiController;
use Modules\DatabaseEditor\Models\PermissionCategory;
use phpOMS\Account\PermissionType;
use phpOMS\Router\RouteVerb;

return [
    '^.*/dbeditor/query(\?.*$|$)' => [
        [
            'dest'       => '\Modules\DatabaseEditor\Controller\ApiController:apiQueryExecute',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::DATABASE_EDITOR,
            ],
        ],
        [
            'dest'       => '\Modules\DatabaseEditor\Controller\ApiController:apiQueryCreate',
            'verb'       => RouteVerb::PUT,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::DATABASE_EDITOR,
            ],
        ],
    ],
    '^.*/dbeditor/connection(\?.*$|$)' => [
        [
            'dest'       => '\Modules\DatabaseEditor\Controller\ApiController:apiConnectionTest',
            'verb'       => RouteVerb::GET,
            'permission' => [
                'module' => ApiController::NAME,
                'type'   => PermissionType::READ,
                'state'  => PermissionCategory::DATABASE_EDITOR,
            ],
        ],
    ],
];
