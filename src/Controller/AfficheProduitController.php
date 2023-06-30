<?php

// sami melki©2022
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produit;
use Symfony\Component\HttpFoundation\Request;

class AfficheProduitController extends AbstractController
{
    /**
     * @Route("/", name="prod_display")
     */
    public function index(): Response
    {
        $articles = $this->getDoctrine()->getRepository(Produit::class)->findAll();

        return $this->render('affiche_produit/index.html.twig', ['articles' => $articles]);
    }

    /**
     * @Route("/produit/delete/{id}", name="produit_delete")
     */
    public function delete(Produit $produit): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($produit);
        $entityManager->flush();

        return $this->redirectToRoute('prod_display');
    }
   


    



}
