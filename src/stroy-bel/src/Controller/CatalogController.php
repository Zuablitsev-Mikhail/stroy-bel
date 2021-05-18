<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CatalogController extends AbstractController
{
    /**
     * @Route("/catalog", name="catalog")
     * @param ProductRepository $productRepository
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        $productData = $productRepository->getProductsSortedByDate();
        $page = 1;

        if(isset($_GET['c'])){
            $productData = $productRepository->getAFewProductsInCategorySortedByDate($_GET['c']);
        }

        if(isset($_GET['p'])){
            $page = $_GET['p'];
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
    public function search(ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        $productData = $productRepository->getProductsByName((string) $_GET['search']);
        $page = 1;

        if(isset($_GET['p'])){
            $page = $_GET['p'];
        }

        return $this->render('catalog/index.html.twig', [
            'controller_name' => 'CatalogController',
            'products' => $productData,
            'page' => $page,
            'categories' => $categoryRepository->findCategoriesSortedByTitle(),
        ]);
    }
}
