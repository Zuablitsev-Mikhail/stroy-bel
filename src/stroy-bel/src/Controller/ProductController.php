<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->getProductsSortedByDate(),
        ]);
    }

    /**
     * @Route("/new", name="product_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $image = $form->get('image')->getData();
            if ($image) {
                $imageName = md5(uniqid()) . '.' . $image->guessExtension();
                $uploads_directory = $this->getParameter('uploads_directory');
                $image->move(
                    $uploads_directory,
                    $imageName
                );
                $product->setImage("img/product-image/" . $imageName);
            }
            $product->setDate(new DateTime());
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product): Response
    {

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $image = $form->get('image')->getData();
            if ($image) {
                $imageName = md5(uniqid()) . '.' . $image->guessExtension();
                $uploads_directory = $this->getParameter('uploads_directory');
                $image->move(
                    $uploads_directory,
                    $imageName
                );
                $product->setImage("img/product-image/" . $imageName);
            }
            $entityManager->flush();
            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods={"POST"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_index');
    }

    /**
     * @Route("/change/{id}", name="product_change", methods={"Get"})
     */
    public function changeStatus(Request $request, Product $product): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        if ($product->getIsActive()) {
            $product->setIsActive(false);
        } else {
            $product->setIsActive(true);
        }
        $entityManager->flush();

        return $this->redirectToRoute('product_index');
    }
}
