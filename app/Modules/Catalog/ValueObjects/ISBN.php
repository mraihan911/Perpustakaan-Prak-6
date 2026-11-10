<?php
namespace Modules\Catalog\ValueObjects;

use InvalidArgumentException;

final readonly class ISBN
{
    public string $value;
    public function __construct(string $value)
    {
        $normalized = strtoupper(preg_replace('/[\s-]+/', '', trim($value)) ?? '');
        if (!preg_match('/^(?:\d{9}[\dX]|\d{13})$/', $normalized)) {
            throw new InvalidArgumentException('ISBN harus berupa ISBN-10 atau ISBN-13 yang valid secara format.');
        }
        $this->value = $normalized;
    }
    public function __toString(): string { return $this->value; }
}
