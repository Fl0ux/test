<?php


namespace App\Entity;


class Item
{
    /**
     * @var Product
     */
    protected $product;

    /**
     * @var int
     */
    protected $quantity;

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function addQuantity(int $quantity): self
    {
        $this->quantity += $quantity;

        return $this;
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
