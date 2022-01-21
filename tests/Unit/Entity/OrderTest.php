<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\Promotion;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    private const PRICE_HT = 250000 + 5000 * 3 + 1000 * 5;
    private const REDUCTION = 8 * (250000 + 5000 * 3 + 1000 * 5) / 100;
    private const SHIPPING_COSTS = 40 + 15;
    private const TVA = 20 * (250000 + 5000 * 3) / 100 + 5 * (1000 * 5) / 100;

    private $order;
    private $promotion1;
    private $promotion2;

    public function setUp(): void
    {
        $this->order = (new Order())
            ->addItem(new Product('Cuve à gasoil', 250000, 'Farmitoo'))
            ->addItem(new Product('Nettoyant pour cuve', 5000, 'Farmitoo'), 3)
            ->addItem(new Product('Piquet de clôture', 1000, 'Gallagher'), 5);

        $this->promotion1 = new Promotion(50000, 8, false);
        $this->promotion2 = new Promotion(50000, 8, true);
    }

    public function testGetTotalPriceHT()
    {
        $this->assertEquals(self::PRICE_HT, $this->order->getTotalPriceHT());
    }

    public function testGetPromotion()
    {
        $this->assertEquals( 0, $this->order->calculatePromotions());

        $this->order->addPromotion($this->promotion1);

        $this->assertEquals( self::REDUCTION, $this->order->calculatePromotions());
    }

    public function testGetShippingCosts()
    {
        $this->assertEquals(self::SHIPPING_COSTS, $this->order->getShippingCosts());

        $this->order->addPromotion($this->promotion1);

        $this->assertEquals(self::SHIPPING_COSTS, $this->order->getShippingCosts());

        $this->order->addPromotion($this->promotion2);

        $this->assertEquals(0, $this->order->getShippingCosts());
    }

    public function testTVA()
    {
        $this->assertEquals(self::TVA, $this->order->getVAT());
    }

    public function testTotalPriceTTC()
    {
        $this->assertEquals(
            self::PRICE_HT + self::SHIPPING_COSTS + self::TVA,
            $this->order->getTotalPriceTTC()
        );

        $this->order->addPromotion($this->promotion1);

        $this->assertEquals(
            self::PRICE_HT + self::SHIPPING_COSTS + self::TVA - self::REDUCTION,
            $this->order->getTotalPriceTTC()
        );

        $this->order->addPromotion($this->promotion2);

        $this->assertEquals(
            self::PRICE_HT + self::TVA - self::REDUCTION - (8 * self::PRICE_HT / 100),
            $this->order->getTotalPriceTTC()
        );
    }
}
