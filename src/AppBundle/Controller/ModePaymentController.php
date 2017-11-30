<?php
/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 10/04/2017
 * Time: 17:06
 */

namespace AppBundle\Controller;
use AppBundle\Entity\ModePayment;
use AppBundle\Form\ModePaymentType;
use AppBundle\Manager\ModePaymentManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/ModePayment")
 */
class ModePaymentController extends Controller
{
    /**
     * @Route("/", name="app_mode_payment_index")
     */
    public function indexAction(Request $request) {
        if($request->isMethod("POST") and $request->isXmlHttpRequest()){
            $param_sort = array(
                '',
                'm.id',
                'm.label'
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
            foreach ($requestResult["modes"] as $mode) {
                $class = $mode->isArchive()?"btn-danger":'btn-success';
                $title = $mode->isArchive()?"Désarchiver":'Archiver';
                $resultat['data'][] = array(
                    "ligne" => $ligne,
                    "id" => $mode->getId(),
                    "label" => $mode->getLabel(),
                    "action" => '<button data-loading-text=\'<i class="fa fa-spin fa-spinner" aria-hidden="true"></i>\' title="Editer" data-href="'.$this->generateUrl("app_mode_payment_edit",array("id"=>$mode->getId())).'" data-title="Editer un mode de paiement" class="btn btn-xs btn-primary edit-mode"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button><div class="btn-group"><button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button><ul class="dropdown-menu"><li><a data-loading-text=\'Veuillez patienter<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>\' class="btn ntm-link mode-toggle-state"  data-href="'.$this->generateUrl("app_mode_payment_state_toggle",array("id"=>$mode->getId())).'">'.$title.'</a></li><li><a data-loading-text=\'Veuillez patienter<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>\' data-href="'.$this->generateUrl("app_mode_payment_remove",array("id"=>$mode->getId())).'" class="btn btn-link delete-mode">Supprimer</a></li></ul></div>',
                );
                $ligne++;
            }
            return new JsonResponse($resultat);
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }

    /**
     * @Route("/add", name="app_mode_payment_add")
     */
    public function addAction(Request $request) {
        $mode = $this->getManager()->create();
        $form = $this->createForm(ModePaymentType::class,$mode);
        if($request->isMethod("POST") and $request->request->has("appbundle_modepayment") and $request->isXmlHttpRequest()){
            $form->handleRequest($request);
            if($form->isValid()) {
                $this->getManager()->persist($mode, true);
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Mode de paiement ajouté avec succès",
                    "mode" =>  $this->get('jms_serializer')->serialize($mode,'json')
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>(string)$form->getErrors(true)));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:mode_payment:form.html.twig",array(
                "formMode" => $form->createView(),
                "action"=>$this->generateUrl("app_mode_payment_add")))
        ));
    }

    /**
     * @Route("/edit/{id}", name="app_mode_payment_edit")
     * @ParamConverter("mode", class="AppBundle:ModePayment")
     */
    public function editAction($mode,Request $request) {
        $form = $this->createForm(ModePaymentType::class,$mode);
        if($request->isMethod("POST") and $request->request->has("appbundle_modepayment") and $request->isXmlHttpRequest()){
            $form->handleRequest($request);
            if($form->isValid()){
                $this->getManager()->flush();
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Mode de paiement modifié avec succès",
                    "mode" =>  $this->get('jms_serializer')->serialize($mode,'json')
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>(string)$form->getErrors(true)));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:mode_payment:form.html.twig",array(
                "formMode" => $form->createView(),
                "action"=>$this->generateUrl("app_mode_payment_edit",array("id"=>$mode->getId()))))
        ));
    }

    /**
     * @Route("/state_toggle/{id}", name="app_mode_payment_state_toggle")
     * @ParamConverter("mode", class="AppBundle:ModePayment")
     */
    public function stateToggleAction($mode,Request $request) {
        if($request->isXmlHttpRequest() and $request->isMethod('post')){
            $mode->setArchive($mode->isArchive()?false:true);
            $this->getManager()->flush();
            return new JsonResponse(array(
                'code' => 1,
                'msg' => 'Statut modifié avec succès'
            ));
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }

    /**
     * @Route("/remove/{id}", name="app_mode_payment_remove")
     * @ParamConverter("mode", class="AppBundle:ModePayment")
     */
    public function removeAction(ModePayment $mode,Request $request) {
        if((int) $this->get('app.achat.manager')->count(array("mode" => $mode->getId())) > 0 or (int) $this->get('app.charge.manager')->count(array("mode" => $mode->getId())) > 0 ){
            return new JsonResponse(array(
                "code" => 0,
                "msg" =>  'Impossible de supprimer un enregistrement lié avec d\'autres !!'
            ));
        }
       $this->getManager()->remove($mode,true);
        return new JsonResponse(array(
            "code" => 1,
            "msg" =>  'Mode supprimé avec succès'
        ));
    }

    /**
     * @Route("/{id}", name="app_mode_payment_get")
     * @ParamConverter("mode", class="AppBundle:ModePayment")
     */
    public function getAction($mode,Request $request) {
        return new JsonResponse(array(
            "code" => 1,
            "mode" =>  $this->get('jms_serializer')->serialize($mode,'json')
        ));
    }

    /**
     * @return ModePaymentManager
     */
    private function getManager(){
        return $this->get("app.mode_payment.manager");
    }
}