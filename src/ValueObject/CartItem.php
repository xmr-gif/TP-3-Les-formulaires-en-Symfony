<?php

namespace App\ValueObject;

class CartItem
{
    public function __construct(
        private string $color,
        private int $quantity,
        private \DateTimeImmutable $addedAt
    ) {
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getAddedAt(): \DateTimeImmutable
    {
        return $this->addedAt;
    }

    public function addQuantity(int $quantity): self
    {
        return new self(
            $this->color,
            $this->quantity + $quantity,
            $this->addedAt
        );
    }

    public function toArray(): array
    {
        return [
            'color' => $this->color,
            'quantity' => $this->quantity,
            'addedAt' => $this->addedAt->format('Y-m-d H:i:s'),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['color'],
            $data['quantity'],
            new \DateTimeImmutable($data['addedAt'])
        );
    }
}
