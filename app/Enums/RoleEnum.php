<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case USER = 'user';
    case BANNED = 'banned';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function toArray(): array
    {
        return [
            self::ADMIN->value,
            self::USER->value,
            self::BANNED->value,
        ];
    }
}
