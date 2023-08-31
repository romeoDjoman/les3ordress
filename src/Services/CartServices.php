<?php

namespace App\Services;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartServices
{
    private $repo;
    private $rs;

    public function __construct(ProductRepository $repo, RequestStack $rs)
    {
        $this->rs = $rs;
        $this->repo = $repo;
    }

    public function add($id)
    {
        //* nous allons récupérer ou créer une session grâce à la classe RequestStack
        $session = $this->rs->getSession();
        
        $cart = $session->get('cart', []);
        $qt=$session->get('qt',0);
        //* je récupère l'attribut de session 'cart' s'il existe ou un tableau vide

        //* si le produit existe deja dans ma cart, j'incrémente sa quantité
        if(!empty($cart[$id]))
        {
            $cart[$id]++;
            $qt++;
        }else{
            $cart[$id] = 1;
            $qt++;
        }
        
        //* dans mon tableau $cart, à la case $id, je donne la valeur 1

        $session->set('cart', $cart);
        $session->set('qt',$qt);
        //* je sauvegarde l'état de mon panier en session à l'attribut de session 'cart'
    }
    public function remove($id)
    {
        $session =$this->rs->getSession();
        $cart = $session->get('cart', []);
        $qt=$session->get('qt',0);

        //*si l'id existe dans mon panier, je le supprime du tableau via unset()
        if(!empty($cart[$id]))
        {
            $qt -=$cart[$id];
            unset($cart[$id]);
        }
        if($qt <0){
            $qt=0;
        }
        $session->set('qt',$qt);
        $session->set('cart', $cart);
    }

    public function cartWithData(){
        
        $session =$this-> rs->getSession();
        $cart = $session->get('cart', []);
        
        //* je vais créer un nouveau tableau qui contiendra des objets Product et les quantité de chaque objet
        $cartWithData = [];
        $total = 0;
        //*Pour chaque $id qui se trouve dans mon tableau $cart, j'ajoute une case au tableau $cartWithData, qui est multidimensionnel
        //* chaque case est elle-même un tableau associatif contenant 2 cases : une case 'product' (produit entier récupéré en BDD) et une case 'quantite' (avec la quantité de se produit présent dans le panier)
        foreach($cart as $id => $quantite)
        {
            $produit =$this-> repo->find($id);
            $cartWithData[] = [
                'product' => $produit,
                'quantite' => $quantite
            ];
        }
        return $cartWithData;
        
    }
    public function total()
        {
            $cartWithData=$this->cartWithData();
            $total=0;

            foreach($cartWithData as $item)
            {
                $totalItem=$item['product']->getPriceProduct()*$item['quantite'];
                $total+=$totalItem;
            }
            return $total;
        }

        public function decreaseCart($id)
    {
        $session = $this->rs->getSession();
        $cart = $this->rs->getSession()->get('cart', []);
        $qt = $session->get('qt',0);
        $cart = $session->get('cart', []);
        if ($cart[$id] > 1) {
            $cart[$id]--;
            $qt--;
        } else {
            unset($cart[$id]);
        }


        $session->set('cart', $cart);
        $session->set('qt',$qt);
    }
    public function removeCart()
    {
        $session = $this->rs->getSession();
        $cart = $this->rs->getSession()->get('cart', []);
        $session->remove('cart');
        $session->remove('qt');
    }
}