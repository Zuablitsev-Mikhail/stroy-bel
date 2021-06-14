<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     */
    public function index(ProductRepository $productRepository): Response
    {
        if (isset($_COOKIE['cart'])) {
            $data = json_decode($_COOKIE['cart']);
        } else {
            $data = "";
        }
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'CartController',
            'products' => $productRepository->findAll(),
            'data' => $data
        ]);
    }

    /**
     * @Route("cart/addtocart/{id}/{quantity}", name="addCart")
     * @param int $id
     * @param int $quantity
     * @return Response
     */
    public function addToCartAction(int $id, int $quantity = 1): Response
    {
        if (!isset($_COOKIE['cart'])) setcookie('cart', 0, time() + 86400, "/");
        else $cookie = json_decode($_COOKIE['cart']);
        for ($i = 0; $i < $quantity; $i++) {
            $cookie[] = intval($id);
        }
        setcookie('cart', json_encode($cookie), time() + 86400, "/");
        $resData['cntItems'] = count($cookie);
        $resData['success'] = 1;
        echo json_encode($resData);
        return $this->redirect($this->generateUrl('view') . "?id=$id");
    }

    /**
     * @Route("cart/removefromcart/{id}", name="removeFromCart")
     * @param int $id
     * @return RedirectResponse
     */
    public function removeFromCartAction(int $id): RedirectResponse
    {
        $cookie = [];
        if (!isset($_COOKIE['cart'])) setcookie('cart', 0, time() + 86400, "/");
        else $cookie = json_decode($_COOKIE['cart']);
        for ($i = 0; $i < sizeof($cookie); $i++) {
            if ($cookie[$i] == $id)
                $cookie[$i] = 0;
        }
        setcookie('cart', json_encode($cookie), time() + 86400, "/");
        return $this->redirect($this->generateUrl('cart'));
    }

    /**
     * @Route("cart/editCart/{id}/{quantity}", name="editCart")
     * @param int $id
     * @param int $quantity
     * @return RedirectResponse
     */
    public function editCartAction(int $id, int $quantity): RedirectResponse
    {
        $cookie = [];
        if (!isset($_COOKIE['cart'])) setcookie('cart', 0, time() + 86400, "/");
        else $cookie = json_decode($_COOKIE['cart']);
        for ($i = 0; $i < sizeof($cookie); $i++) {
            if ($cookie[$i] == $id)
                $cookie[$i] = 0;
        }
        for ($i = 0; $i < $quantity; $i++) {
            $cookie[] = intval($id);
        }

        setcookie('cart', json_encode($cookie), time() + 86400, "/");
        return $this->redirect($this->generateUrl('cart'));
    }
}
