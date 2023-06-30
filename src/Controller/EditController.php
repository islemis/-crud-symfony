<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Produit;
use App\Form\ProduitType;

class EditController extends AbstractController
{
    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit(Request $request, Produit $produit): Response
    {
        $form = $this->createForm(ProduitType::class, $produit, [
            'photo_value' => $produit->getPhoto(), // <--- passer la valeur actuelle de la photo du produit
        ]);



        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $produit = $form->getData();
            // Check if the photo field has a value
            $photo = $form->get('photo')->getData();
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $photo->getClientOriginalExtension();
                $photo->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
                $produit->setPhoto($newFilename);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('prod_display', ['id' => $produit->getId()]);
        }

        return $this->render('produit/index.html.twig', [
            'form' => $form->createView(),
            'produit' => $produit,
        ]);
    }
}
