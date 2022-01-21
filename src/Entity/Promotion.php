<?php

namespace App\Entity;

class Promotion
{
    /**
     * @var int
     */
    protected $minAmount;

    /**
     * @var int
     */
    protected $reduction;

    /**
     * @var bool
     */
    protected $freeDelivery;

    public function __construct(int $minAmount, int $reduction, bool $freeDelivery)
    {
        $this->minAmount = $minAmount;
        $this->reduction = $reduction;
        $this->freeDelivery = $freeDelivery;
    }

    public function getMinAmount(): int
    {
        return $this->minAmount;
    }

    public function getReduction(): int
    {
        return $this->reduction;
    }

    public function isFreeDelivery(): bool
    {
        return $this->freeDelivery;
    }
}
