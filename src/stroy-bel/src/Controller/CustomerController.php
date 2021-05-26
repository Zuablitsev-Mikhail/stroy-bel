<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\AddressRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class CustomerController extends AbstractController
{
    /**
     * @Route("/customer", name="customer")
     */
    public function index(Security $security, UserRepository $userRepository, ProductRepository $productRepository): Response
    {
        $customer =  new User();
        $customer = $userRepository->findOneBy(['email' => $this->getUser()->getUsername()]);

        $orders = $customer->getOrders();
        $addresses = $customer->getAddresses();

        $orders = $customer->getOrders();
        $productIdsInCart = [];
        foreach ($orders as $order)
        {
            array_push($productIdsInCart, $order->getProduct());
        }

        return $this->render('customer/index.html.twig', [
            'controller_name' => 'CustomerController',
            'addresses' => $addresses,
            'products' => $productRepository->findAll(),
            'data' => $productIdsInCart[0]
        ]);
    }
}
