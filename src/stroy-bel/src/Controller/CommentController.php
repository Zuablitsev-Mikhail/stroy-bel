<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Product;
use App\Entity\User;
use App\Form\ProductType;
use App\Repository\CommentRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment", name="comment")
     */
    public function index(CommentRepository $commentRepository): Response
    {

        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
        ]);
    }

    /**
     * @Route("/comment/new", name="new_comment")
     * @param Request $request
     * @param ProductRepository $productRepository
     * @param Security $security
     * @param UserRepository $userRepository
     * @return Response
     */
    public function new(Request $request, ProductRepository $productRepository, Security $security, UserRepository $userRepository): Response
    {
        $comment = new Comment();
        $entityManager = $this->getDoctrine()->getManager();
        $product = $productRepository->findOneBy(['id' => $_GET['productId']]);

        $comment->setUser($userRepository->findOneBy(['email' => $security->getUser()->getUsername()]));
        $comment->setProduct($product);
        $comment->setDate(new DateTime());
        $comment->setBody($request->query->get('body'));
        $comment->setRating((int)$request->query->get('rating'));
        $entityManager->persist($comment);
        $entityManager->flush();
        return $this->redirectToRoute('view', ['id' => $_GET['productId']]);
    }
}
