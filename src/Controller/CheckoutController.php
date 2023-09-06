<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Address;
use App\Form\CheckoutType;
use App\Services\CartServices;
use App\Services\OrderServices;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CheckoutController extends AbstractController
{
    private $cs;
    private $requestStack;

    public function __construct(CartServices $cs, RequestStack $requestStack)
    {
        $this->cs = $cs;
        $this->requestStack = $requestStack;
    }
    
    #[Route('/checkout', name: 'checkout')]
    public function index(Request $request, Address $address, User $user): Response
    {
        $user = $this->getUser();    
        $cartData = $this->cs->cartWithData();
        $totaux = $this->cs->total();

        if ($totaux == 0) {
            return $this->redirectToRoute('accueil');
        }
        
        if  ($user->getAddresses()->isEmpty()) {
            $this->addFlash('checkout_message', 'Merci de renseigner une adresse de livraison avant de continuer !');
            return $this->redirectToRoute("app_address_new");
        }

        $form = $this->createForm(CheckoutType::class, null, ['user' => $user]);
        $form->handleRequest($request);

        return $this->render('checkout/index.html.twig', [
            'cartData' => $cartData,
            'total' => $totaux,
            'checkout' => $form->createView()
        ]);
    }
    
    #[Route('/checkout/confirm', name: 'checkoutConfirm')]
    public function confirm(Request $request, OrderServices $orderServices, Address $address, User $user): Response
    {
        $user = $this->getUser(); // Récupérer toutes les informations concernant le User 
        $cartData = $this->cs->cartWithData(); // Récupérer les données du panier

        // Si le panier est vide, redirection vers la page d'accueil
        if (!isset($cartData['products'])) {
            return $this->redirectToRoute("accueil");
        }
        
        
        if ($user->getAddresses()->getValues()) {
            $this->addFlash('checkout_message', 'Merci de renseigner une adresse de livraison avant de continuer !');
            return $this->redirectToRoute("app_address_new");
        }
        
        $form = $this->createForm(CheckoutType::class, null, ['user' => $user]);
        $form->handleRequest($request);

        $checkoutData = $this->requestStack->getSession()->get('checkout_data', []);
        if ($form->isSubmitted() && $form->isValid() || $checkoutData) {
            if ($checkoutData) {
                $data = $checkoutData;
            } else {
                $data = $form->getData();
                $this->requestStack->getSession()->set('checkout_data', $data);
            }

            $address = $data['address'];
            $transport = $data['transport'];
            $informations = $data['informations'];
            //dd($data);
            //save panier
            $cart['checkout'] = $checkoutData;
            //dd($cart);
           
            $reference = $orderServices->saveCart($cart,$user);
            //dd($reference);
            return $this->render('checkout/confirm.html.twig', [
                'cart'=>$cart,
                'address' =>$address,
                'transport' =>$transport,
                'informations' =>$informations,
                'reference' =>$reference,
                'apiKeyPublic' => $_ENV['key_test_stripe_public'],
                'checkout'=>$form->createView()
            ]);            
        } else {
            return $this->redirectToRoute('checkout');
        }    
    }

    #[Route('/checkout/show', name: 'checkoutShow')]
    public function checkoutShow(): Response
    {
        $this->requestStack->getSession()->remove('checkout_data');
        return $this->redirectToRoute("checkout");
    }
    
    #[Route('/checkout/edit', name: 'checkout_edit')]
    public function checkoutEdit():Response{
        $this->requestStack->getSession()->set('checkout_data',[]);
        return $this->redirectToRoute("checkout");
    }
   
}



