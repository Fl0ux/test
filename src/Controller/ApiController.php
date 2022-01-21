<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\Order;
use NumberFormatter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

/**
 * @Route("/api")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/panier")
     */
    public function basket(Request $request): JsonResponse
    {
        /** @var Order $order */
        $order = $request->getSession()->get('order');
        $slugger = new AsciiSlugger();

        /** @var Item $item */
        foreach ($order->getItems() as $item) {
            if ($slugger->slug($item->getProduct()->getTitle())->toString() === $request->get('slug')) {
                $item->setQuantity($request->get('quantity'));

                break;
            }
        }

        $formatter = new NumberFormatter('fr', NumberFormatter::CURRENCY);
        $formatter->setTextAttribute(NumberFormatter::CURRENCY_CODE, 'EUR');

        return $this->json(
            [
                'price' => $formatter->format($item->getProduct()->getPrice() * $item->getQuantity()),
                'ht' => $formatter->format($order->getTotalPriceHT()),
                'promotion' => $formatter->format($order->calculatePromotions()),
                'shippingCosts' => $formatter->format($order->getShippingCosts()),
                'vat' => $formatter->format($order->getVAT()),
                'ttc' => $formatter->format($order->getTotalPriceTTC()),
            ]
        );
    }
}
