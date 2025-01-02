<?php

namespace App\Enum;

enum TaskStatusEnum: string
{
    case TO_DO = 'To Do';
    case IN_PROGRESS = 'In Progress';
    case DONE = 'Done';


    public static function toArray(): array
    {
        return [
            self::TO_DO,
            self::IN_PROGRESS,
            self::DONE,
        ];
    }

    public static function toValues(): array
    {
        return [
            self::TO_DO->value,
            self::IN_PROGRESS->value,
            self::DONE->value,
        ];
    }}
