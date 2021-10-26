<?php

namespace App\Controller;

use App\Entity\Board;
use App\Entity\Comment;
use App\Form\BoardType;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Mercure\CookieGenerator;


class BoardController extends AbstractController
{
    /**
     * @Route("/", name="board")
     */
    public function index(Security $security): Response
    {

        $this->security = $security;
        $user = $this->security->getUser();

        $repo = $this->getDoctrine()->getRepository(Board::class);

        $boards = $repo->findAll();

        return $this->render('board/index.html.twig', [
            'boards' => $boards,
            'user' => $user
        ]);
    }


    /**
     * @Route("/board/new", name="board_create")
     * @Route("/board/{id}/edit", name="board_edit")
     */
    public function form(Board $board = null, Request $request, EntityManagerInterface $manager, Security $security)
    {

        $this->security = $security;
        $user = $this->security->getUser();
        if (!$user) {
            return $this->redirectToRoute('board');
        } else {
            $user = $user->getUsername();
        }


        if (!$board) {
            $board = new Board();
            $board->setAuthor($user);
        }

        $form = $this->createForm(BoardType::class, $board);



        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($board);
            $manager->flush();

            return $this->redirectToRoute('board_show', ['id' => $board->getId()]);
        }


        return $this->render('board/create.html.twig', [
            'formBoard' => $form->createView(),
            'editMode' => $board->getId() !== null,
            'user' => $user,
            'board' => $board
        ]);
    }
    /**
     * @Route("/board/{id}", name="board_show")
     */
    public function show(Board $board, Request $request, EntityManagerInterface $manager, Security $security)
    {

        $this->security = $security;
        $user = $this->security->getUser();



        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new \DateTime())
                ->setBoard($board);
            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute('board_show', ['id' => $board->getId()]);
        }


        return $this->render('board/show.html.twig', [
            'board' => $board,
            'commentForm' => $form->createView(),
            'user' => $user
        ]);
    }
}
