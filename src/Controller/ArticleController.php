<?php
//   this Controller of all article routes and business logic 
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
//
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


// function created to return the current user ID
    public function GetAppUser($rep)
    {
        $user = new User();   //create new user
        if ($this->getUser() != NULL) {  //if we find a user
            $user = $rep->find($this->getUser()->getId()); 
        }
        return $user;  // return his ID
    }



    // function returns the index page and all the products
    /**
     * @Route("/", name="article_index", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        $user = $this->GetAppUser($this->rep); // rep refers to UserRepository check Constructor
        return $this->render('article/index.html.twig', [
            'articles' => $articleRepository->findAll(), // findall() function defined in UserRepository
            'user' => $user
        ]);
    }


// when we want to create a new article go through /article/new
    /**
     * @Route("/new", name="article_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $article = new Article(); //instantiate  your Article class
        $form = $this->createForm(ArticleType::class, $article); //create a form
        $form->handleRequest($request); //handle your request 

        if ($form->isSubmitted() && $form->isValid()) { // if form is submitted and valid, typical process...
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

    // show all Articles (PS: you must install the Paginator bundle , check the ReadMe File for more infos)
    /**
     * @Route("/ShowAll", name="property.ShowAll")
     * @return Response
     * @param Request $request
     * @param ArticleRepository $repository
     */
    public function ShowAll(PaginatorInterface $paginator, Request $request, ArticleRepository $repository, UserRepository $rep): Response
    {
        $user = new User(); // basically the same as GetAppUser() defined above
        if ($this->getUser() != NULL)
            $user = $rep->find($this->getUser()->getId());
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search); // this is for the search Entity so we can make search for articles
        $form->handleRequest($request);

      // this is a paginator function defined in the documentation of the bundle 
      //check the documentation on github for more infos
      // so here we will simply get 4 article on each page
        $articles = $paginator->paginate(
            $this->repository->findSearch($search),
            $request->query->getInt('page', 1), 4 );  /*1 = page number*/ /* 4= limit per page*/
        

        return $this->render('article/ShowAll.html.twig', [
            'articles' => $articles,
            'user' => $user,
            'form' => $form->createView()


        ]);
    }
 
    //Manage "Panier" using sessions to store all the data needed
    /**
     * @Route("/panier" , name="panier_index") 
     */
    public function panierIndex(UserRepository $rep, SessionInterface $session, ArticleRepository $articleRepository)
    {
        $user = $this->GetAppUser($rep); // defined above

        $panier = $session->get('panier', []); // get the value of 'Panier' from session, id it doesnt exist create one with empty value

        $panierWithData = [];
        foreach ($panier as $id => $qte) { // loop through the array and fetch values into panierWithData array
            $panierWithData[] = [
                'product' => $articleRepository->find($id),// find the article based on the given id
                'qte' => $qte, // qte is the value of the id key
            ];
        }
        $total = 0; // total initially is 0
        foreach ($panier as $id => $qte) {
            $user = $articleRepository->find($id); // find article based on id
            $price = $user->getArtPrix(); // get price of the article(Ps: Article and User have ManyToOne relationship)
            if ($user->getPromotion()) { // if that article is in promotion
                $price = ($price * (100 - $user->getArtRemise())) / 100; // apply the promotion formula
            }
            $totalprod = $qte * $price;
            $total = $total + $totalprod;} // get the final price
        
        return $this->render('article/panier.html.twig', [
            'items' => $panierWithData,
            'total' => $total,
            'user' => $user,
          
        ]);
    }
    /**
     * unset cookies when I Click on "PAY" !!!
     * @Route("/unset" , name="unsetCookies")
     * @param SessionInterface $session
     * @return void
     */
    public function unsetCookies(SessionInterface $session)
    {
        $panier = $session->get('panier', []);
        foreach ($panier as $id => $qte) {
            unset($panier[$id]); // deleted storage
            $session->set('panier', $panier); //set the new value to 'panier'
        }
        return $this->redirectToRoute('panier_index');
    }

    // add items to 'panier'

    /**
     * @Route("/panier/add/{id}" , name="panier_add") 
     */
    public function add($id, SessionInterface $session, Request $request)
    {
        $user = $this->GetAppUser($this->rep);
        $panier = $session->get('panier', []);
        if (!empty($panier[$id])) { // if the 'panier' key has a value for a given id
            $panier[$id]++; // increment tha value
        } else {
            $panier[$id] = 1; // else (panier is empty) put 1 as value    
        }
        $session->set('panier', $panier);
        return $this->redirectToRoute('property.ShowAll'); 
    }

// remove articles from panier 

    /**
     * @Route("/panier/remove/{id}" , name="panier_remove")
     */

    public function remove($id, SessionInterface $session)
    {
        $panier = $session->get('panier', []);
        if (!empty($panier[$id])) { // if panier not empty simply unset values
            unset($panier[$id]);
        }
        $session->set('panier', $panier); // set the panier to the new panier value which is empty
        return $this->redirectToRoute('panier_index');
    }


//show each article discription 

    /**
     * @Route("/{id}", name="article_show", methods={"GET"})
     */
    public function show(Article $article): Response
    {
        $user = $this->GetAppUser($this->rep); //get current user
        return $this->render('article/show.html.twig', [
            'article' => $article,
            'user' => $user
        ]);
    }

// edit article 

    /**
     * @Route("/{id}/edit", name="article_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Article $article): Response
    {
        $user = $this->GetAppUser($this->rep);
        $form = $this->createForm(ArticleType::class, $article); // create form of article entity
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

//delete article 

    /**
     * @Route("/{id}", name="article_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
          // check if the token sent from the navigator is equal to the one stored in the server to verify 
          // otherwise we won't be abe to delete

            $entityManager->remove($article);

            $entityManager->flush();
        }

        return $this->redirectToRoute('article_index');
    }


    // return an article based on ID

    /**
     * @Route("/product/{id}", name="product" ,methods={"GET"})
     * @param Article $article
     */
    public function product(Article $article, ArticleRepository $articlerep): Response
    {
        $user = $this->GetAppUser($this->rep);
      

        return $this->render('product.html.twig', [
            'article' => $article,
            'Nextarticle1' => $articlerep->find(($article->getId()) ),
            'Nextarticle2' => $articlerep->find(($article->getId()) ),
            'Nextarticle3' => $articlerep->find(($article->getId())),
            'Nextarticle4' => $articlerep->find(($article->getId())) ,
            'user' => $user, // simply fetch few article from database to display on page,it has bugs i know...

        ]);
    }


    // Like Process .. Still developping it dont read it please Thanks ....

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
