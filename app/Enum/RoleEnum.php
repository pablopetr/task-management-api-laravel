<?php

namespace App\Enum;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case USER = 'user';

    public static function toArray(): array
    {
        return [
            self::ADMIN,
            self::USER,
        ];
    }

    public static function toValues(): array
    {
        return [
            self::ADMIN->value,
            self::USER->value,
        ];
    }
}
