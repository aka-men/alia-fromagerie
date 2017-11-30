<?php

/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 08/04/2017
 * Time: 11:27
 */
namespace AppBundle\Controller;

use AppBundle\Form\ChargeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Manager\ChargeManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/charges")
 */
class ChargeController extends Controller
{
    /**
     * @Route("/", name="app_charge_index")
     */
    public function indexAction(Request $request) {
        if($request->isXmlHttpRequest() and $request->request->has('columns')){
            $param_sort = array(
                '',
                'c.id',
                'c.date',
                'c.fournisseur',
                'c.typesDepenses',
                'c.montantTtc',
                'c.modePayment',
                'c.numeroCheque',
                'c.dateCheque',
                'c.numeroFacture',
                'c.tva',
                'c.observation'
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
                'recordsTotal' => $requestResult["total"],
                'totals' => $this->renderView('AppBundle:charge:totals.html.twig',array(
                    'totals'=>$requestResult['totals'],
                    'totalGlobal' => $requestResult['totalGlobal']
                ))
            );
            $ligne = 1;
            foreach ($requestResult['charges'] as $charge){
                $types = '';
                if($charge->getTypesDepenses()->count() === 1)
                    $types = '<span class="label label-info">'.$charge->getTypesDepenses()->first()->fullLabel().'</span>';
                else if($charge->getTypesDepenses()->count() > 1){
                    $types = '<span class="label label-info">'.$charge->getTypesDepenses()->first()->fullLabel().'</span>';
                    $title ='<ul>';
                    foreach ($charge->getTypesDepenses() as $type){
                        $title .= '<li>'.$type->fullLabel().'</li>';
                    }
                    $title .= '</ul>';
                    $types .= ' <span data-toggle="tooltip" data-placement="top" data-original-title="'.$title.'" class="label label-info" ><i class="fa fa-plus-square" aria-hidden="true"></i></span>';
                }
                $resultat['data'][] = array(
                    "ligne" => $ligne,
                    "id" => $charge->getId(),
                    "date" => $charge->getDate()->format('d/m/Y'),
                    "fournisseur" => $charge->getFournisseur()?$charge->getFournisseur()->getNom(): null,
                    "type" => $types,
                    "montant" => number_format($charge->getMontantTtc(),2,'.',' '),
                    "mode" => $charge->getModePayment()?$charge->getModePayment()->getLabel():null,
                    "cheque" => $charge->getNumeroCheque().' '.((is_null($charge->getImageCheque()) OR is_null($charge->getNumeroCheque()))? null :'<a style="margin-left:5px;" title="'.$charge->getNumeroCheque().'" data-fancybox href="'.$this->generateUrl("app_images",array("nom" => $charge->getImageCheque()->getNom())).'"><i class="fa fa-eye" aria-hidden="true"></i></a>'),
                    "dateCheque" => $charge->getDateCheque() ? $charge->getDateCheque()->format('d/m/Y') : null,
                    "facture" => $charge->getNumeroFacture(),
                    "tva" => $charge->getTva()?$charge->getTva().'%':null,
                    "observation" => $charge->getObservation(),
                    "action" => $this->renderView("AppBundle:charge:td.action.html.twig",array("charge" => $charge))
                );
                $ligne++;
            }
            return new JsonResponse($resultat);
        }
        return $this->render("AppBundle:charge:index.html.twig",array(
            "modes" => $this->get('app.mode_payment.manager')->findAll(),
            "types" => $this->get('app.type_depense.manager')->findAll(),
            "fournisseurs" => $this->get('app.fournisseur.manager')->findAll(),
            "query" => $request->query->all()
        ));
    }

