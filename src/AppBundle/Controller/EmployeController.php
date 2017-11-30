<?php
/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 10/04/2017
 * Time: 17:06
 */

namespace AppBundle\Controller;
use AppBundle\Entity\Employe;
use AppBundle\Form\EmployeEditType;
use AppBundle\Form\EmployeType;
use AppBundle\Manager\EmployeManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/employe")
 */
class EmployeController extends Controller
{
    /**
     * @Route("/", name="app_employe_index")
     */
    public function indexAction(Request $request) {
        if($request->isMethod("POST") and $request->isXmlHttpRequest()){
            $param_sort = array(
                '',
                'e.id',
                'e.nom',
                'e.cin',
                'e.email',
                'e.gsm',
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
            foreach ($requestResult["employes"] as $employe) {
                $title = $employe->isArchive()?"Désarchiver":'Archiver';
                $resultat['data'][] = array(
                    "ligne" => $ligne,
                    "id" => $employe->getId(),
                    "nom" => $this->renderView("AppBundle:employe:td.html.twig",array(
                        "employe" => $employe,
                        "td" => "nom"
                    )),
                    "cin" => $employe->getCin(),
                    "email" => $employe->getEmail(),
                    "gsm" => $employe->getGsm(),
                    "documents" => $this->renderView("AppBundle:employe:td.html.twig",array(
                        "employe" => $employe,
                        "td" => "documents"
                    )),
                    "action" => $this->renderView("AppBundle:employe:td.html.twig",array(
                        "employe" => $employe,
                        "td" => "action"
                    )),
                );
                $ligne++;
            }
            return new JsonResponse($resultat);
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }

    /**
     * @Route("/add", name="app_employe_add")
     */
    public function addAction(Request $request) {
        $employe = $this->getManager()->create();
        $form = $this->createForm(EmployeType::class,$employe);
        if($request->isMethod("POST") and $request->request->has("appbundle_employe") and $request->isXmlHttpRequest()){
            $form->handleRequest($request);
            if($form->isValid()) {
                $this->getManager()->persist($employe, true);
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Employé ajouté avec succès",
                    "employe" =>  $this->get('jms_serializer')->serialize($employe,'json')
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>(string)$form->getErrors(true)));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:employe:form.html.twig",array(
                "formEmploye" => $form->createView(),
                "action"=>$this->generateUrl("app_employe_add")
            ))
        ));
    }


    /**
     * @Route("/edit/{id}", name="app_employe_edit")
     * @ParamConverter("employe", class="AppBundle:Employe")
     */
    public function editAction(Employe $employe,Request $request) {
        $form = $this->createForm(EmployeEditType::class,$employe);
        if($request->isMethod("POST") and $request->request->has("appbundle_employe") and $request->isXmlHttpRequest()){
            $form->handleRequest($request);
            if($form->isValid()){
                if($employe->isCommercial()){
                    $commerciale = $employe->getCommerciale();
                    $commerciale
                        ->setAdresse($employe->getAdresse())
                        ->setCin($employe->getCin())
                        ->setNom($employe->getNom())
                        ->setPrenom($employe->getPrenom())
                        ->setEmail($employe->getEmail())
                        ->setGsm($employe->getGsm())
                    ;
                }
				foreach ($request->request->get("deleted_image",array()) as $id){
                    $img = $this->get('app.image.manager')->find($id);
                    !is_null($img) ? $employe->removeImagesDocument($img) : null ;
                    $this->get('app.image.manager')->remove($img);
                }
                $files = $request->files->get("appbundle_employe",array());
                if(array_key_exists('imagesDocuments',$files)){
                    foreach ($files['imagesDocuments'] as $file){
                        $image = $this->get('app.image.manager')->create();
                        $image->setFile($file['file']);
                        $employe->addImagesDocument($image);
                    }
                }
                $this->getManager()->flush();
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Employé modifié avec succès",
                    "employe" =>  $this->get('jms_serializer')->serialize($employe,'json')
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>(string)$form->getErrors(true)));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:employe:form.edit.html.twig",array(
                "formEmploye" => $form->createView(),
                "employe"=>$employe))
        ));
    }

    /**
     * @Route("/state_toggle/{id}", name="app_employe_state_toggle")
     * @ParamConverter("employe", class="AppBundle:Employe")
     */
    public function stateToggleAction($employe,Request $request) {
        if($request->isXmlHttpRequest() and $request->isMethod('post')){
            $employe->setArchive($employe->isArchive()?false:true);
            $this->getManager()->flush();
            return new JsonResponse(array(
                'code' => 1,
                'msg' => 'Statut modifié avec succès'
            ));
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }

    /**
     * @Route("/remove/{id}", name="app_employe_remove")
     * @ParamConverter("employe", class="AppBundle:Employe")
     */
    public function removeAction(Employe $employe,Request $request) {
        if($request->isXmlHttpRequest() and $request->isMethod('post')){
            if((int)$this->get('app.charge.manager')->count(array("employe" => $employe->getId())) > 0 or (int)$this->get('app.commande.manager')->count(array("employe" => $employe->getId())) > 0){
                return new JsonResponse(array(
                    "code" => 0,
                    "msg" =>  'Impossible de supprimer un enregistrement lié avec d\'autres !!'
                ));
            }
            $this->getManager()->remove($employe,true);
            return new JsonResponse(array(
                'code' => 1,
                'msg' => 'Employé supprimé avec succès'
            ));
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }

    /**
     * @return EmployeManager
     */
    private function getManager(){
        return $this->get("app.employe.manager");
    }
}