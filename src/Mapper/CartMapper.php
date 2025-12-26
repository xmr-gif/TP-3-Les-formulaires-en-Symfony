<?php

namespace App\Mapper;

use App\DTO\AddToCartDTO;
use App\ValueObject\CartItem;

class CartMapper
{
    public function dtoToCartItem(AddToCartDTO $dto): CartItem
    {
        return new CartItem(
            color: $dto->getColor(),
            quantity: $dto->getQuantity(),

        );
    }
}
