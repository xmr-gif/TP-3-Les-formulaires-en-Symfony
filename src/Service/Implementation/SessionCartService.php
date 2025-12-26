<?php

namespace App\Service\Implementation;

use App\DTO\AddToCartDTO;
use App\Mapper\CartMapper;
use App\Service\Interface\CartServiceInterface;
use App\ValueObject\CartItem;
use Symfony\Component\HttpFoundation\RequestStack;

class SessionCartService implements CartServiceInterface
{
    private const CART_KEY = 'shopping_cart';

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly CartMapper $cartMapper
    ) {
    }

    public function addItem(AddToCartDTO $dto): void
    {
        $session = $this->requestStack->getSession();

        $cartData = $session->get(self::CART_KEY, []);

        $newItem = $this->cartMapper->dtoToCartItem($dto);
        $color = $newItem->getColor();

        if (isset($cartData[$color])) {
            $existingItem = CartItem::fromArray($cartData[$color]);
            $updatedItem = $existingItem->addQuantity($newItem->getQuantity());
            $cartData[$color] = $updatedItem->toArray();
        } else {
            $cartData[$color] = $newItem->toArray();
        }


        $session->set(self::CART_KEY, $cartData);
    }

    public function getItems(): array
    {
        $session = $this->requestStack->getSession();
        $cartData = $session->get(self::CART_KEY, []);


        return array_map(
            fn(array $itemData) => CartItem::fromArray($itemData),
            $cartData
        );
    }

    public function getItemCount(): int
    {
        $items = $this->getItems();
        return array_sum(
            array_map(fn(CartItem $item) => $item->getQuantity(), $items)
        );
    }

    public function clear(): void
    {
        $session = $this->requestStack->getSession();
        $session->remove(self::CART_KEY);
    }
}
