<?php

namespace App\Service\Interface;

use App\DTO\AddToCartDTO;

interface CartServiceInterface
{
    public function addItem(AddToCartDTO $dto): void ;
    public function getItems(): array;
    public function getItemCount(): int;
    public function clear(): void;

}
