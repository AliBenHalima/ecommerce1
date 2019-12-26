<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Search;
use App\Entity\Article;
use App\Entity\Contact;
use App\Form\SearchType;
use App\Entity\Categorie;
use App\Form\ArticleType;
use App\Entity\ArticleLike;
use App\Form\ContactType;
use App\Repository\UserRepository;
use App\Repository\ArticleRepository;
use App\Repository\ArticleLikeRepository;
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
    public function __construct(ArticleRepository $repository, ObjectManager $em, UserRepository $rep)
    {
        $this->repository = $repository;
        $this->em = $em;
        $this->rep = $rep;
    }


    public function GetAppUser($rep)
    {
        $user = new User();
        if ($this->getUser() != NULL) {
            $user = $rep->find($this->getUser()->getId());
        }
        return $user;
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
    public function ShowAll(PaginatorInterface $paginator, Request $request, ArticleRepository $repository, UserRepository $rep): Response
    {
        $user = new User();
        if ($this->getUser() != NULL)
            $user = $rep->find($this->getUser()->getId());
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);
        $form->handleRequest($request);
        // dd($search);
        // $products = $;
        $articles = $paginator->paginate(
            $this->repository->findSearch($search),
            $request->query->getInt('page', 1), /*page number*/
            2
        ); /*limit per page*/

        return $this->render('article/ShowAll.html.twig', [
            'articles' => $articles,
            'user' => $user,
            'form' => $form->createView()


        ]);
    }

    /**
     * @Route("/panier" , name="panier_index") 
     */
    public function panierIndex(UserRepository $rep, SessionInterface $session, ArticleRepository $articleRepository)
    {
        $user = $this->GetAppUser($rep);

        $panier = $session->get('panier', []);

        $panierWithData = [];
        foreach ($panier as $id => $qte) {
            $panierWithData[] = [
                'product' => $articleRepository->find($id),
                'qte' => $qte,
            ];
        }
        $total = 0;
        foreach ($panier as $id => $qte) {
            $user = $articleRepository->find($id);
            $price = $user->getArtPrix();
            if ($user->getPromotion()) {
                $price = ($price * $user->getArtRemise()) / 100;
            }
            $totalprod = $qte * $price;
            $total = $total + $totalprod;
        }

        // dd($panierWithData);

        return $this->render('article/panier.html.twig', [
            'items' => $panierWithData,
            'total' => $total,
            'user' => $user
            //'article' => $panierWithData['product']
        ]);
    }
    /**
     * unset cookies when I Click on "PAY"
     * @Route("/unset" , name="unsetCookies")
     * @param SessionInterface $session
     * @return void
     */
    public function unsetCookies(SessionInterface $session)
    {
        $panier = $session->get('panier', []);
        foreach ($panier as $id => $qte) {
            unset($panier[$id]);
            $session->set('panier', $panier);
        }
        return $this->redirectToRoute('panier_index');
    }

    /**
     * @Route("/panier/add/{id}" , name="panier_add") 
     */
    public function add($id, SessionInterface $session, Request $request)
    {

        $panier = $session->get('panier', []);
        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }
        $session->set('panier', $panier);
        // dd($panier);
        // return $this->redirectToRoute('property.ShowAll');
        //  return $this->render('article/panier.html.twig', [
        //   'panier' => $panier
        // ]);
        return;
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
        $session->set('panier', $panier);
        return $this->redirectToRoute('panier_index');
    }



    /**
     * @Route("/{id}", name="article_show", methods={"GET"})
     */
    public function show(Article $article): Response
    {
        $user = $this->GetAppUser($this->rep);
        return $this->render('article/show.html.twig', [
            'article' => $article,
            'user' => $user
        ]);
    }

    /**
     * @Route("/{id}/edit", name="article_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Article $article): Response
    {
        $user = $this->GetAppUser($this->rep);
        //  $cat = new Categorie();
        // $article->addCategory($cat);
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
            'user' => $user
        ]);
    }


    /**
     * @Route("/{id}", name="article_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            // $art = new ArticleLike();
            // $article->removeArtcileLike($art);

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
        $user = $this->GetAppUser($this->rep);
        //$articID = $article->getId();

        return $this->render('product.html.twig', [
            'article' => $article,
            'Nextarticle1' => $articlerep->find(($article->getId()) + 1),
            'Nextarticle2' => $articlerep->find(($article->getId()) + 2),
            'Nextarticle3' => $articlerep->find(($article->getId()) + 3),
            'Nextarticle4' => $articlerep->find(($article->getId()) + 4),
            'user' => $user,
        ]);
    }

    /**
     *@Route("/like/{id}" , name="article_like")
     */
    public function Like(Article $article, ArticleLikeRepository $likerepo, ObjectManager $manager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(
                [
                    'message' => 'you must Login First !!'
                ],
                403
            );
        }

        if ($article->IsLikedBy($user)) {

            $like = $likerepo->findOneBy([
                'article' => $article,
                'user' => $user
            ]);
            $manager->remove($like);
            $manager->flush();
            return $this->json([
                'message' => 'Like Deleted',
                'like' => $likerepo->count([
                    'article' => $article
                ])
            ], 200);
        }
        $Like = new ArticleLike();
        $Like->setArticle($article)
            ->setUser($user);
        $manager->persist($Like);
        $manager->flush();

        return $this->json([
            'message' => 'Finally',
            'likes' => $likerepo->count(['article' => $article])
        ], 200);
    }
}
