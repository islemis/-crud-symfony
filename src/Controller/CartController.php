<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Produit;
use App\Controller\console;
use Symfony\Component\HttpFoundation\JsonResponse;


class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        $panier = $session->get('panier', []);
        $panierData = [];
        foreach ($panier as $id => $quantity) {
            $produit = $this->getDoctrine()->getRepository(Produit::class)->find($id);
            if ($produit !== null) {
                $name = $produit->getName();
                $price = $produit->getPrice();
                $photo = $produit->getPhoto();

                $panierData[] = [
                    'id' => $id,
                    'name' => $name,
                    'quantity' => $quantity,
                    'price' => $price,
                    'photo' => $photo,

                ];
            }
        }


        return $this->render('cart/index.html.twig', ['items' => $panierData, 'total' => $this->getTotalPrice($panierData)]);
    }
    private function getTotalPrice(array $panierData): float
    {
        $total = 0.0;
        foreach ($panierData as $item) {
            $total += $item['quantity'] * $item['price'];
        }
        return $total;
    }

    // Add this method to calculate the total price of items in the cart



    public function add($id, Request $request)
    {
        $session = $request->getSession();
        $panier = $session->get('panier', []);
        if (!empty($panier[$id]))
            $panier[$id]++;
        else
            $panier[$id] = 1;
        $session->set('panier', $panier);
        return $this->redirectToRoute('cart');
    }
    /**
     * @Route("/update-quantity", name="update_cart_quantity", methods={"POST"})
     */
    public function updateQuantity(Request $request): JsonResponse
    {
        $productId = $request->request->get('product_id');
        $action = $request->request->get('action');

        $session = $request->getSession();
        $panier = $session->get('panier', []);

        if ($productId && isset($panier[$productId])) {
            if ($action === 'decrement') {
                if ($panier[$productId] > 1) {
                    $panier[$productId]--;
                } else {
                    unset($panier[$productId]);
                }
            } elseif ($action === 'increment') {
                $panier[$productId]++;
            }
        }

        $session->set('panier', $panier);

        return new JsonResponse(['success' => true]);
    }
    /**
     * @Route("/remove/{id}", name="remove_from_cart")
     */
    public function remove($id, Request $request): Response
    {
        $session = $request->getSession();
        $panier = $session->get('panier', []);

        if (isset($panier[$id])) {
            unset($panier[$id]);
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute('cart');
    }
}
