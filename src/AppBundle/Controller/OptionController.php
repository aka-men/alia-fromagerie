<?php
/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 10/04/2017
 * Time: 17:06
 */

namespace AppBundle\Controller;
use AppBundle\Entity\Option;
use AppBundle\Form\OptionType;
use AppBundle\Manager\OptionManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/option")
 */
class OptionController extends Controller
{
    /**
     * @Route("/", name="app_option_index")
     */
    public function indexAction(Request $request) {
        if($request->isMethod("POST") and $request->isXmlHttpRequest()){
            $param_sort = array(
                '',
                'o.id'.
                'o.code',
                'o.label'
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
            foreach ($requestResult["options"] as $option) {
                $class = $option->isArchive()?"btn-danger":'btn-success';
                $title = $option->isArchive()?"Désarchiver":'Archiver';
                $resultat['data'][] = array(
                    "ligne" => $ligne,
                    "id" => $option->getId(),
                    "code" => $option->getCode(),
                    "label" => $option->getLabel(),
                    "action" => '<button data-loading-text=\'<i class="fa fa-spin fa-spinner" aria-hidden="true"></i>\' title="Editer" data-href="'.$this->generateUrl("app_option_edit",array("id"=>$option->getId())).'" data-title="Editer une analyse" class="btn btn-xs btn-primary edit-option"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button><div class="btn-group"><button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button><ul class="dropdown-menu"><li><a data-loading-text=\'Veuillez patienter<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>\' class="btn ntm-link option-toggle-state"  data-href="'.$this->generateUrl("app_option_state_toggle",array("id"=>$option->getId())).'">'.$title.'</a></li><li><a data-loading-text=\'Veuillez patienter<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>\' data-href="'.$this->generateUrl("app_option_remove",array("id"=>$option->getId())).'" class="btn btn-link delete-option">Supprimer</a></li></ul></div>',
                );
                $ligne++;
            }
            return new JsonResponse($resultat);
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }

    /**
     * @Route("/add", name="app_option_add")
     */
    public function addAction(Request $request) {
        $option = $this->getManager()->create();
        $form = $this->createForm(OptionType::class,$option);
        if($request->isMethod("POST") and $request->request->has("appbundle_option") and $request->isXmlHttpRequest()){
            $form->handleRequest($request);
            if($form->isValid()) {
                $this->getManager()->persist($option, true);
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Analyse ajoutée avec succès",
                    "option" =>  $this->get('jms_serializer')->serialize($option,'json')
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>(string)$form->getErrors(true)));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:option:form.html.twig",array(
                "formOption" => $form->createView(),
                "action"=>$this->generateUrl("app_option_add")))
        ));
    }

    /**
     * @Route("/edit/{id}", name="app_option_edit")
     * @ParamConverter("option", class="AppBundle:Option")
     */
    public function editAction($option,Request $request) {
        $form = $this->createForm(OptionType::class,$option);
        if($request->isMethod("POST") and $request->request->has("appbundle_option") and $request->isXmlHttpRequest()){
            $form->handleRequest($request);
            if($form->isValid()){
                $this->getManager()->flush();
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Analyse modifiée avec succès",
                    "option" =>  $this->get('jms_serializer')->serialize($option,'json')
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>(string)$form->getErrors(true)));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:option:form.html.twig",array(
                "formOption" => $form->createView(),
                "action"=>$this->generateUrl("app_option_edit",array("id"=>$option->getId()))))
        ));
    }

    /**
     * @Route("/{id}", name="app_option_get")
     * @ParamConverter("option", class="AppBundle:Option")
     */
    public function getAction($option,Request $request) {
        return new JsonResponse(array(
            "code" => 1,
            "option" =>  $this->get('jms_serializer')->serialize($option,'json')
        ));
    }

    /**
     * @Route("/state_toggle/{id}", name="app_option_state_toggle")
     * @ParamConverter("option", class="AppBundle:Option")
     */
    public function stateToggleAction($option,Request $request) {
        if($request->isXmlHttpRequest() and $request->isMethod('post')){
            $option->setArchive($option->isArchive()?false:true);
            $this->getManager()->flush();
            return new JsonResponse(array(
                'code' => 1,
                'msg' => 'Statut modifié avec succès'
            ));
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }

    /**
     * @Route("/remove/{id}", name="app_option_remove")
     * @ParamConverter("option", class="AppBundle:Option")
     */
    public function removeAction(Option $option,Request $request) {
        if($request->isXmlHttpRequest() and $request->isMethod('post')){
            if((int)$this->get('app.achat.manager')->count(array('option' => $option->getId())) > 0){
                return new JsonResponse(array(
                    "code" => 0,
                    "msg" =>  'Impossible de supprimer un enregistrement lié avec d\'autres !!'
                ));
            }
            $this->getManager()->remove($option,true);
            return new JsonResponse(array(
                'code' => 1,
                'msg' => 'Statut supprimée avec succès'
            ));
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }



    /**
     * @return OptionManager
     */
    private function getManager(){
        return $this->get("app.option.manager");
    }
}