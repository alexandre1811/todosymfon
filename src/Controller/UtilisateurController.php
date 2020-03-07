<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{
    /**
     * @Route("/", name="utilisateur")
     */
    public function index(Request $request)
    {
        $pdo =$this->getDoctrine()->getManager();

        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $pdo->persist($utilisateur);
            $pdo->flush();
        }
        $utilisateurs = $pdo->getRepository(Utilisateur::class)->findAll();


        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurs,
            'form_ajout' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="un_utilisateur")
     */

    public function modif(Utilisateur $utilisateur=null, Request $request){
        if ($utilisateur !=null){
            $form= $this->createForm(UtilisateurType::class, $utilisateur);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
                $pdo = $this->getDoctrine()->getManager();
                $pdo->persist($utilisateur);
                $pdo->flush();
                $this->addFlash("success", "Utilisateur Modifié");

            }

            return $this->render('utilisateur/utilisateur.html.twig', [
                'utilisateur' => $utilisateur,
                'form_edit'=>$form -> createView()

            ]);
        }

        else{
            return $this->redirectToRoute('utilisateur');
        }
    }

    /**
     *@Route("/delete/{id}", name="delete_utilisateur")
     */

    public function delete(Utilisateur $utilisateur=null){
        if ($utilisateur !=null){
            $pdo = $this->getDoctrine()->getManager();
            $pdo->remove($utilisateur);
            $pdo->flush();
            $this->addFlash("success", "Utilisateur supprimé");
        }
        else{
            $this->addFlash("danger", "utilisateur intouvable");
        }

        return $this->redirectToRoute('utilisateur');

    }








}
