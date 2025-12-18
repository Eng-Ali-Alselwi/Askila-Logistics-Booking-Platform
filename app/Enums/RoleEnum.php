<?php

namespace App\Enums;

enum RoleEnum: string
{
    case SUPER_ADMIN = 'super_admin';
    case MANAGER     = 'manager';
    case BRANCH_MANAGER = 'branch_manager';
    case CUSTOMER_SERVICE = 'customer_service';
    case UPDATER     = 'updater';
    case SENDER      = 'sender';
    case VIEWER      = 'viewer';
}
