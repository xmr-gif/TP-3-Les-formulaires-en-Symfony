<?php

namespace App\ValueObject;

class CartItem
{
    public function __construct(
        private string $color,
        private int $quantity,
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



    public function addQuantity(int $quantity): self
    {
        return new self(
            $this->color,
            $this->quantity + $quantity,
        );
    }

    public function toArray(): array
    {
        return [
            'color' => $this->color,
            'quantity' => $this->quantity,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['color'],
            $data['quantity'],
        );
    }
}
