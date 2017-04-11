<?php
/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 10/04/2017
 * Time: 17:06
 */

namespace AppBundle\Controller;
use AppBundle\Form\FournisseurType;
use AppBundle\Manager\FournisseurManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/fournisseur")
 */
class FournisseurController extends Controller
{
    /**
     * @Route("/", name="app_fournisseur_index")
     */
    public function indexAction(Request $request) {
        $fournisseur =  $this->getManager()->create();
        $form = $this->createForm(FournisseurType::class,$fournisseur);
        if($request->isMethod("POST") and $request->request->has("appbundle_fournisseur")){
            $form->handleRequest($request);
            if($form->isValid()){
                $this->getManager()->persist($fournisseur,true);
                $request->getSession()->getFlashBag()->add('success', 'Fournisseur ajouté avec succès');
                return $this->redirectToRoute('app_fournisseur_index');
            }
        }
        $fournisseurs = $this->getManager()->findAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $fournisseurs, $request->query->getInt("page", 1), 6
        );

        return $this->render("AppBundle:fournisseur:index.html.twig",array(
            "fournisseurs"=>$pagination,
            "formFournisseur"=>$form->createView()
        ));
    }

    /**
     * @Route("/edit/{id}", name="app_fournisseur_edit")
     * @ParamConverter("fournisseur", class="AppBundle:Fournisseur")
     */
    public function editAction($fournisseur,Request $request) {
        $form = $this->createForm(FournisseurType::class,$fournisseur);
        if($request->isMethod("POST") and $request->request->has("appbundle_fournisseur")){
            $form->handleRequest($request);
            if($form->isValid()){
                $this->getManager()->persist($fournisseur,true);
                $request->getSession()->getFlashBag()->add('success', 'Fournisseur modifié avec succès');
                return $this->redirectToRoute('app_fournisseur_index');
            }
        }
       return $this->render("AppBundle:fournisseur:edit.html.twig",array(
            "fournisseur"=>$fournisseur,
            "formFournisseur"=>$form->createView()
        ));
    }

    /**
     * @return FournisseurManager
     */
    private function getManager(){
        return $this->get("app.fournisseur.manager");
    }
}