<?php
/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 10/04/2017
 * Time: 17:06
 */

namespace AppBundle\Controller;
use AppBundle\Entity\Banque;
use AppBundle\Form\BanqueType;
use AppBundle\Manager\BanqueManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/banque")
 */
class BanqueController extends Controller
{
    /**
     * @Route("/", name="app_banque_index")
     */
    public function indexAction(Request $request) {
        if($request->isMethod("POST") and $request->isXmlHttpRequest()){
            $param_sort = array(
                '',
                'b.id'.
                'b.nom',
                'b.code'
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
            foreach ($requestResult["banques"] as $banque) {
                $resultat['data'][] = array(
                    "ligne" => $ligne,
                    "id" => $banque->getId(),
                    "nom" => $banque->getNom(),
                    "code" => $banque->getCode(),
                    "action" => $this->renderView('AppBundle:banque:td.action.html.twig',array(
                        "banque" => $banque
                    ))
                );
                $ligne++;
            }
            return new JsonResponse($resultat);
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }

    /**
     * @Route("/add", name="app_banque_add")
     */
    public function addAction(Request $request) {
        $banque = $this->getManager()->create();
        $form = $this->createForm(BanqueType::class,$banque);
        if($request->isMethod("POST") and $request->request->has("appbundle_banque") and $request->isXmlHttpRequest()){
            $form->handleRequest($request);
            if($form->isValid()) {
                $this->getManager()->persist($banque, true);
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Analyse ajoutée avec succès",
                    "banque" =>  $this->get('jms_serializer')->serialize($banque,'json')
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>(string)$form->getErrors(true)));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:banque:form.html.twig",array(
                "formBanque" => $form->createView(),
                "action"=>$this->generateUrl("app_banque_add")))
        ));
    }

    /**
     * @Route("/edit/{id}", name="app_banque_edit")
     * @ParamConverter("banque", class="AppBundle:Banque")
     */
    public function editAction(Banque $banque,Request $request) {
        $form = $this->createForm(BanqueType::class,$banque);
        if($request->isMethod("POST") and $request->request->has("appbundle_banque") and $request->isXmlHttpRequest()){
            $form->handleRequest($request);
            if($form->isValid()){
                $this->getManager()->flush();
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Banque modifiée avec succès",
                    "banque" =>  $this->get('jms_serializer')->serialize($banque,'json')
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>(string)$form->getErrors(true)));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:banque:form.html.twig",array(
                "formBanque" => $form->createView(),
                "action"=>$this->generateUrl("app_banque_edit",array("id"=>$banque->getId()))))
        ));
    }

    /**
     * @Route("/{id}", name="app_banque_get")
     * @ParamConverter("banque", class="AppBundle:Banque")
     */
    public function getAction(Banque $banque,Request $request) {
        return new JsonResponse(array(
            "code" => 1,
            "banque" =>  $this->get('jms_serializer')->serialize($banque,'json')
        ));
    }

    /**
     * @Route("/state_toggle/{id}", name="app_banque_state_toggle")
     * @ParamConverter("banque", class="AppBundle:Banque")
     */
    public function stateToggleAction(Banque $banque,Request $request) {
        if($request->isXmlHttpRequest() and $request->isMethod('post')){
            $banque->setArchive($banque->isArchive()?false:true);
            $this->getManager()->flush();
            return new JsonResponse(array(
                'code' => 1,
                'msg' => 'Banque modifiée avec succès'
            ));
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }

    /**
     * @Route("/remove/{id}", name="app_banque_remove")
     * @ParamConverter("banque", class="AppBundle:Banque")
     */
    public function removeAction(Banque $banque,Request $request) {
        if($request->isXmlHttpRequest() and $request->isMethod('post')){
            /*if((int)$this->get('app.achat.manager')->count(array('option' => $banque->getId())) > 0){
                return new JsonResponse(array(
                    "code" => 0,
                    "msg" =>  'Impossible de supprimer un enregistrement lié avec d\'autres !!'
                ));
            }*/
            $this->getManager()->remove($banque,true);
            return new JsonResponse(array(
                'code' => 1,
                'msg' => 'Banque supprimée avec succès'
            ));
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }



    /**
     * @return BanqueManager
     */
    private function getManager(){
        return $this->get("app.banque.manager");
    }
}