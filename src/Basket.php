<?php

namespace App\Basket;

class Basket
{
    private array $productCatalogue;
    private array $basket = [];
    private Offer $offer;
    private DeliveryCharges $deliveryCharges;

    public function __construct(Database $db, Offer $offer, DeliveryCharges $deliveryCharges)
    {
        $this->productCatalogue = $db->getProducts();
        $this->offer = $offer;
        $this->deliveryCharges = $deliveryCharges;
    }

    public function add(string $productCode): void
    {
        if (!isset($this->productCatalogue[$productCode])) {
            throw new \InvalidArgumentException("Invalid product code: $productCode");
        }
        $this->basket[] = $productCode;
    }

    public function total(): mixed
    {
        $subtotal = $this->calculateSubtotal();
        $deliveryCharge = $this->deliveryCharges->getDeliveryCharge($subtotal);
        return [$subtotal , $deliveryCharge, round($subtotal + $deliveryCharge, 2)];
    }

    private function calculateSubtotal(): float
    {
        $productCounts = array_count_values($this->basket);
        $total = 0;

        foreach ($productCounts as $code => $count) {
            $price = $this->productCatalogue[$code]->price;
            $total += $this->offer->applyOffer($code, $count, $price);
        }

        return $total;
    }
}
