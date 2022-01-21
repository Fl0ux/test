<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\Promotion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): RedirectResponse
    {
        return $this->redirectToRoute('basket');
    }

    /**
     * @Route("/panier", name="basket")
     */
    public function basket(Request $request): Response
    {
        $product1 = new Product('Cuve à gasoil', 250000, 'Farmitoo');
        $product2 = new Product('Nettoyant pour cuve', 5000, 'Farmitoo');
        $product3 = new Product('Piquet de clôture', 1000, 'Gallagher');

        $promotion1 = new Promotion(50000, 8, false);

        // Je passe une commande avec
        // Cuve à gasoil x1
        // Nettoyant pour cuve x3
        // Piquet de clôture x5

        $order = (new Order())
            ->addItem($product1)
            ->addItem($product2, 3)
            ->addItem($product3, 5)
            ->addPromotion($promotion1);

        $request->getSession()->set('order', $order);

        return $this->render('basket.html.twig', compact('order'));
    }
}
