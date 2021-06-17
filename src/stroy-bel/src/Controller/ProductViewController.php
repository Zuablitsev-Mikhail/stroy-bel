<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductViewController extends AbstractController
{
    /**
     * @Route("/view", name="view")
     * @param ProductRepository $productRepository
     * @param CommentRepository $commentRepository
     * @return Response
     */
    public function index(ProductRepository $productRepository, CommentRepository $commentRepository): Response
    {
        $rating = 0;
        if ($commentRepository->getRating($_GET['id'])[0]['rating']) {
            $rating = $commentRepository->getRating($_GET['id'])[0]['rating'];
        }
        return $this->render('product_view/index.html.twig', [
            'controller_name' => 'ProductViewController',
            'products' => $productRepository->findOneBy(['id' => (int)$_GET['id']]),
            'comments' => $commentRepository->getSortedCommentsByDate($_GET['id']),
            'rating' => $rating
        ]);
    }
}
