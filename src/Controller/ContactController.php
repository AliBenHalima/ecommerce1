<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Search;
use App\Entity\Article;
use App\Entity\Contact;
use App\Form\SearchType;
use App\Entity\Categorie;
use App\Form\ArticleType;
use App\Form\ContactType;
use App\Entity\ArticleLike;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use App\Repository\ContactRepository;
use App\Repository\ArticleLikeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class ContactController extends AbstractController
{
    public function __construct(ContactRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }
    /**
     * Formulaire de Contact
     * @Route("/contact" , name="contact")
     */
    public function contact(Request $request, UserRepository $rep)
    {
        $user = new User();
        if ($this->getUser() != NULL) {
            $user = $rep->find($this->getUser()->getId());
        }

        $con = new Contact();
        $form = $this->createForm(ContactType::class, $con);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($con);
            $entityManager->flush();

            return $this->redirectToRoute('contact');
        }

        return $this->render('contact.html.twig', [
            'contact' => $con,
            'form' => $form->createView(),
            'user' => $user
        ]);
    }
}
