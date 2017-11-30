<?php
/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 10/04/2017
 * Time: 17:06
 */

namespace AppBundle\Controller;
use AppBundle\Entity\Fournisseur;
use AppBundle\Entity\Produit;
use AppBundle\Form\MatiereType;
use AppBundle\Form\ProduitFiniType;
use AppBundle\Form\ProduitType;
use AppBundle\Form\SousProduitType;
use AppBundle\Manager\ProduitManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/produit")
 */
class ProduitController extends Controller
{
    /**
     * @Route("/matiere-premiere", name="app_matiere_premiere_index")
     */
    public function matierePremiereAction(Request $request) {
        if($request->isMethod("POST") and $request->isXmlHttpRequest()){
            $param_sort = array(
                '',
                'p.id',
                'p.titre',
                'p.prixAchat'
            );
            $start = $request->request->get("start");
            $length = $request->request->get("length");
            $sort = $param_sort[$request->request->get("order")[0]['column']];
            $dir = $request->request->get("order")[0]['dir'];
            $criteres = $request->request->get("criteres",array());
            $requestResult = $this->getManager()->listeMPDataTable($criteres, $sort, $dir, $start, $length);
            $resultat = array(
                "data" => array(),
                'recordsFiltered' => $requestResult["totalFiltred"],
                'recordsTotal' => $requestResult["total"]
            );
            $ligne = 1;
            foreach ($requestResult["produits"] as $produit) {
                $title = $produit->isArchive()?"Désarchiver":'Archiver';
                $resultat['data'][] = array(
                    'ligne' => $ligne,
                    'id' => $produit->getId(),
                    "titre" => $produit->getTitre().' <span class="badge badge-info">'.count($produit->getChildrens()).'</span>',
                    "prixAchat" => number_format($produit->getPrixAchat(), 2, ".", " "),
                    "fournisseurs" => $this->renderView("AppBundle:produit:td.html.twig",array(
                        "produit" => $produit,
                        "td" => 'fournisseurs'
                    )),
                    "options"=> $this->renderView("AppBundle:produit:td.html.twig",array(
                        "produit" => $produit,
                        "td" => 'options'
                    )),
                    "action" => $this->renderView("AppBundle:produit:td.html.twig",array(
                        "produit" => $produit,
                        "td" => 'action'
                    ))
                );
                $ligne++;
            }
            return new JsonResponse($resultat);
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }



    /**
     * @Route("/produit-fini", name="app_produit_fini_index")
     */
    public function produitFiniAction(Request $request) {
        if($request->isMethod("POST") and $request->isXmlHttpRequest()){
            $param_sort = array(
                '',
                'p.id',
                'p.titre',
                'p.prixAchat',
                'p.prix',
                'p.stock',
            );
            $start = $request->request->get("start");
            $length = $request->request->get("length");
            $sort = $param_sort[$request->request->get("order")[0]['column']];
            $dir = $request->request->get("order")[0]['dir'];
            $criteres = $request->request->get("criteres",array());
            $requestResult = $this->getManager()->listePFDataTable($criteres, $sort, $dir, $start, $length);
            $resultat = array(
                "data" => array(),
                'recordsFiltered' => $requestResult["totalFiltred"],
                'recordsTotal' => $requestResult["total"]
            );
            $ligne = 1;
            foreach ($requestResult["produits"] as $produit) {
               $resultat['data'][] = array(
                    'ligne' => $ligne,
                    'id' => $produit->getId(),
                    "titre" => $produit->getTitre(),
                    "prixAchat" => number_format($produit->getPrixAchat(), 2, ".", " "),
                    "prix" => number_format($produit->getPrix(), 2, ".", " "),
                    "stock" => $produit->getStock(),
                    "fournisseurs" => $this->renderView("AppBundle:produit:td.html.twig",array(
                        "produit" => $produit,
                        "td" => 'fournisseurs'
                    )),
                    "action" => $this->renderView("AppBundle:produit:td.html.twig",array(
                        "produit" => $produit,
                        "td" => 'action'
                    ))
               );
                $ligne++;
            }
            return new JsonResponse($resultat);
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }

    /**
     * @Route("/add", name="app_produit_add")
     */
    public function addAction(Request $request) {
        $produit = $this->getManager()->create();
        $form = $this->createForm(ProduitType::class,$produit);
        if($request->isMethod("POST") and $request->request->has("appbundle_produit") and $request->isXmlHttpRequest()){
            $form->handleRequest($request);
            if($form->isValid()) {
                $this->getManager()->persist($produit, true);
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Produit ajouté avec succès",
                    "produit" =>  $this->get('jms_serializer')->serialize($produit,'json')
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>(string)$form->getErrors(true)));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:produit:form.html.twig",array(
                "formProduit" => $form->createView(),
                "action"=>$this->generateUrl("app_produit_add"),
                "params" => $request->query->all()
                ))
        ));
    }

    /**
     * @Route("/fini/add", name="app_produit_fini_add")
     */
    public function addProduitFiniAction(Request $request) {
        $produit = $this->getManager()->create();
        $produit->setIsAchat(true);
        $form = $this->createForm(ProduitFiniType::class,$produit);
        if($request->isMethod("POST") and $request->request->has("appbundle_produit_fini") and $request->isXmlHttpRequest()){
            $form->handleRequest($request);
            if($form->isValid()) {
                $this->getManager()->persist($produit, true);
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Produit ajouté avec succès",
                    "produit" =>  $this->get('jms_serializer')->serialize($produit,'json')
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>(string)$form->getErrors(true)));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:produit:form.html.twig",array(
                "formProduitFini" => $form->createView(),
                "action"=>$this->generateUrl("app_produit_fini_add"),
                "params" => $request->query->all()
            ))
        ));
    }

    /**
     * @Route("/fini/edit/{id}", name="app_produit_fini_edit")
     * @ParamConverter("produit", class="AppBundle:Produit")
     */
    public function editProduitFiniAction(Produit $produit,Request $request) {
        $form = $this->createForm(ProduitFiniType::class,$produit);
        if($request->isMethod("POST") and $request->request->has("appbundle_produit_fini") and $request->isXmlHttpRequest()){
            $form->handleRequest($request);
            if($form->isValid()) {
                $this->getManager()->flush();
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Produit modifié avec succès",
                    "produit" =>  $this->get('jms_serializer')->serialize($produit,'json')
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>(string)$form->getErrors(true)));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:produit:form.html.twig",array(
                "formProduitFini" => $form->createView(),
                "action"=>$this->generateUrl("app_produit_fini_edit",array("id" => $produit->getId())),
                "params" => $request->query->all()
            ))
        ));
    }

    /**
     * @Route("/sousProduit/add", name="app_sous_produit_add")
     */
    public function addSousProduitAction(Request $request) {
        $produit = $this->getManager()->create();
        $form = $this->createForm(SousProduitType::class,$produit);
        if($request->isMethod("POST") and $request->request->has("appbundle_produit_sub") and $request->isXmlHttpRequest()){
            $form->handleRequest($request);
            if($form->isValid()) {
                $this->getManager()->persist($produit, true);
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Produit ajouté avec succès",
                    "produit" =>  $this->get('jms_serializer')->serialize($produit,'json')
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>(string)$form->getErrors(true)));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:produit:form.html.twig",array(
                "formSousProduit" => $form->createView(),
                "action"=>$this->generateUrl("app_sous_produit_add"),
                "params" => $request->query->all()
            ))
        ));
    }

    /**
     * @Route("/sousProduit/edit/{id}", name="app_sous_produit_edit")
     * @ParamConverter("produit", class="AppBundle:Produit")
     */
    public function editSousProduitAction(Produit $produit,Request $request) {
        $form = $this->createForm(SousProduitType::class,$produit);
        if($request->isMethod("POST") and $request->request->has("appbundle_produit_sub") and $request->isXmlHttpRequest()){
            $form->handleRequest($request);
            if($form->isValid()) {
                $this->getManager()->flush();
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Produit modifié avec succès",
                    "produit" =>  $this->get('jms_serializer')->serialize($produit,'json')
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>(string)$form->getErrors(true)));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:produit:form.html.twig",array(
                "formSousProduit" => $form->createView(),
                "action"=>$this->generateUrl("app_sous_produit_edit",array('id' => $produit->getId())),
                "params" => $request->query->all()
            ))
        ));
    }

    /**
     * @Route("/state_toggle/{id}", name="app_produit_state_toggle")
     * @ParamConverter("produit", class="AppBundle:Produit")
     */
    public function stateToggleAction(Produit $produit,Request $request) {
        if($request->isXmlHttpRequest() and $request->isMethod('post')){
            $produit->setArchive($produit->isArchive()?false:true);
            $this->getManager()->flush();
            return new JsonResponse(array(
                'code' => 1,
                'msg' => 'Statut modifié avec succès'
            ));
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }

    /**
     * @Route("/matiere/add", name="app_produit_matiere_add")
     */
    public function addMatiereAction(Request $request) {
        $matiere = $this->getManager()->create();
        $matiere->setIsMatierePremiere(true);
        $form = $this->createForm(MatiereType::class,$matiere);
        if($request->isMethod("POST") and $request->request->has("appbundle_produit_matiere") and $request->isXmlHttpRequest()){
            $form->handleRequest($request);
            if($form->isValid()){
                $this->getManager()->persist($matiere,true);
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Matière prmière ajoutée avec succès",
                    "matiere" =>  $this->get('jms_serializer')->serialize($matiere,'json')
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>(string)$form->getErrors(true)));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:produit:form.html.twig",array(
                "formMatiere" => $form->createView(),
                "action"=>$this->generateUrl("app_produit_matiere_add"),
                "params" => $request->query->all()
            ))
        ));
    }
    /**
     * @Route("/matiere/edit/{id}", name="app_produit_matiere_edit")
     * @ParamConverter("matiere", class="AppBundle:Produit")
     */
    public function editMatiereAction($matiere,Request $request) {
        $form = $this->createForm(MatiereType::class,$matiere);
        if($request->isMethod("POST") and $request->request->has("appbundle_produit_matiere") and $request->isXmlHttpRequest()){
            $form->handleRequest($request);
            if($form->isValid()){
                $this->getManager()->flush();
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Matière prmière modifiée avec succès",
                    "matiere" =>  $this->get('jms_serializer')->serialize($matiere,'json')
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>(string)$form->getErrors(true)));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:produit:form.html.twig",array(
                "formMatiere" => $form->createView(),
                "action"=>$this->generateUrl("app_produit_matiere_edit",array("id"=>$matiere->getId()))))
        ));
    }


    /**
     * @Route("/{id}", name="app_produit_get")
     * @ParamConverter("produit", class="AppBundle:Produit")
     */
    public function getAction($produit,Request $request) {
        return new JsonResponse(array(
            "code" => 1,
            "produit" =>  $this->get('jms_serializer')->serialize($produit,'json')
        ));
    }

    /**
     * @Route("/remove/{id}", name="app_produit_remove")
     * @ParamConverter("produit", class="AppBundle:Produit")
     */
    public function removeAction(Produit $produit,Request $request) {
        if($produit->getChildrens()->count() > 0 or $produit->getAchats()->count() > 0){
            return new JsonResponse(array(
                "code" => 0,
                "msg" =>  'Impossible de supprimer un enregistrement lié avec d\'autres !!'
            ));
        }
        $this->getManager()->remove($produit,true);
        return new JsonResponse(array(
            "code" => 1,
            "msg" => 'Produit supprimé avec succès'
        ));
    }

    /**
     * @Route("/getByParent/{id}", name="app_produit_list_by_parent")
     * @ParamConverter("produit", class="AppBundle:Produit")
     */
    public function listByParentAction($produit,Request $request) {
        if($request->isXmlHttpRequest()){
            $html = $this->renderView('AppBundle:produit:list.html.twig',array(
                 'produit' => $produit,
                'params' => $request->query->all()
            ));
            return new JsonResponse(array(
                'code' => 1,
                'html' => $html
            ));
        }
    }

    /**
     * @Route("unlink/{produit}/{fournisseur}", name="app_produit_fournisseur_delete")
     * @ParamConverter("produit", class="AppBundle:Produit", options={"id" = "produit"})
     * @ParamConverter("fournisseur", class="AppBundle:Fournisseur", options={"id" = "fournisseur"})
     */
    public function removeTypeFournisseurAction(Produit $produit,Fournisseur $fournisseur,Request $request) {
        if($produit->getFournisseurs()->contains($fournisseur)){
            $nbrAchat = $this->get('app.achat.manager')->count(array(
                'produit' => $produit->getId(),
                'fournisseur' => $fournisseur->getId()
            ));
            if((int) $nbrAchat > 0){
                return new JsonResponse(array(
                    "code" => 0,
                    "type" =>  'Vous ne pouvez pas détacher ce produit,car il est lié à '.(int) $nbrAchat.' achat(s)'
                ));
            }
            $produit->removeFournisseur($fournisseur);
            $fournisseur->removeProduit($produit);
            $this->getManager()->flush();
        }
        return new JsonResponse(array(
            "code" => 1,
            'msg' => 'Produit déttachée avec succès'
        ));
    }

    /**
     * @Route("/getByFournisseur/{id}", name="app_produit_liste_by_fournisseur")
     * @ParamConverter("fournisseur", class="AppBundle:Fournisseur")
     */
    public function getByFournisseurAction(Fournisseur $fournisseur,Request $request) {
        $html = $this->renderView("AppBundle:produit:liste.html.twig",array(
            'fournisseur' => $fournisseur
        ));
        return new JsonResponse(array(
            "code" => 1,
            "html" =>  $html
        ));
    }

    /**
     * @Route("/achat/autocomplet", name="app_produit_achat_autocomplete")
     */
    public function achatAutocompleteAction(Request $request) {
        if($request->request->has('fournisseur'))
            $produits = $this->getManager()->autocompleteAchat($request->request->all());
        else
            $produits = array();
        return new JsonResponse(array(
            "incomplete_results" => false,
            "items" => $produits,
            "total_count" => count($produits)
        ));
    }


    /**
     * @return ProduitManager
     */
    private function getManager(){
        return $this->get("app.produit.manager");
    }
}