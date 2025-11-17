<?php

namespace App\Enums;

enum TagEnum: string
{
    case PROGRAMMING = 'Programming';
    case WEB_DEV = 'Web Development';
    case MOBILE = 'Mobile';
    case DATABASE = 'Database';
    case DESIGN = 'Design';
    case DEVOPS = 'DevOps';
    case AI = 'Artificial Intelligence';
    case SECURITY = 'Security';
    case GAMING = 'Gaming';
    case BUSINESS = 'Business';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function toArray(): array
    {
        return [
            self::PROGRAMMING->value,
            self::WEB_DEV->value,
            self::MOBILE->value,
            self::DATABASE->value,
            self::DESIGN->value,
            self::DEVOPS->value,
            self::AI->value,
            self::SECURITY->value,
            self::GAMING->value,
            self::BUSINESS->value,
        ];
    }
}
