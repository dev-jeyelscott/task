<?php

declare(strict_types=1);

namespace App\Domain\Task\Entities;

final class TaskPriority
{
    private const string LOW = 'low';
    private const string MEDIUM = 'medium';
    private const string HIGH = 'high';

    private string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public static function low(): self
    {
        return new self(self::LOW);
    }

    public static function medium(): self
    {
        return new self(self::MEDIUM);
    }

    public static function high(): self
    {
        return new self(self::HIGH);
    }

    public function canStransitionTo(self $next): bool
    {
        return match ($this->value) {
            self::LOW => $next->value() === self::MEDIUM,
            self::MEDIUM => $next->value() === self::HIGH,
            self::HIGH => false,
            default => false,
        };
    }
}
