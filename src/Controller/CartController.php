<?php 
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ProduitsRepository;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;

class CartController extends AbstractController
{
    #[Route('/cart/ajout/{id}', name: 'cart_ajout')]
    public function add(Request $request, SessionInterface $session, ProductRepository $repository, $id)
    {
        $produit = $repository->find($id);
        $cart = $session->get('cart', []);
        $quantite = $session->get('quantite', 0);

      

        if (!empty($cart[$id])) {
            $cart[$id]['quantite']++;
        } else {
            $cart[$id] = [
                'produit' => $produit,
                'quantite' => 1,
                
            ];
        }

        $quantite++;
        $session->set('quantite', $quantite);
        $session->set('cart', $cart);

        return $this->redirectToRoute('cart');
    }

    #[Route('/cart', name: 'cart')]
    public function index(SessionInterface $session)
    {
        $cart = $session->get('cart', []);
        $cartWithData = [];
        $montant = 0;

        foreach ($cart as $id => $item) {
            $produit = $item['produit'];
            $quantite = $item['quantite'];

            $cartWithData[] = [
                'produit' => $produit,
                'quantite' => $quantite,
            ];

            $montant += $produit->getPriceProduct() * $quantite;
        }

        return $this->render('cart/index.html.twig', [
            'items' => $cartWithData,
            'montant' => $montant,
        ]);
    }

    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    public function less($id, SessionInterface $session)
    {
        $cart = $session->get('cart', []);
        $quantite = $session->get('quantite', 0);

        if (isset($cart[$id])) {
            if ($cart[$id]['quantite'] != 1) {
                $cart[$id]['quantite']--;
                $quantite--;
            } else {
                unset($cart[$id]);
                $quantite--;
            }

            $session->set('quantite', $quantite);
            $session->set('cart', $cart);
        }
        return $this->redirectToRoute('cart');
    }
    #[Route('/cart/drop/{id}', name:'cart_drop')]
    public function remove($id,SessionInterface $session)
    {
       
        $cart= $session->get('cart', []);
        $quantite = $session->get('quantite', 0);

        if(!empty($cart[$id]))
        {
            $cart[$id]['quantite']=0;
            unset($cart[$id]);
        }
        if($quantite < 0){
            $quantite = 0;
        }
        $session->set('quantite', $quantite);
        $session->set('cart', $cart);
        return $this->redirectToRoute('cart');
    }
    #[Route('/cart/drop', name:'cart_drop')]
    public function deleteCart(SessionInterface $session)
    {
        $session->remove('cart');
        $session->remove('quantite');
        return $this->redirectToRoute('cart');
    }
    

}
