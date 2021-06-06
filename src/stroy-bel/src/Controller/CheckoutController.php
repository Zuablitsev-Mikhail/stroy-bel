<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\User;
use App\Form\OrderType;
use App\Repository\OrderProductRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
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
    public function index(Request $request, Security $security, ProductRepository $productRepository, UserRepository $userRepository)
    {
        $order = new Order();
        $user = $security->getUser();
        $form = $this->createForm(OrderType::class, $order, ['user' => $userRepository->findOneBy(['email' => $user->getUsername()])->getId()]);
        $form->handleRequest($request);
        $productListInCart = [];
        $coast = 0;
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();


            $order->setUser($user);
            $order->setStatus(0);
            $order->setDate(new \DateTime());

            $entityManager->persist($order);
            $entityManager->flush();

            if(!isset($_COOKIE['cart'])) {
                setcookie('cart',0, time() + 86400, "/");
            }
            else {
                $cookie = json_decode($_COOKIE['cart']);
            }

            $orderedProducts = array_count_values($cookie);
            foreach ($orderedProducts as $productId=>$quantity) {
                if($productId != 0) {
                    $orderedProduct = new OrderProduct();
                    $orderedProduct->setProduct($productRepository->findOneBy(['id' => $productId]));
                    $orderedProduct->setOrder($order);
                    $orderedProduct->setQuantity($quantity);
                    $entityManager->persist($orderedProduct);
                    $entityManager->flush();
                }
            }
            setcookie('cart',"", time() + 86400, "/");
            return $this->redirectToRoute('checkout_success');
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
    /**
     * @Route("/success", name="checkout_success")
     */
    public function showSuccess(): Response
    {
        return $this->render('checkout/success.html.twig', [
        ]);
    }
}
