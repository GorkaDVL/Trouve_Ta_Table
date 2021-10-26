<?php

namespace App\Controller;
use App\Entity\User;
use App\Entity\Board;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request,EntityManagerInterface $manager,UserPasswordEncoderInterface $encoder) {
        $user = new User();
        
        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush($user);

            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);

    }
    /**
     * @Route("/connexion", name="security_login")
     */
    public function login(){
        return $this->render('security/login.html.twig');
    }
     /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout(){}

    /**
    * @Route("/profile", name="user_profile")
    */
    public function userProfile(Security $security)
    {
        $this->security = $security;
        $user = $this->security->getUser();
        return $this->render('security/userProfile.html.twig', [
            'user' => $user,
        ]);
    }
    /**
    * @Route("/games", name="user_games")
    */
    public function userGames(Security $security)
    {
        $this->security = $security;
        $user = $this->security->getUser();
        return $this->render('security/userGames.html.twig', [
            'user' => $user,
        ]);
    }
    /**
    * @Route("/profile/pass/modifier", name="users_pass_modifier")
    */
    public function EditPass(Request $request,Security $security, UserPasswordEncoderInterface $encoder)
    {
        if($request->isMethod('POST')){
            $manager = $this->getDoctrine()->getManager();

            $user = $this->getUser();

            if($request->request->get('pass') == $request->request->get('pass2')){
                $user->setPassword($encoder->encodePassword($user, $request->request->get('pass')));
                $manager->flush();
                $this->addFlash('message', 'Mot de passe mis à jour');

                return $this->redirectToRoute('user_profile');

            }else{
                $this->addFlash('error', 'Le mot de passe doit être identiques !');
            }


        }
  
        return $this->render('security/editpass.html.twig');
    }
        
}