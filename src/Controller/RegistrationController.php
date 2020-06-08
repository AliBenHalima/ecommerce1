<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResgisterType;
use Doctrine\ORM\Mapping as ORM;
use App\Form\RegistrationFormType;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class RegistrationController extends AbstractController
{
    public function __construct(UserRepository $userrepository, ObjectManager $em)
    {
        $this->userrepository = $userrepository;
        $this->em = $em;
    }

  // Registration form

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(ResgisterType::class, $user); // form
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword($user, $form->get('password')->getData()) //similar to $user->getPassword()
                // encode password and save it in data base as encrypted for more security
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();


            return $this->redirectToRoute('base');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/", name="base" ,methods={"GET"})
     */
    public function index(UserRepository $userrepository): Response
    {
        if ($this->getUser() == NULL) { //getUser() returns the Current User yeeey
            return $this->render('welcome.html.twig');
        } else {
        }
        return $this->render('welcome.html.twig', [
            'user' => $userrepository->find($this->getUser()->getId()),
        ]);
    }

    // Show Profile for a particular User

    /**
     * @Route("/profil/{id}", name="profil")
     * @param Request $request
     * @param User $user
     */
    public function EditUser(User $user, Request $request): Response
    {
        $form = $this->createForm(ResgisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('base');
        }

        return $this->render(
            'registration/UserProfil.html.twig',
            [
                'user' => $user,
                'form' => $form->createView(),
            ]
        );
    }
}
