<?php
// src/Service/ProductDataService.php
namespace App\Service\Implementation;

class ProductDataService
{
    public function getProductData(): array
    {
        return [
            'name' => 'Premium Wireless Headphones',
            'price' => 129.99,
            'description' => 'Experience superior sound quality with our premium wireless headphones. Features active noise cancellation, 30-hour battery life, and premium comfort padding.',
            'imageUrl' => 'https://images.pexels.com/photos/90946/pexels-photo-90946.jpeg?auto=compress&cs=tinysrgb&w=800',
            'brand' => 'AudioTech',
            'connectivity' => 'Bluetooth 5.0',
            'batteryLife' => '30 hours',
            'colors' => [
                'black' => 'Matte Black',
                'white' => 'Pearl White',
                'silver' => 'Silver'
            ]
        ];
    }

    public function getColorLabel(string $colorKey): string
    {
        $colors = $this->getProductData()['colors'];
        return $colors[$colorKey] ?? $colorKey;
    }
}
