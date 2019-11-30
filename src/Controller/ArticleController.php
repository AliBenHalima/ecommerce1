<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    public function __construct(ArticleRepository $repository, ObjectManager $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }
    /**
     * @Route("/", name="article_index", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="article_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/ShowAll", name="property.ShowAll")
     * @return Response
     * @param Request $request
     * @param ArticleRepository $repository
     */
    public function ShowAll(PaginatorInterface $paginator, Request $request, ArticleRepository $repository): Response
    {

        $articles = $paginator->paginate(
            $this->repository->FindArticles(),
            $request->query->getInt('page', 1), /*page number*/
            6
        ); /*limit per page*/

        return $this->render('article/ShowAll.html.twig', [
            'articles' => $articles


        ]);
    }

       /**
     * @Route("/panier" , name="panier_index") 
     */
    public function panierIndex(SessionInterface $session, ArticleRepository $articleRepository)
    {
        $panier = $session->get('panier', []);
        $panierWithData = [];
        foreach ($panier as $id => $qte) {
            $panierWithData[] = [
                'product' => $articleRepository->find($id),
                'qte' => $qte
            ];
        }
        $total=0;
        foreach ($panier as $id => $qte){
            $totalprod = $qte * $articleRepository->find($id)->getArtPrix() ;
            $total = $total + $totalprod;

        }
        // dd($panierWithData);
        
        return $this->render('article/panier.html.twig', [
            'items' => $panierWithData,
            'total' => $total
            //'article' => $panierWithData['product']
        ]);
    }

    /**
     * @Route("/panier/add/{id}" , name="panier_add") 
     */
    public function add($id, SessionInterface $session)
    {
        $panier = $session->get('panier', []);
        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }
        $session->set('panier', $panier);
       // dd($panier);
       return $this->redirectToRoute('property.ShowAll');
             //  return $this->render('article/panier.html.twig', [
         //   'panier' => $panier
       // ]);
    }
    /**
     * @Route("/panier/remove/{id}" , name="panier_remove")
     */
    
    public function remove($id, SessionInterface $session)
    {
        $panier = $session->get('panier', []);
        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }
        $session->set('panier',$panier);
        return $this->redirectToRoute('panier_index');
    }
    
 

    /**
     * @Route("/{id}", name="article_show", methods={"GET"})
     */
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="article_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Article $article): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="article_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article_index');
    }

    /**
     * @Route("/product/{id}", name="product" ,methods={"GET"})
     * @param Article $article
     */
    public function product(Article $article, ArticleRepository $articlerep): Response
    {
        //$articID = $article->getId();

        return $this->render('product.html.twig', [
            'article' => $article,
            'Nextarticle1' => $articlerep->find(($article->getId()) + 1),
            'Nextarticle2' => $articlerep->find(($article->getId()) + 2),
            'Nextarticle3' => $articlerep->find(($article->getId()) + 3),
            'Nextarticle4' => $articlerep->find(($article->getId()) + 4)
        ]);
    }
}
