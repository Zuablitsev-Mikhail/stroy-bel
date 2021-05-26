<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class CheckoutController extends AbstractController
{
    /**
     * @Route("/checkout", name="checkout")
     */
    public function index(Request $request, Security $security, ProductRepository $productRepository)
    {
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);
        $productListInCart = [];
        $coast = 0;
        if ($form->isSubmitted() && $form->isValid()) {
            if(!isset($_COOKIE['cart'])) setcookie('cart',0, time() + 86400, "/");
            else $cookie = json_decode($_COOKIE['cart']);
            $order->setProduct($cookie);
            $user = $security->getUser();
            $order->setUser($user);
            $order->setStatus(0);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($order);
            $entityManager->flush();
            setcookie('cart',0, time() + 86400, "/");
            return $this->redirectToRoute('main');
        }

        if(isset($_COOKIE['cart'])) {
            $productListInCart = json_decode($_COOKIE['cart']);
        }

        $productsInCart = $productRepository->findBy(['id' => $productListInCart]);

        foreach ($productsInCart as $product){
            $coast += $product->getPrice();
        }
        return $this->render('checkout/index.html.twig', [
            'order' => $order,
            'form' => $form->createView(),
            'coast' => $coast
        ]);
    }
}
