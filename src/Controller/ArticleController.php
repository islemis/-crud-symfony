<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produit;
use App\Repository\ProduitRepository;

class ArticleController extends AbstractController
{

    private ProduitRepository $repo;

    public function __construct(ProduitRepository $repo)
    {
        $this->repo = $repo;
    }

    #[Route('/articles', name: 'app_articles')]
    public function index(Request $request): Response
    {
        $search = $request->query->get('search');
        $priceFrom = $request->query->get('priceFrom');
        $priceTo = $request->query->get('priceTo');

        $articles = $this->repo->findByFilters($search, $priceFrom, $priceTo);

        return $this->render('affiche_produit/index.html.twig', [
            'articles' => $articles,
        ]);
    }



    public function show(Produit $produit): Response
    {
        //print('here');
        return $this->render('article/index.html.twig', [
            'produit' => $produit,
        ]);
    }
}
