<?php

namespace App\Domain\Task\Entities;

final class TaskSeverity
{
    private const LOW = 'low';
    private const MEDIUM = 'medium';
    private const HIGH = 'high';
    private const CRITICAL = 'critical';

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

    public static function critical(): self
    {
        return new self(self::CRITICAL);
    }

    public function canTransitionTo(self $next): bool
    {
        return match ($this->value) {
            self::LOW => $next->value() === self::MEDIUM,
            self::MEDIUM => $next->value() === self::HIGH,
            self::HIGH => $next->value() === self::CRITICAL,
            self::CRITICAL => false,
            default => false,
        };
    }
}
