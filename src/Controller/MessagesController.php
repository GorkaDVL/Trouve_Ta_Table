<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Form\MessageType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessagesController extends AbstractController
{
    #[Route('/messages', name: 'messages')]
    public function index(): Response
    {
        $user = $this->getUser();
        return $this->render('messages/index.html.twig', [
            'controller_name' => 'MessagesController',
            'user' => $user
        ]);
    }
    /**
     *@Route("/send", name="send")
     */
    public function send(Request $request): Response
    {
        $message = new Messages();
        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setSender($this->getUser());

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($message);
            $manager->flush();

            $this->addFlash("message", 'Message envoyé avec succès.');
            return $this->redirectToRoute("messages");
        }

        return $this->render('messages/send.html.twig', [

            'form' => $form->createView()
        ]);
    }
    /**
     *@Route("/received", name="received")
     */
    public function received(): Response
    {
        return $this->render('messages/received.html.twig');
    }
    /**
     *@Route("/sent", name="sent")
     */
    public function sent(): Response
    {
        return $this->render('messages/sent.html.twig');
    }
    /**
     *@Route("/read/{id}", name="read")
     */
    public function read(Messages $message): Response
    {
        $message->setIsRead(true);
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($message);
        $manager->flush();

        return $this->render('messages/read.html.twig', compact("message"));
    }
    /**
     *@Route("/delete/{id}", name="delete")
     */
    public function delete(Messages $message): Response
    {
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($message);
        $manager->flush();

        return $this->redirectToRoute("received");
    }
}
