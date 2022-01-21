<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testGetTitle()
    {
        $product = new Product('Cuve à gasoil', 100, 'Farmitoo');

        $this->assertSame('Cuve à gasoil', $product->getTitle());
    }
}
