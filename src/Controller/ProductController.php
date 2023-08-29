<?php

namespace App\Controller;

use App\Entity\Product;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ProductController extends AbstractController
{
    #[Route('/product/show', name: 'product_show')]
    public function afficheProduits(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
        $produits = $entityManager->getRepository(Product::class)->findAll();

        return $this->render('product/show_product.html.twig', [
            'products' => $produits,
        ]);
    }

    #[Route('/produits/details/{id}', name: 'product_details')]
    public function detailProduits(Product $product = null): Response
    {
        if ($product == null) {
            return $this->redirectToRoute('accueil');
        }
        return $this->render('product/detail_product.html.twig', [
            'product' => $product,
            
        ]);
           
        }

      
    }


