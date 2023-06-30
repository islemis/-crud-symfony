<?php
//sami melki©2022
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produit;
use App\Form\ProduitType;
use Symfony\Component\HttpFoundation\Request;

class ProduitController extends AbstractController
{

    public function index(Request $request)
    { {
            $produit = new Produit();
            $form = $this->createForm(ProduitType::class, $produit);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $produit = $form->getData();
                //****************Manage Uploaded FileName
                $photo_prod = $form->get('photo')->getData();
                $originalFilename = $photo_prod->getClientOriginalName();
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $photo_prod->getClientOriginalExtension();
                $photo_prod->move($this->getParameter('images_directory'), $newFilename);
                $produit->setPhoto($newFilename);
                //****************
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($produit);
                $entityManager->flush();
                //return $this->redirectToRoute('confirm');
            }
            return $this->render('produit/index.html.twig', ['form' => $form->createView(),]);
        }
    }
}
