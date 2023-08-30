<?php

namespace App\Controller;

use App\Services\CartServices;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(CartServices $cs): Response
    {
        //$session = $rs->getSession();
        //$cart = $session->get('cart', []);
        
        //* je vais créer un nouveau tableau qui contiendra des objets Product et les quantité de chaque objet
      //  $cartWithData = [];
       // $total = 0;
        //*Pour chaque $id qui se trouve dans mon tableau $cart, j'ajoute une case au tableau $cartWithData, qui est multidimensionnel
        //* chaque case est elle-même un tableau associatif contenant 2 cases : une case 'product' (produit entier récupéré en BDD) et une case 'quantity' (avec la quantité de se produit présent dans le panier)
       // foreach($cart as $id => $quantity)
       // {
          //  $produit = $repo->find($id);
           // $cartWithData[] = [
            //    'product' => $produit,
            //    'quantity' => $quantity
          //  ];
         //   $total += $produit->getPrice() * $quantity;
      //  }
        
      $cartWithData=$cs->cartWithData();
      $total=$cs->total();

        
        return $this->render('cart/index.html.twig', [
           'items' => $cartWithData,
           'total' => $total
        ]);
    }

    #[Route('/cart/add/{id}', name: 'cart_ajout')]
    public function add($id,CartServices $cs)
    {
        $cs->add($id);
        // dd($session->get('cart'));
        $this->addFlash('success','ajout du produit dans le panier');
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove/{id}', name: 'cart_drop')]
    public function remove($id, CartServices $cs)
    {
       $cs->remove($id);
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/monPanier/decrement/{id}', name: 'decrement')]
    public function decrement(CartServices $cs, int $id): Response
    {
        $cs->decreaseCart($id);
        //dd($cartService);
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/monPanier/removeAll', name: 'remove_cart')]
    public function removeCart(CartServices $cs): Response
    {
    $cs->removeCart();
    return $this->redirectToRoute('app_cart');
    }
}
