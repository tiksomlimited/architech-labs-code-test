<?php

namespace App\Basket;

class Offer
{
    private string $offerCode;
    private float $offerDiscount;

    public function __construct(string $offerCode, float $offerDiscount)
    {
        $this->offerCode = $offerCode;
        $this->offerDiscount = $offerDiscount;
    }

    public function applyOffer(string $productCode, int $count, float $price): float
    {
        if ($productCode !== $this->offerCode) {
            // No offer applies to this product
            return $price * $count;
        }

        // Calculate "Buy one, get one half price" offer
        $pairs = intdiv($count, 2);  // Number of eligible pairs
        $remainder = $count % 2;    // Remaining items not in a pair
        return ($pairs * ($price + $price / 2)) + ($remainder * $price);
    }
}
