<?php
/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 10/04/2017
 * Time: 17:06
 */

namespace AppBundle\Controller;
use AppBundle\Entity\Fournisseur;
use AppBundle\Entity\TypeDepense;
use AppBundle\Form\TypeDepenseType;
use AppBundle\Manager\TypeDepenseManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/typeDepense")
 */
class TypeDepenseController extends Controller
{
    /**
     * @Route("/", name="app_type_depense_index")
     */
    public function indexAction(Request $request) {
        if($request->isMethod("POST") and $request->isXmlHttpRequest()){
            $param_sort = array(
                '',
                't.id',
                't.label'
            );
            $start = $request->request->get("start");
            $length = $request->request->get("length");
            $criteres = $request->request->get("criteres",array());
            $sort = $param_sort[$request->request->get("order")[0]['column']];
            $dir = $request->request->get("order")[0]['dir'];
            $requestResult = $this->getManager()->listeDataTable($criteres,$sort, $dir, $start, $length);
            $resultat = array(
                "data" => array(),
                'recordsFiltered' => $requestResult["totalFiltred"],
                'recordsTotal' => $requestResult["total"]
            );
            $ligne = 1;
            foreach ($requestResult["types"] as $type) {
                $class = $type->isArchive()?"btn-danger":'btn-success';
                $title = $type->isArchive()?"Désarchiver":'Archiver';
                $fournisseurs = '';
                foreach ($type->getFournisseurs() as $fournisseur){
                    $fournisseurs .= '<span data-href="'.$this->generateUrl("app_fournisseur_get",array("id" => $fournisseur->getId())).'" class="label label-info pointer get-fournisseur">'.$fournisseur->getNom().'</span> ';
                }
                $resultat['data'][] = array(
                    "ligne" => $ligne,
                    "id" => $type->getId(),
                    "label" => $type->getLabel().' <span class="badge badge-info">'.$type->getChildrens()->count().'</span>',
                    "fournisseurs" => $fournisseurs,
                    "action" => '<button data-loading-text=\'<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>\' class="btn btn-table  btn-primary edit-type" data-title="Editer une designation" title="Editer" data-href="'.$this->generateUrl("app_type_depense_edit",array("id"=>$type->getId())).'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button><button data-loading-text=\'<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>\' class="btn btn-table  btn-info get-sous-type" title="Liste sous libellé" data-href="'.$this->generateUrl("app_type_depense_liste_by_parent",array("id"=>$type->getId())).'"><i class="fa fa-list" aria-hidden="true"></i></button><button data-loading-text=\'<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>\' class="btn btn-table  btn-info atache-detache-fournisseurs-to-type" title="Liste fournisseurs" data-href="'.$this->generateUrl("app_type_depense_fournisseurs_collection",array("id"=>$type->getId())).'"><i class="fa fa-address-book-o" aria-hidden="true"></i></button><div class="btn-group"><button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button><ul class="dropdown-menu"><li><a data-loading-text=\'Veuillez patienter<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>\' class="btn ntm-link type-toggle-state"  data-href="'.$this->generateUrl("app_type_depense_state_toggle",array("id"=>$type->getId())).'">'.$title.'</a></li><li><a data-loading-text=\'Veuillez patienter<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>\' data-href="'.$this->generateUrl("app_type_depense_remove",array("id"=>$type->getId())).'" class="btn btn-link delete-type">Supprimer</a></li></ul></div>',
                );
                $ligne++;
            }
            return new JsonResponse($resultat);
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }

    /**
     * @Route("/mensuelle", name="app_type_depense_mensuelle_index")
     */
    public function indexMensuelleAction(Request $request) {
        if($request->isMethod("POST") and $request->isXmlHttpRequest()){
            $param_sort = array(
                '',
                't.label'
            );
            $start = $request->request->get("start");
            $length = $request->request->get("length");
            $criteres = $request->request->get("criteres",array());
            $sort = $param_sort[$request->request->get("order")[0]['column']];
            $dir = $request->request->get("order")[0]['dir'];
            $requestResult = $this->getManager()->listeMensuelleDataTable($criteres,$sort, $dir, $start, $length);
            $resultat = array(
                "data" => array(),
                'recordsFiltered' => $requestResult["totalFiltred"],
                'recordsTotal' => $requestResult["total"]
            );
            $ligne = 1;
            foreach ($requestResult["types"] as $type) {
                $resultat['data'][] = array(
                    "ligne" => $ligne,
                    "label" => $type->fullLabel()
                );
                $ligne++;
            }
            return new JsonResponse($resultat);
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }

    /**
     * @Route("/mensuelle/non-paye", name="app_type_depense_mensuelle_non_paye_index")
     */
    public function indexMensuelleNonPayeAction(Request $request) {
        if($request->isMethod("POST") and $request->isXmlHttpRequest()){
            $param_sort = array(
                '',
                't.label'
            );
            $start = $request->request->get("start");
            $length = $request->request->get("length");
            $sort = $param_sort[$request->request->get("order")[0]['column']];
            $dir = $request->request->get("order")[0]['dir'];
            $requestResult = $this->getManager()->listeMensuelleNonPayeDataTable($this->getUser(),$sort, $dir, $start, $length);
            $resultat = array(
                "data" => array(),
                'recordsFiltered' => $requestResult["totalFiltred"],
                'recordsTotal' => $requestResult["total"]
            );
            $ligne = 1;
            foreach ($requestResult["types"] as $type) {
                $resultat['data'][] = array(
                    "ligne" => $ligne,
                    "label" => $type->fullLabel(),
                    "action" => $this->renderView("AppBundle:type_depense:mensuelle_non_paye.td.html.twig",array(
                        'type' => $type,
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
     * @Route("/mensuelle_select", name="app_type_depense_mensuelle_select")
     */
    public function mensuelleToggleAction(Request $request) {
        if($request->isMethod("POST") and $request->request->has("types_mensuelles") and $request->isXmlHttpRequest()){
            $allTypes = $this->getManager()->findAll();
            $selectedTypes = is_array($request->request->get('types_mensuelles',array())) ? $request->request->get('types_mensuelles',array()) : array();
            foreach ($allTypes as $type){
                if(in_array($type->getId(),$selectedTypes))
                    $type->setMensuelle(true);
                else
                    $type->setMensuelle(false);
            }
            $this->getManager()->flush();
            return new JsonResponse(array(
                "code" => 1,
                "msg" => "Modification enregistrée avec succès"
            ));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:type_depense:mensuelles.html.twig",array(
                "types" => $this->getManager()->findAll(),
                "action"=>$this->generateUrl("app_type_depense_mensuelle_select")
            )),

        ));
    }

    /**
     * @Route("/employe", name="app_type_depense_employe_index")
     */
    public function indexEmployeAction(Request $request) {
        if($request->isMethod("POST") and $request->isXmlHttpRequest()){
            $param_sort = array(
                '',
                't.id',
                't.label'
            );
            $start = $request->request->get("start");
            $length = $request->request->get("length");
            $criteres = $request->request->get("criteres",array());
            $criteres['forEmploye'] = 1;
            $sort = $param_sort[$request->request->get("order")[0]['column']];
            $dir = $request->request->get("order")[0]['dir'];
            $requestResult = $this->getManager()->listeDataTable($criteres,$sort, $dir, $start, $length);
            $resultat = array(
                "data" => array(),
                'recordsFiltered' => $requestResult["totalFiltred"],
                'recordsTotal' => $requestResult["total"]
            );
            $ligne = 1;
            foreach ($requestResult["types"] as $type) {
                $resultat['data'][] = array(
                    "ligne" => $ligne,
                    "id" => $type->getId(),
                    "label" => $type->getLabel(),
                    "action" => $this->renderView("AppBundle:type_depense:td.action.html.twig",array(
                        "type" => $type
                    )),
                );
                $ligne++;
            }
            return new JsonResponse($resultat);
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }

    /**
     * @Route("/employe/add", name="app_type_depense_employe_add")
     */
    public function addEmployeAction(Request $request) {
        $type = $this->getManager()->create();
        $form = $this->createForm(TypeDepenseType::class,$type);
        if($request->isMethod("POST") and $request->request->has("appbundle_typedepense") and $request->isXmlHttpRequest()){
            $form->handleRequest($request);
            if($form->isValid()) {
                $type->setForEmploye(true);
                $this->getManager()->persist($type, true);
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Designation ajoutée avec succès",
                    "type" =>  $this->get('jms_serializer')->serialize($type,'json')
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>(string)$form->getErrors(true)));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:type_depense:form.html.twig",array(
                "formType" => $form->createView(),
                "action"=>$this->generateUrl("app_type_depense_employe_add"),
                "params" => $request->query->all()
            )),

        ));
    }


    /**
     * @Route("/add", name="app_type_depense_add")
     */
    public function addAction(Request $request) {
        $type = $this->getManager()->create();
        $form = $this->createForm(TypeDepenseType::class,$type);
        if($request->isMethod("POST") and $request->request->has("appbundle_typedepense") and $request->isXmlHttpRequest()){
            $form->handleRequest($request);
            if($form->isValid()) {
                $this->getManager()->persist($type, true);
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Designation ajoutée avec succès",
                    "type" =>  $this->get('jms_serializer')->serialize($type,'json')
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>(string)$form->getErrors(true)));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:type_depense:form.html.twig",array(
                "formType" => $form->createView(),
                "action"=>$this->generateUrl("app_type_depense_add"),
                "params" => $request->query->all()
            )),

        ));
    }


    /**
     * @Route("/add/collection", name="app_type_depense_add_collection")
     */
    public function addCollectionAction(Request $request) {
        if($request->isMethod("POST") and $request->request->has("appbundle_typedepense") and $request->isXmlHttpRequest()){
           $errors = '';
            foreach ($request->request->get('appbundle_typedepense') as $object){
               $type = $this->getManager()->create();
               $form = $this->createForm(TypeDepenseType::class,$type);
               $req = clone $request;
               $req->request->set('appbundle_typedepense',$object);
               $form->handleRequest($req);
                if($form->isValid()){
                    $this->getManager()->persist($type);
                }else
                    $errors .= (string)$form->getErrors(true);
            }
            if(strlen($errors) === 0){
                $this->getManager()->flush();
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Designation(s) ajoutée(s) avec succès"
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>$errors));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:type_depense:form_collection.html.twig",array(
                "action"=>$this->generateUrl("app_type_depense_add_collection"),
                "params" => $request->request->all()
            )),
        ));
    }

    /**
     * @Route("/edit/{id}", name="app_type_depense_edit")
     * @ParamConverter("type", class="AppBundle:TypeDepense")
     */
    public function editAction(TypeDepense $type,Request $request) {
        if($type->getForEmploye() and !$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))
            throw $this->createAccessDeniedException("Vous n'avez pas le droit d'efféctuer cette opération!");
        $form = $this->createForm(TypeDepenseType::class,$type);
        if($request->isMethod("POST") and $request->request->has("appbundle_typedepense") and $request->isXmlHttpRequest()){
            $form->handleRequest($request);
            if($form->isValid()){
                $this->getManager()->flush();
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Designation modifiée avec succès",
                    "type" =>  $this->get('jms_serializer')->serialize($type,'json')
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>(string)$form->getErrors(true)));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:type_depense:form.html.twig",array(
                "formType" => $form->createView(),
                "action"=>$this->generateUrl("app_type_depense_edit",array("id"=>$type->getId())),
                "params" => $request->query->all()
                ))
        ));
    }

    /**
     * @Route("/{id}", name="app_type_depense_get")
     * @ParamConverter("type", class="AppBundle:TypeDepense")
     */
    public function getAction($type,Request $request) {
        return new JsonResponse(array(
            "code" => 1,
            "type" =>  $this->get('jms_serializer')->serialize($type,'json')
        ));
    }

    /**
     * @Route("unlink/{type}/{fournisseur}", name="app_type_depense_fournisseur_delete")
     * @ParamConverter("type", class="AppBundle:TypeDepense", options={"id" = "type"})
     * @ParamConverter("fournisseur", class="AppBundle:Fournisseur", options={"id" = "fournisseur"})
     */
    public function removeTypeFournisseurAction(TypeDepense $type,Fournisseur $fournisseur,Request $request) {
        if($type->getFournisseurs()->contains($fournisseur)){
            $nbrCharge = $this->get('app.charge.manager')->count(array(
                'type' => $type->getId(),
                'fournisseur' => $fournisseur->getId()
            ));
            if((int) $nbrCharge > 0){
                return new JsonResponse(array(
                    "code" => 0,
                    "type" =>  'Vous ne pouvez pas détacher cette designation,car elle est liée à '.(int) $nbrCharge.' charge(s)'
                ));
            }
           $type->removeFournisseur($fournisseur);
           $fournisseur->removeTypesDepense($type);
           $this->getManager()->flush();
        }
        return new JsonResponse(array(
            "code" => 1,
            'msg' => 'Designation déttachée avec succès'
        ));
    }

    /**
     * @Route("link/{type}/{fournisseur}", name="app_type_depense_fournisseur_add")
     * @ParamConverter("type", class="AppBundle:TypeDepense", options={"id" = "type"})
     * @ParamConverter("fournisseur", class="AppBundle:Fournisseur", options={"id" = "fournisseur"})
     */
    public function addTypeFournisseurAction(TypeDepense $type,Fournisseur $fournisseur,Request $request) {
        if(!$type->getFournisseurs()->contains($fournisseur)){
            $type->addFournisseur($fournisseur);
            $this->getManager()->flush();
        }
        return new JsonResponse(array(
            "code" => 1,
            'msg' => 'Designation attachée avec succès'
        ));
    }


    /**
     * @Route("/remove/{id}", name="app_type_depense_remove")
     * @ParamConverter("type", class="AppBundle:TypeDepense")
     */
    public function removeAction(TypeDepense $type,Request $request) {
        if($type->getForEmploye() and !$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))
            throw $this->createAccessDeniedException("Vous n'avez pas le droit d'efféctuer cette opération!");
        $nbrCharge = $this->get('app.charge.manager')->count(array('type' => $type->getId()));
        if((int) $nbrCharge > 0 or $type->getChildrens()->count() > 0){
            return new JsonResponse(array(
                "code" => 0,
                "msg" =>  'Impossible de supprimer un enregistrement lié avec d\'autres !!'
            ));
        }
        $this->getManager()->remove($type,true);
        return new JsonResponse(array(
            "code" => 1,
            "msg" =>  'Designation supprimée avec succès'
        ));
    }


    /**
     * @Route("/getByFournisseur/{id}", name="app_type_depense_liste_by_fournisseur")
     * @ParamConverter("fournisseur", class="AppBundle:Fournisseur")
     */
    public function getByFournisseurAction($fournisseur,Request $request) {
        $html = $this->renderView("AppBundle:type_depense:liste.html.twig",array(
            'fournisseur' => $fournisseur
        ));
        return new JsonResponse(array(
            "code" => 1,
            "html" =>  $html
        ));
    }

    /**
     * @Route("/getByParent/{id}", name="app_type_depense_liste_by_parent")
     * @ParamConverter("type", class="AppBundle:TypeDepense")
     */
    public function getByParentAction($type,Request $request) {
        $html = $this->renderView("AppBundle:type_depense:liste.html.twig",array(
            'type' => $type,
            'params' => $request->query->all()
        ));
        return new JsonResponse(array(
            "code" => 1,
            "html" =>  $html
        ));
    }

    /**
     * @Route("/state_toggle/{id}", name="app_type_depense_state_toggle")
     * @ParamConverter("type", class="AppBundle:TypeDepense")
     */
    public function stateToggleAction($type,Request $request) {
        if($type->getForEmploye() and !$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN'))
            throw $this->createAccessDeniedException("Vous n'avez pas le droit d'efféctuer cette opération!");
        if($request->isXmlHttpRequest() and $request->isMethod('post')){
            $type->setArchive($type->isArchive()?false:true);
            $this->getManager()->flush();
            return new JsonResponse(array(
                'code' => 1,
                'msg' => 'Statut modifié avec succès'
            ));
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }

    /**
     * @Route("/liste/options", name="app_type_depense_liste")
     */
   public function listeAction(Request $request) {
        if($request->isXmlHttpRequest() and $request->isMethod('post')){
           $fournisseur = $request->request->get('fournisseur_id',null);
           $types = $this->getManager()->listeOption(array('fournisseur' => $fournisseur));
           return new JsonResponse(array(
                'code' => 1,
                'types' => $types
            ));
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }

    /**
     * @Route("/{id}/fournisseurs/collection", name="app_type_depense_fournisseurs_collection")
     * @ParamConverter("type", class="AppBundle:TypeDepense")
     */
    public function fournisseursCollectionAction(TypeDepense $type,Request $request) {
        if($request->isMethod("POST") and $request->request->has("fournisseurs") and $request->isXmlHttpRequest()){
            $errors = '';
            $fournisseurs = is_array($request->request->get('fournisseurs')) ? $request->request->get('fournisseurs') : array();
            foreach ($type->getFournisseurs() as $fournisseur){
                if(!in_array($fournisseur->getId(),$fournisseurs)){
                    $nbrCharge = $this->get('app.charge.manager')->count(array(
                        'type' => $type->getId(),
                        'fournisseur' => $fournisseur->getId()
                    ));
                    if((int) $nbrCharge > 0){
                        $errors .= '<br/>'.$fournisseur->getNom();
                    }else{
                        $fournisseur->removeTypesDepense($type);
                        $type->removeFournisseur($fournisseur);
                    }
                }
            }
            if(strlen($errors) > 0){
                return new JsonResponse(array("code"=>0,"msg"=>"Vous ne pouve pas détacher les fournisseurs suivants : <br/>".$errors));
            }
            foreach ($fournisseurs as $id){
                $fournisseur = $this->get('app.fournisseur.manager')->find($id);
                if($fournisseur and !$type->getFournisseurs()->contains($fournisseur)){
                    $fournisseur->addTypesDepense($type);
                    $type->addFournisseur($fournisseur);
                }
            }
            $this->getManager()->flush();
            return new JsonResponse(array("code"=>1,"msg"=>"Modifications Enregistrées avec succès"));
        }
        return new JsonResponse(array(
            "code" => 1,
            "fournisseurs" => $this->renderView("AppBundle:type_depense:fournisseurs_collection.html.twig",array(
                "type"=> $type,
                "fournisseurs" => $this->get('app.fournisseur.manager')->findAll()
            )),
        ));
    }


    /**
     * @return TypeDepenseManager
     */
    private function getManager(){
        return $this->get("app.type_depense.manager");
    }
}