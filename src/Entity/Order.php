<?php

namespace App\Entity;

use App\Service\VAT;

class Order
{
    /**
     * @var array
     */
    protected $items = [];

    /**
     * @var array
     */
    private $promotions = [];

    /**
     * @var Country
     */
    private $country;

    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(Product $product, int $quantity = 1): self
    {
        $productKey = $this->getProductKey($product);

        if ($productKey) {
            $this->items[$productKey]->addQuantity($quantity);
        } else {
            $this->items[] = (new Item())
                ->setProduct($product)
                ->addQuantity($quantity);
        }

        return $this;
    }

    public function getTotalPriceHT(): float
    {
        $total = 0;

        /** @var Item $item */
        foreach ($this->items as $item) {
            $total += $item->getProduct()->getPrice() * $item->getQuantity();
        }

        return $total;
    }

    public function getTotalPriceTTC(): float
    {
        return $this->getTotalPriceHT() + $this->getVAT() + $this->getShippingCosts() - $this->calculatePromotions();
    }

    public function getVAT(): float
    {
        $tva = 0;

        /** @var Item $item */
        foreach ($this->items as $item) {
            /** @todo Change to countriesRules when ready */
            $percentage = VAT::brandsRules($item->getProduct()->getBrand());

            $tva += ($percentage * $item->getProduct()->getPrice() / 100) * $item->getQuantity();
        }

        return $tva;
    }

    public function getShippingCosts(): float
    {
        $costs = 0;
        $brands = [];

        if ($this->hasFreeShippingCosts()) {
            return 0;
        }

        /** @var Item $item */
        foreach ($this->items as $item) {
            $brand = $item->getProduct()->getBrand();

            if (! isset($brands[$brand])) {
                $brands[$brand] = $item->getQuantity();
            } else {
                $brands[$brand] += $item->getQuantity();
            }
        }

        foreach ($brands as $brand => $quantity) {
            switch ($brand) {
                case 'Farmitoo':
                    $costs += ceil($quantity / 3) * 20;
                    break;
                case 'Gallagher':
                    $costs += 15;
                    break;
            }
        }

        return $costs;
    }

    public function addPromotion(Promotion $promotion): self
    {
        $this->promotions[] = $promotion;

        return $this;
    }

    public function calculatePromotions(): float
    {
        $reduction = 0;
        $totalHT = $this->getTotalPriceHT();

        if (! $this->hasPromotion()) {
            return 0;
        }

        /** @var Promotion $promotion */
        foreach ($this->promotions as $promotion) {
            if ($promotion->getMinAmount() <= $totalHT) {
                $reduction += $promotion->getReduction() * $totalHT / 100;
            }
        }

        return $reduction;
    }

    public function hasPromotion(): bool
    {
        return count($this->promotions);
    }

    public function hasFreeShippingCosts(): bool
    {
        if (! $this->hasPromotion()) {
            return false;
        }

        /** @var Promotion $promotion */
        foreach ($this->promotions as $promotion) {
            if ($promotion->isFreeDelivery()) {
                return true;
            }
        }

        return false;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(Country $country): void
    {
        $this->country = $country;
    }

    private function getProductKey(Product $product): ?int
    {
        /** @var Item $item */
        foreach ($this->items as $key => $item) {
            if ($item->getProduct()->getTitle() === $product->getTitle()) {
                return $key;
            }
        }

        return null;
    }
}
