<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Message;
use Symfony\Component\Routing\Annotation\Route;

class ContactsController extends AbstractController
{
    /**
     * @Route("/contacts", name="contacts")
     */
    public function index(): Response
    {
        return $this->render('contacts/index.html.twig', [
            'controller_name' => 'ContactsController',
        ]);
    }

    /**
     * @Route("/sendmail", name="sendcontactmail")
     * @param MailerInterface $mailer
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function sendMail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('noreplystroybel@gmail.com')
            ->to('noreplystroybel@gmail.com')
            ->subject($_POST['subject'])
            ->html("
                        <h2>Новое сообщение от пользователя сайта!</h2>
                        <p><b>Имя отправителя:</b> " . $_POST['name'] . "</p>   
                        <p><b>Email отправителя:</b> " . $_POST['email'] . "</p>   
                        <p><b>Сообщение:</b> " . $_POST['message'] . "</p>   
                   ");
        $mailer->send($email);
        $this->addFlash(
            'notice',
            'Your changes were saved!'
        );
        return $this->redirectToRoute('contacts');
    }
}
