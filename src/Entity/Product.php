<?php

namespace App\Entity;

class Product
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var int
     */
    protected $price;

    /**
     * @var string
     */
    protected $brand;

    public function __construct(string $title, float $price, string $brand)
    {
        $this->title = $title;
        $this->price = $price;
        $this->brand = $brand;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}