    /**
     * @Route("/employes", name="app_charge_employes_index")
     */
    public function employesAction(Request $request) {
        if($request->isXmlHttpRequest() and $request->request->has('columns')){
            $param_sort = array(
                '',
                'c.id',
                'c.date',
                'c.typesDepenses',
                'c.montantTtc',
                'c.modePayment',
                'c.numeroCheque',
                'c.dateCheque',
                'c.employe',
                'c.observation'
            );
            $start = $request->request->get("start");
            $length = $request->request->get("length");
            $criteres = $request->request->get("criteres",array());
            $sort = $param_sort[$request->request->get("order")[0]['column']];
            $dir = $request->request->get("order")[0]['dir'];
            $requestResult = $this->getManager()->listeDataTableEmployes($criteres,$sort, $dir, $start, $length);
            $resultat = array(
                "data" => array(),
                'recordsFiltered' => $requestResult["totalFiltred"],
                'recordsTotal' => $requestResult["total"],
                'totals' => $this->renderView('AppBundle:charge:totals.html.twig',array(
                    'totals'=>$requestResult['totals'],
                    'totalGlobal' => $requestResult['totalGlobal']
                    ))
            );
            $ligne = 1;
            foreach ($requestResult['charges'] as $charge){
                $types = '';
                foreach ($charge->getTypesDepenses() as $type){
                    $types .= '<span class="label label-info">'.$type->getLabel().'</span>';
                }
                $resultat['data'][] = array(
                    "ligne" => $ligne,
                    "id" => $charge->getId(),
                    "date" => $charge->getDate()->format('d/m/Y'),
                    "type" => $types,
                    "montant" => number_format($charge->getMontantTtc(),2,'.',' '),
                    "mode" => $charge->getModePayment()?$charge->getModePayment()->getLabel():null,
                    "cheque" => $charge->getNumeroCheque().(is_null($charge->getImageCheque())? null :'<a style="margin-left:5px;" title="'.$charge->getNumeroCheque().'" data-fancybox href="'.$this->generateUrl("app_images",array("nom" => $charge->getImageCheque()->getNom())).'"><i class="fa fa-eye" aria-hidden="true"></i></a>'),
                    "dateCheque" => $charge->getDateCheque() ? $charge->getDateCheque()->format('d/m/Y') : null,
                    "employe" => $charge->getEmploye()?$charge->getEmploye()->getFullName(): null,
                    "observation" => $charge->getObservation(),
                    "action" =>$this->renderView("AppBundle:charge:td.action.html.twig",array("charge" => $charge))
                );
                $ligne++;
            }
            return new JsonResponse($resultat);
        }
        return $this->render("AppBundle:charge:employe.html.twig",array(
            "modes" => $this->get('app.mode_payment.manager')->findAll(),
            "types" => $this->get('app.type_depense.manager')->findAll(),
            "employes" => $this->get('app.employe.manager')->findAll(),
            "query" => $request->query->all()
        ));
    }
    /**
     * @Route("/employes/add", name="app_charge_employe_add")
     */
    public function chargeEmployeAction(Request $request) {
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView('AppBundle:charge:fomr.charges.employes.html.twig',array(
                "modes" => $this->get('app.mode_payment.manager')->findAll(),
                "types" => $this->get('app.type_depense.manager')->findAll(),
                "employes" => $this->get('app.employe.manager')->findAll(),
                "query" => $request->query->all()
            ))
        ));
    }

    /**
     * @Route("/add", name="app_charge_add")
     */
    public function addAction(Request $request) {
        if($request->isXmlHttpRequest() and $request->request->has('appbundle_charge')){
            $inserted = array();
            $loop = 0;
            foreach ($request->request->get('appbundle_charge') as $object){
                $charge = $this->getManager()->create();
                $form = $this->createForm(ChargeType::class,$charge);
                $req = clone $request;
                $req->request->set("appbundle_charge",$object);
                if($request->files->has('appbundle_charge') and array_key_exists($loop,$request->files->get('appbundle_charge')))
                    $req->files->set('appbundle_charge',$request->files->get('appbundle_charge')[$loop]);
                $form->handleRequest($req);
                if($form->isValid()){
                    $charge->setUser($this->getUser());
                    $inserted[] = $charge;
                    $this->getManager()->persist($charge);
                }else{
                    return new JsonResponse(array(
                        "code" => 0,
                        "msg" => (string)$form->getErrors(true)
                    ));
                }
                $loop++;
            }
            $this->getManager()->flush();
            $ids = '<ul style="display: table;">';
            foreach ($inserted as $c){
                $ids .= '<li> Id : '.$c->getId().'</li>';
            }
            $ids .= '</ul>';
            return new JsonResponse(array(
                "code" => 1,
                "msg" => '('.count($inserted).') Charge(s) ajoutée(s) avec succès <br/>'.$ids
            ));
        }
        throw $this->createAccessDeniedException("L'url demandée n'a pu être chargée");
    }


    /**
     * @Route("/autres/add", name="app_charge_autre_add")
     */
    public function chargeAutreAction(Request $request) {
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView('AppBundle:charge:fomr.charges.autres.html.twig',array(
                "modes" => $this->get('app.mode_payment.manager')->findAll(),
                "types" => $this->get('app.type_depense.manager')->findAll(),
                "fournisseurs" => $this->get('app.fournisseur.manager')->findAll(),
                'query' => $request->query->all()
            ))
        ));
    }

    /**
     * @Route("/autres/line/add", name="app_charge_autre_line_add")
     */
    public function chargeAutreLineAction(Request $request) {
        return new JsonResponse(array(
            "code" => 1,
            "line" => $this->renderView('AppBundle:charge:form.charge.autre.line.html.twig',array(
                "modes" => $this->get('app.mode_payment.manager')->findAll(),
                "types" => $this->get('app.type_depense.manager')->findAll(),
                "fournisseurs" => $this->get('app.fournisseur.manager')->findAll(),
                'index' => $request->query->get('index',0)
            ))
        ));
    }

    /**
     * @Route("/edit/{id}", name="app_charge_edit")
     * @ParamConverter("charge", class="AppBundle:Charge")
     */
    public function editAction($charge,Request $request) {
        $form = $this->createForm(ChargeType::class,$charge);
        if($request->isMethod("POST") and $request->request->has("appbundle_charge") and $request->isXmlHttpRequest()){
            $form->handleRequest($request);
            if($form->isValid()){
                if($charge->getImageCheque() and $charge->getImageCheque()->getFile()){
                    if(is_null($charge->getImageCheque()->getId()))
                        $charge->getImageCheque()->preUpload();
                    $charge->getImageCheque()->upload();
                }
                if($charge->getImage() and $charge->getImage()->getFile()){
                    if(is_null($charge->getImage()->getId()))
                        $charge->getImage()->preUpload();
                    $charge->getImage()->upload();
                }
                $this->getManager()->flush();
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Charge modifiée avec succès",
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>(string)$form->getErrors(true)));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:charge:form.html.twig",array(
                "formCharge" => $form->createView(),
                "action"=>$this->generateUrl("app_charge_edit",array("id"=>$charge->getId())),
                'charge' => $charge,
                'types' => $this->get('app.type_depense.manager')->findAll())),
        ));
    }

    /**
     * @Route("/remove/{id}", name="app_charge_remove")
     * @ParamConverter("charge", class="AppBundle:Charge")
     */
    public function removeAction($charge,Request $request) {
        $this->getManager()->remove($charge,true);
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Charge supprimée avec succès",
                ));
    }


    /**
     * @return ChargeManager
     */
    private function getManager(){
        return $this->get('app.charge.manager');
    }
}