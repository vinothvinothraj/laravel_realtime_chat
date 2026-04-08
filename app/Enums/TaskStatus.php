<?php

namespace App\Enums;

enum TaskStatus: string
{
    case Todo = 'todo';
    case InProgress = 'in_progress';
    case Testing = 'testing';
    case Completed = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::Todo => 'To-Do',
            self::InProgress => 'In Progress',
            self::Testing => 'Testing',
            self::Completed => 'Completed',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Todo => 'Ready to be picked up.',
            self::InProgress => 'Actively being worked on.',
            self::Testing => 'Waiting for validation.',
            self::Completed => 'Finished and delivered.',
        };
    }

    public function colorClasses(): string
    {
        return match ($this) {
            self::Todo => 'from-sky-500 to-cyan-500',
            self::InProgress => 'from-amber-500 to-orange-500',
            self::Testing => 'from-violet-500 to-fuchsia-500',
            self::Completed => 'from-emerald-500 to-teal-500',
        };
    }

    public function tintClasses(): string
    {
        return match ($this) {
            self::Todo => 'border-sky-200 bg-sky-50 text-sky-700',
            self::InProgress => 'border-amber-200 bg-amber-50 text-amber-700',
            self::Testing => 'border-violet-200 bg-violet-50 text-violet-700',
            self::Completed => 'border-emerald-200 bg-emerald-50 text-emerald-700',
        };
    }

    public function chipClasses(): string
    {
        return match ($this) {
            self::Todo => 'bg-sky-600',
            self::InProgress => 'bg-amber-600',
            self::Testing => 'bg-violet-600',
            self::Completed => 'bg-emerald-600',
        };
    }

    /**
     * @return array<int, self>
     */
    public static function ordered(): array
    {
        return [
            self::Todo,
            self::InProgress,
            self::Testing,
            self::Completed,
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_map(static fn (self $status): string => $status->value, self::ordered());
    }
}
