<?php
namespace App\Utils;

class Constant
{
    const ACTIVITY_TYPE_SUBMIT_JOB = 6;
    const ACTIVITY_TYPE_PAYMENT = 7;
    const ACTIVITY_TYPE_ADD_CREDIT = 9;
    const ACTIVITY_TYPE_REGISTER_FAIL = 23;

    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    const USER_ROLE_SUPER_ADMIN = 1;
    const USER_ROLE_ADMIN = 2;
    const USER_ROLE_SUPPORTER = 3;

    const PREFIX_SESSION_TIMEZONE = 'timezone-';
}