<?php
// src/Controller/ProductController.php
namespace App\Controller;

use App\DTO\AddToCartDTO;
use App\Form\AddToCartType;
use App\Service\Interface\CartServiceInterface;
use App\Service\Implementation\ProductDataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    public function __construct(
        private readonly ProductDataService $productDataService,
        private readonly CartServiceInterface $cartService
    ) {
    }

    #[Route('/product', name: 'app_product_show', methods: ['GET', 'POST'])]
    public function show(Request $request): Response
    {
        $product = $this->productDataService->getProductData();

        $addToCartDTO = new AddToCartDTO();
        $form = $this->createForm(AddToCartType::class, $addToCartDTO);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->cartService->addItem($addToCartDTO);

            $colorLabel = $this->productDataService->getColorLabel($addToCartDTO->getColor());

            $this->addFlash(
                'success',
                sprintf(
                    '%d x %s headphones added to cart!',
                    $addToCartDTO->getQuantity(),
                    $colorLabel
                )
            );

            return $this->redirectToRoute('app_product_show');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'form' => $form,
            'cartItemCount' => $this->cartService->getItemCount(),
        ]);
    }

    #[Route('/cart', name: 'app_cart_view', methods: ['GET'])]
    public function viewCart(): Response
    {
        $items = $this->cartService->getItems();
        $product = $this->productDataService->getProductData();

        return $this->render('cart/view.html.twig', [
            'items' => $items,
            'product' => $product,
        ]);
    }
}
