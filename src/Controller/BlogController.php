<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */

    public function index()
    {

        $repo = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repo->findAll();
    
        //-> use App/Entity/Article ;

        return $this->render('blog/index.html.twig', [

            'articles'=>$articles

        ]);
    }


     /**
     * @Route("/", name="home")
     */   
    public function home()
    {
        return $this->render('blog/home.html.twig', [

        ]);
    }


  

    /**
    * @Route("/blog/new", name="blog_create")
    * @route("/blog/{id}/edit", name= "blog_edit")
    */

    public function create(Article $article = null, Request $request, ObjectManager $manager)
    {
        // dump($request) ;
    
        if(!$article){
        $article = new Article ;
        }


        $form = $this->createFormBuilder($article)
            ->add('title')
            ->add('content')
            ->add('image')
            ->getForm();

        if(!$article->getId()){
            $article->setCreatedAt(new \DateTime());
        }

        $form->handleRequest($request) ;

        // dump($article) ;

        if($form->isSubmitted() && $form->isValid()){
	    // $article->setCreatedAd(new DateTime()) ;
	    $manager->persist($article) ;
	    $manager->flush() ;
	    return $this->redirectToRoute('blog_show' , ['id'=>$article->getId()]) ;

        }

    
        return $this->render('blog/create.html.twig',[
        'formArticle'=>$form->createView(),
        'editMode' => $article->getId()
        ]) ;
    

        
        
    }
    
            
    


    /**
    * @Route("blog/{id}", name="blog_show")     
    */

    public function show($id){
    
        $repo = $this->getDoctrine()->getRepository(Article::class);
        $article = $repo->find($id);

    return $this->render('blog/show.html.twig',[

        'article'=>$article

    ]) ;

    }
    

}




