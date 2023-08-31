<?php

namespace App\Controller;

use App\Form\CheckoutType;
use App\Services\CartServices;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

class CheckoutController extends AbstractController
{
    private $cs;
    private $rs;

    public function __construct(CartServices $cs, RequestStack $rs)
    {
        $this->cs = $cs;
        $this->rs = $rs;
    }

    #[Route('/checkout', name: 'checkout')]
    public function index(Request $request, UserInterface $user): Response
    {
        $cart = $this->cs->cartWithData();

        if (!isset($cart['products'])) {
            return $this->redirectToRoute("accueil");
        }
        if (!$user->addresses->getValues()) {
            $this->addFlash('checkout_message', 'Merci de renseigner une adresse de livraison avant de continuer !');
            return $this->redirectToRoute("app_address_new");
        }
        if ($this->rs->get('checkout_data')) {
            return $this->redirectToRoute('checkoutConfirm');
        }

        $form = $this->createForm(CheckoutType::class, null, ['user' => $user]);
        //$form->handleRequest($request);
        //traitement du formulaire

        return $this->render('checkout/index.html.twig', [
            'cart' => $cart,
            'checkout' => $form->createView()
        ]);
    }


    #[Route('/checkout/confirm', name: 'checkoutConfirm')]
    public function confirm(Request $request, OrderServices $orderServices): Response
    {
        $user = $this->getUser();
        $cart = $this->cs->cartWithData(); //deux fois utiliser donc faire un contructeur

        if (!isset($cart['products'])) {
            return $this->redirectToRoute("accueil");
        }
        if (!$user->addresses->getValues()) {
            $this->addFlash('checkout_message', 'Merci de renseigner une adresse de livraison avant de continuer !');
            return $this->redirectToRoute("address_new");
        }
        //dd($cart['products'][0]);
        $form = $this->createForm(CheckoutType::class, null, ['user' => $user]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() || $this->rs->get('checkout_data')) {
            if ($this->rs->get('checkout_data')) {
                $data = $this->rs->get('checkout_data');
            } else {

                $data = $form->getData();
                $this->rs->set('checkout_data', $data);
            }

            $address = $data['address'];
            $transport = $data['transport'];
            $informations = $data['informations'];
            //dd($data);
            //save panier
            $cart['checkout'] = $data;
            //dd($cart);

            $reference = $orderServices->saveCart($cart, $user);
            //dd($reference);
            return $this->render('checkout/confirm.html.twig', [
                'cart' => $cart,
                'address' => $address,
                'transport' => $transport,
                'informations' => $informations,
                'reference' => $reference,
                'apiKeyPublic' => $_ENV['key_test_stripe_public'],
                'checkout' => $form->createView()
            ]);
        } else {
            return $this->redirectToRoute('checkout');
        }
    }

    #[Route('/checkout/edit', name: 'checkout_edit')]
    public function checkoutEdit(): Response
    {
        $this->rs->set('checkout_data', []);
        return $this->redirectToRoute("checkout");
    }
}
