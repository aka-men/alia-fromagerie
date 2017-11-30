<?php
/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 10/04/2017
 * Time: 17:06
 */

namespace AppBundle\Controller;
use AppBundle\Entity\Fournisseur;
use AppBundle\Form\FournisseurType;
use AppBundle\Manager\FournisseurManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        if($request->isMethod("POST") and $request->isXmlHttpRequest()){
            $param_sort = array(
                "",
                'f.id',
                'f.nom'
            );
            $start = $request->request->get("start");
            $length = $request->request->get("length");
            $criteres = $request->request->get("criteres",array());
            $sort = $param_sort[$request->request->get("order")[0]['column']];
            $dir = $request->request->get("order")[0]['dir'];
            $requestResult = $this->getManager()->listeDataTableOther($criteres,$sort, $dir, $start, $length);
            $resultat = array(
                "data" => array(),
                'recordsFiltered' => $requestResult["totalFiltred"],
                'recordsTotal' => $requestResult["total"]
            );
            $ligne = 1;
            foreach ($requestResult["fournisseurs"] as $founisseur) {
                $resultat['data'][] = array(
                    "ligne" => $ligne,
                    "id" => $founisseur->getId(),
                    "nom" => $this->renderView("AppBundle:fournisseur:td.html.twig",array(
                        'fournisseur' => $founisseur,
                        'td' => 'nom'
                    )),
                    "action" => $this->renderView("AppBundle:fournisseur:td.html.twig",array(
                        'fournisseur' => $founisseur,
                        'td' => 'action'
                    ))
                );
                $ligne++;
            }
            return new JsonResponse($resultat);
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }
    /**
     * @Route("/matiere-premiere", name="app_fournisseur_matiere_premiere_index")
     */
    public function indexMPAction(Request $request) {
        if($request->isMethod("POST") and $request->isXmlHttpRequest()){
            $param_sort = array(
                "",
                'f.id',
                'f.nom'
            );
            $start = $request->request->get("start");
            $length = $request->request->get("length");
            $criteres = $request->request->get("criteres",array());
            $sort = $param_sort[$request->request->get("order")[0]['column']];
            $dir = $request->request->get("order")[0]['dir'];
            $requestResult = $this->getManager()->listeDataTableMP($criteres,$sort, $dir, $start, $length);
            $resultat = array(
                "data" => array(),
                'recordsFiltered' => $requestResult["totalFiltred"],
                'recordsTotal' => $requestResult["total"]
            );
            $ligne = 1;
            foreach ($requestResult["fournisseurs"] as $founisseur) {
                $resultat['data'][] = array(
                    "ligne" => $ligne,
                    "id" => $founisseur->getId(),
                    "nom" => $this->renderView("AppBundle:fournisseur:td.html.twig",array(
                        'fournisseur' => $founisseur,
                        'td' => 'nom'
                    )),
                    "action" => $this->renderView("AppBundle:fournisseur:td.html.twig",array(
                        'fournisseur' => $founisseur,
                        'td' => 'action'
                    ))
                );
                $ligne++;
            }
            return new JsonResponse($resultat);
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }
    /**
     * @Route("/produits-importation", name="app_fournisseur_Produit_importation_index")
     */
    public function indexPIAction(Request $request) {
        if($request->isMethod("POST") and $request->isXmlHttpRequest()){
            $param_sort = array(
                "",
                'f.id',
                'f.nom'
            );
            $start = $request->request->get("start");
            $length = $request->request->get("length");
            $criteres = $request->request->get("criteres",array());
            $sort = $param_sort[$request->request->get("order")[0]['column']];
            $dir = $request->request->get("order")[0]['dir'];
            $requestResult = $this->getManager()->listeDataTablePI($criteres,$sort, $dir, $start, $length);
            $resultat = array(
                "data" => array(),
                'recordsFiltered' => $requestResult["totalFiltred"],
                'recordsTotal' => $requestResult["total"]
            );
            $ligne = 1;
            foreach ($requestResult["fournisseurs"] as $founisseur) {
                $title = $founisseur->isArchive()?"Désarchiver":'Archiver';
                $resultat['data'][] = array(
                    "ligne" => $ligne,
                    "id" => $founisseur->getId(),
                    "nom" => $this->renderView("AppBundle:fournisseur:td.html.twig",array(
                        'fournisseur' => $founisseur,
                        'td' => 'nom'
                    )),
                    "action" => $this->renderView("AppBundle:fournisseur:td.html.twig",array(
                        'fournisseur' => $founisseur,
                        'td' => 'action'
                    ))
                );
                $ligne++;
            }
            return new JsonResponse($resultat);
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }

    /**
     * @Route("/add", name="app_fournisseur_add")
     */
    public function addAction(Request $request) {
        $fournisseur = $this->getManager()->create();
        $form = $this->createForm(FournisseurType::class,$fournisseur);
        if($request->isMethod("POST") and $request->request->has("appbundle_fournisseur") and $request->isXmlHttpRequest()){
            $form->handleRequest($request);
            if($form->isValid()) {
                $this->getManager()->persist($fournisseur, true);
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Fournisseur ajouté avec succès",
                    "fournisseur" =>  $this->get('jms_serializer')->serialize($fournisseur,'json')
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>(string)$form->getErrors(true)));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:fournisseur:form.html.twig",array(
                "formFournisseur" => $form->createView(),
                "action"=>$this->generateUrl("app_fournisseur_add"),
                "params" => $request->query->all()
                ))
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
                $this->getManager()->flush();
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Fournisseur modifié avec succès",
                    "fournisseur" =>  $this->get('jms_serializer')->serialize($fournisseur,'json')
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>(string)$form->getErrors(true)));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:fournisseur:form.html.twig",array(
                "formFournisseur" => $form->createView(),
                "action"=>$this->generateUrl("app_fournisseur_edit",array("id"=>$fournisseur->getId())),
                "params" => $request->query->all()
            ))
        ));
    }

    /**
     * @Route("/{id}", name="app_fournisseur_get")
     * @ParamConverter("fournisseur", class="AppBundle:Fournisseur")
     */
    public function getAction($fournisseur,Request $request) {
       return new JsonResponse(array(
                    "code" => 1,
                    "fournisseur" =>  $this->get('jms_serializer')->serialize($fournisseur,'json')
                ));
     }

    /**
     * @Route("/remove/{id}", name="app_fournisseur_remove")
     * @ParamConverter("fournisseur", class="AppBundle:Fournisseur")
     */
    public function removeAction(Fournisseur $fournisseur,Request $request) {
        if((int)$this->get('app.charge.manager')->count(array("fournisseur" => $fournisseur->getId())) > 0 or (int)$this->get('app.achat.manager')->count(array("fournisseur" => $fournisseur->getId())) > 0){
            return new JsonResponse(array(
                "code" => 0,
                "msg" =>  'Impossible de supprimer un enregistrement lié avec d\'autres !!'
            ));
        }
        $this->getManager()->remove($fournisseur,true);
        return new JsonResponse(array(
            "code" => 1,
            "msg" =>  'Fournisseur supprimé avec succès'
        ));
    }

    /**
     * @Route("/state_toggle/{id}", name="app_fournisseur_state_toggle")
     * @ParamConverter("fournisseur", class="AppBundle:Fournisseur")
     */
    public function stateToggleAction($fournisseur,Request $request) {
        if($request->isXmlHttpRequest() and $request->isMethod('post')){
            $fournisseur->setArchive($fournisseur->isArchive()?false:true);
            $this->getManager()->flush();
            return new JsonResponse(array(
                'code' => 1,
                'msg' => 'Statut modifié avec succès'
            ));
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }

    /**
     * @Route("/{id}/types/collection", name="app_fournisseur_types_collection")
     * @ParamConverter("fournisseur", class="AppBundle:Fournisseur")
     */
    public function typesCollectionAction(Fournisseur $fournisseur,Request $request) {
        if($request->isMethod("POST") and $request->request->has("types") and $request->isXmlHttpRequest()){
            $errors = '';
            $types = is_array($request->request->get('types')) ? $request->request->get('types') : array();
            foreach ($fournisseur->getTypesDepenses() as $type){
                if(!in_array($type->getId(),$types)){
                    $nbrCharge = $this->get('app.charge.manager')->count(array(
                        'type' => $type->getId(),
                        'fournisseur' => $fournisseur->getId()
                    ));
                    if((int) $nbrCharge > 0){
                        $errors .= '<br/>'.$type->getLabel();
                    }else{
                        $fournisseur->removeTypesDepense($type);
                        $type->removeFournisseur($fournisseur);
                    }
                }
            }
            if(strlen($errors) > 0){
                return new JsonResponse(array("code"=>0,"msg"=>"Vous ne pouve pas détacher les designations suivantes : <br/>".$errors));
            }
            foreach ($types as $id){
                $type = $this->get('app.type_depense.manager')->find($id);
                if($type and !$fournisseur->getTypesDepenses()->contains($type)){
                    $fournisseur->addTypesDepense($type);
                    $type->addFournisseur($fournisseur);
                }
            }
            $this->getManager()->flush();
            return new JsonResponse(array("code"=>1,"msg"=>"Modifications Enregistrées avec succès"));
        }
        return new JsonResponse(array(
            "code" => 1,
            "types" => $this->renderView("AppBundle:fournisseur:types_collection.html.twig",array(
                "fournisseur"=> $fournisseur,
                "types" => $this->get('app.type_depense.manager')->findBy(array('parent' => null))
            )),
        ));
    }


    /**
     * @Route("/{id}/produits/collection", name="app_fournisseur_produits_collection")
     * @ParamConverter("fournisseur", class="AppBundle:Fournisseur")
     */
    public function produitsCollectionAction(Fournisseur $fournisseur,Request $request) {
        if($request->isMethod("POST") and $request->request->has("produits") and $request->isXmlHttpRequest()){
            $produits = is_array($request->request->get('produits')) ? $request->request->get('produits') : array();
            $errors = '';
            foreach ($fournisseur->getProduits() as $produit){
                if(!in_array($produit->getId(),$produits)){
                    $nbrCharge = $this->get('app.charge.manager')->count(array(
                        'produit' => $produit->getId(),
                        'fournisseur' => $fournisseur->getId()
                    ));
                    if((int) $nbrCharge > 0){
                        $errors .= '<br/>'.$produit->getTitre();
                    }else{
                        $fournisseur->removeProduit($produit);
                        $produit->removeFournisseur($fournisseur);
                    }
                }
            }
            if(strlen($errors) > 0){
                return new JsonResponse(array("code"=>0,"msg"=>"Vous ne pouve pas détacher les produits suivante : <br/>".$errors));
            }
            foreach ($produits as $id){
                $produit = $this->get('app.produit.manager')->find($id);
                if($produit and !$fournisseur->getProduits()->contains($produit)){
                    $fournisseur->addProduit($produit);
                    $produit->addFournisseur($fournisseur);
                }
            }
            $this->getManager()->flush();
            return new JsonResponse(array("code"=>1,"msg"=>"Modifications Enregistrées avec succès"));
        }
        return new JsonResponse(array(
            "code" => 1,
            "types" => $this->renderView("AppBundle:fournisseur:produits_collection.html.twig",array(
                "fournisseur"=> $fournisseur,
                "produits" => $this->get('app.produit.manager')->getProduitForAchat()
            )),
        ));
    }

    /**
     * @return FournisseurManager
     */
    private function getManager(){
        return $this->get("app.fournisseur.manager");
    }
}