<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CatalogController extends AbstractController
{
    /**
     * @Route("/catalog", name="catalog")
     * @param ProductRepository $productRepository
     * @param CategoryRepository $categoryRepository
     * @param CommentRepository $commentRepository
     * @param Request $request
     * @return Response
     */
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository, CommentRepository $commentRepository, Request $request): Response
    {
        $category = $request->query->get('c');

        if ($category !== null) {
            $productData = $productRepository->getProductsInCategorySortedByDate($category);
        } else {
            $productData = $productRepository->getProductsSortedByDate();
        }
        $page = 1;

        foreach ($productData as $product) {
            $productsRating[] = $commentRepository->getRating($product->getId())[0]['rating'] ? $commentRepository->getRating($product->getId())[0]['rating'] : 0;
        }

        if ($request->query->get('p') !== null) {
            $page = $request->query->get('p');
        }

        return $this->render('catalog/index.html.twig', [
            'controller_name' => 'CatalogController',
            'products' => $productData,
            'page' => $page,
            'categories' => $categoryRepository->findCategoriesSortedByTitle(),
        ]);
    }

    /**
     * @Route("/search", name="catalogSearch")
     */
    public function search(ProductRepository $productRepository, CategoryRepository $categoryRepository, Request $request): Response
    {
        $productData = $productRepository->getProductsByName((string)$_GET['search']);
        $page = 1;

        if ($request->query->get('p') !== null) {
            $page = $request->query->get('p');
        }

        return $this->render('catalog/index.html.twig', [
            'controller_name' => 'CatalogController',
            'products' => $productData,
            'page' => $page,
            'categories' => $categoryRepository->findCategoriesSortedByTitle(),
        ]);
    }
}
