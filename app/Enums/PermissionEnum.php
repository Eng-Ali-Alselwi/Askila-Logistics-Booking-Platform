<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case MANAGE_USERS          = 'manage users';
    case ADD_SHIPMENT          = 'add shipment';
    case UPDATE_SHIPMENT       = 'update shipment status';
    case DELETE_SHIPMENT       = 'delete shipment';
    case MANAGE_BRANCH         = 'manage branch';
    case VIEW_BRANCH_REPORTS   = 'view branch reports';
    case MANAGE_FLIGHTS        = 'manage flights';
    case MANAGE_BOOKINGS       = 'manage bookings';
    case VIEW_REPORTS          = 'view reports';
    case MANAGE_CUSTOMERS      = 'manage customers';
    case MANAGE_SETTINGS       = 'manage settings';
}
