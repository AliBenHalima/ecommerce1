<?php

namespace App\Controller;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\ResgisterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(ResgisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $passwordEncoder->encodePassword($user,$form->get('password')->getData()) //similar to $user->getPassword()

                );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_register');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

  /**
     * @Route("/", name="base")
     */
    public function index(): Response
    {

    return $this->render('base.html.twig');
    }

    /**
     * @Route("/profil/{id}", name="profil")
     * @param Request $request
     * @param User $user
     */
    public function EditUser(User $user ,Request $request): Response
    {  
        $form = $this->createForm(ResgisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('/');
        }

    return $this->render('registration/UserProfil.html.twig',
    ['user'=>$user,
    'form' => $form->createView(),
    ]);
    }
   
}
