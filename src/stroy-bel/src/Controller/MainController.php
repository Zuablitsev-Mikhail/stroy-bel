<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\NewsRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     * @param ProductRepository $productRepository
     * @param NewsRepository $newsRepository
     * @param CategoryRepository $categoryRepository
     * @return Response
     */
    public function index(ProductRepository $productRepository, NewsRepository $newsRepository, CategoryRepository $categoryRepository): Response
    {
        return $this->render('main/main.index.html.twig', [
            'controller_name' => 'MainController',
            'products' => $productRepository->getAFewProductsSortedByDate(),
            'newsList' => $newsRepository->getAFewElementsFromNewsListSortedByDate(),
            'categories' => $categoryRepository->findCategoriesSortedByTitle(),
        ]);
    }
}
