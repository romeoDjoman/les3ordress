<?php
namespace App\Services;

use App\Entity\Orders;
use App\Entity\Panier;
use App\Entity\Product;
use App\Entity\CartDetails;
use App\Entity\OrderDetails;
use App\Entity\PanierDetails;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class OrderServices{
    private $manager;
   
    // Entity mananger pour insérer dans la base de données
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
 
    }

    public function createOrder($panier, )
    {

        $order = new Orders();//remplissage  table orders
        $order->setReference($panier->getReference())
        // ->setFullname($panier->getFullName())
            ->setTransportName($panier->getTransportName())
            ->setTransportPrice($panier->getTransportPrice())
            // ->setLivraisonAdresse($panier->getLivraisonAdresse())
            ->setMoreInformations($panier->getMoreInformations())
            ->setCreatedAt($panier->getCreatedAt())
            // ->setUser($panier->getUser())
            // ->setQuantity($panier->getQuantity())
            ->setSubTotalHT($panier->getSubTotalHT())
            // ->setTaxe($panier->getTaxe())
            ->setSubTotalTTC($panier->getSubTotalTTC());
        $this->manager->persist($order);
        
        $products = $panier->getPanierDetails()->getValues();

        foreach ($products as $panier_product) {
            $orderDetails = new OrderDetails();//remplissage table orderDetails
            $orderDetails->setOrders($order)
                         ->setProductName($panier_product->getProductName())
                         ->setProductPrice($panier_product->getProductPrice())
                         ->setProductQuantity($panier_product->getProductQuantity())
                         ->setSubtotalProductHT($panier_product->getSubTotalHt())
                         ->setTaxeProduct($panier_product->getTaxe())
                         ->setSubtotalProductTTC($panier_product->getSubTotalTtc());
                         
            $this->manager->persist($orderDetails);
        }

        $this->manager->flush();

        return $order;
    }

    public function getLineItems($panier){
        $panierDetails = $panier->getPanierDetails();
        $transportName = $panier->getTransportName();
        $transportPrice = $panier->getTransportPrice();
        $line_items = [];
        foreach ($panierDetails as $details) {
            //$product = $repoProduct->findOneByName($details->getProductName());
            
            $line_items[] = [
                'price_data' => [
                  'currency' => 'eur',
                  'unit_amount' => $details->getProductPrice()*100,
                  'product_data' => [
                    'name' => $details->getProductName(),
                  ],
                ],
                'quantity' =>  $details->getProductQuantity(),
            ];
        }
        //transport
        $line_items[] = [
            'price_data' => [
              'currency' => 'eur',
              'unit_amount' => $transportPrice,
              'product_data' => [
                'name' => 'Transport ( '.$transportName.' )',
              ],
            ],
            'quantity' =>  1,
        ];
        // Taxe
        $line_items[] = [
            'price_data' => [
              'currency' => 'eur',
              'unit_amount' => $panier->getTaxe(),
              'product_data' => [
                'name' => 'TVA (20%)',
              ],
            ],
            'quantity' =>  1,
        ];
        return $line_items;
    }

    public function savePanier($data, $user)
    {
        /*[
            'products' => [],//tous les produits du panier
            'data' => [],//sous-total, taxe, totalTTC
            'checkout' => [
                'address' => objet,
                'transport' => objet,
                'informations' => sdfsfn
            ]
        ]*/
        $panier = new Panier();//remplissage de la table Panier
        $reference = $this->generateUuid();
        $address = $data['checkout']['address'];
        $transport = $data['checkout']['transport'];
        $informations = $data['checkout']['informations'];
        

        $panier->setReference($reference)
             ->setTransportName($transport->getNameTransport())
             ->setTransportPrice($transport->getPrice())
             ->setFirstName($address->getFirstName())
             ->setLastName($address->getLastName())
             ->setAdresseLivraison($address)
             ->setMoreInformations($informations)
             ->setQuantityPanier($data['data']['quantity_cart'])//voir dans CartServices.php
             ->setSubTotalHT($data['data']['subTotalHT'])
             ->setTaxePanier($data['data']['Taxe'])
             ->setSubTotalTTC($data['data']['subTotalTTC']+$transport->getPrice()/100)
             ->setUser($user)
             ->setCreatedAt(new \DateTime());
        $this->manager->persist($panier);
        
        //creation de l'objet cart details
        $panier_details_array = [];
        //dans session deux clés voir dans CartServices : "quantity" => $quantity, "product" => $product
        foreach ($data['products'] as $products) {
            $panierDetails = new PanierDetails(); //remplissage de la table panier-details
            $subtotal = $products['quantity'] * $products['product']->getPrice()/100;
            $panierDetails->setPanier($panier)
            ->setProductName($products['product']->getNameProduct())
            ->setProductPrice($products['product']->getPrice()/100)
            ->setProductQuantity($products['quantity'])
            ->setSubtotalProductHT($subtotal)
            ->setTaxeProduct(round($subtotal*0.2),2)
            ->setSubtotalProductTTC(round($subtotal*1.2),2);
            
        $this->manager->persist($panierDetails);
        $panier_details_array[] = $panierDetails;
        }
        $this->manager->flush();

        return $reference;
    }
    

    public function generateUuid()
    {
        // Initialise le générateur de nombres aléatoires Mersenne Twister
        mt_srand((double)microtime()*100000);

        //strtoupper : Renvoie une chaîne en majuscules
        //uniqid : Génère un identifiant unique
        $charid = strtoupper(md5(uniqid(rand(), true)));

        //Générer une chaîne d'un octet à partir d'un nombre
        $hyphen = chr(45);

        //substr : Retourne un segment de chaîne
        $uuid = ""
        .substr($charid, 0, 8).$hyphen
        .substr($charid, 8, 4).$hyphen
        .substr($charid, 12, 4).$hyphen
        .substr($charid, 16, 4).$hyphen
        .substr($charid, 20, 12);
        
        return $uuid;
    }
}