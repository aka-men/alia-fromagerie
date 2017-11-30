<?php
/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 10/04/2017
 * Time: 17:06
 */

namespace AppBundle\Controller;
use AppBundle\Entity\Entreprise;
use AppBundle\Form\EntrepriseType;
use AppBundle\Manager\EntrepriseManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/entreprise")
 */
class EntrepriseController extends Controller
{
    /**
     * @Route("/", name="app_entreprise_index")
     */
    public function indexAction(Request $request) {
        if($request->isMethod("POST") and $request->isXmlHttpRequest()){
            $param_sort = array(
                "",
                'e.id',
                'e.nom',
                'e.email',
                'e.phone',
                'e.fax',
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
            foreach ($requestResult["entreprises"] as $entreprise) {
                $resultat['data'][] = array(
                    "ligne" => $ligne,
                    "id" => $entreprise->getId(),
                    "nom" => $this->renderView('AppBundle:entreprise:td.action.html.twig',array(
                        "entreprise" => $entreprise,
                        "td" => "nom"
                    )),
                    "email" => $entreprise->getEmail(),
                    "phone" => $entreprise->getPhone(),
                    "fax" => $entreprise->getFax(),
                    "action" => $this->renderView('AppBundle:entreprise:td.action.html.twig',array(
                        "entreprise" => $entreprise,
                        "td" => "action"
                    )),
                 );
                $ligne++;
            }
            return new JsonResponse($resultat);
        }
        return $this->render("AppBundle:entreprise:index.html.twig");
    }
    /**
     * @Route("/add", name="app_entreprise_add")
     */
    public function addAction(Request $request) {
        $entreprise = $this->getManager()->create();
        $form = $this->createForm(EntrepriseType::class,$entreprise);
        if($request->isMethod("POST") and $request->request->has("appbundle_entreprise") and $request->isXmlHttpRequest()){
            $form->handleRequest($request);
            if($form->isValid()) {
                $this->getManager()->persist($entreprise, true);
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Entreprise ajoutée avec succès",
                    "entreprise" =>  $this->get('jms_serializer')->serialize($entreprise,'json')
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>(string)$form->getErrors(true)));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:entreprise:form.html.twig",array(
                "formEntreprise" => $form->createView(),
                "action"=>$this->generateUrl("app_entreprise_add")
                ))
        ));
    }

    /**
     * @Route("/edit/{id}", name="app_entreprise_edit")
     * @ParamConverter("entreprise", class="AppBundle:Entreprise")
     */
    public function editAction(Entreprise $entreprise,Request $request) {
        $form = $this->createForm(EntrepriseType::class,$entreprise);
         if($request->isMethod("POST") and $request->request->has("appbundle_entreprise")){
            $form->handleRequest($request);
            if($form->isValid()){
                //$entreprise->autoExecut();
                $this->getManager()->flush();
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Entreprise modifiée avec succès",
                    "entreprise" =>  $this->get('jms_serializer')->serialize($entreprise,'json')
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>(string)$form->getErrors(true)));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:entreprise:form.html.twig",array(
                "formEntreprise" => $form->createView(),
                "action"=>$this->generateUrl("app_entreprise_edit",array("id"=>$entreprise->getId()))
            ))
        ));
    }

    /**
     * @Route("/{id}", name="app_entreprise_get")
     * @ParamConverter("entreprise", class="AppBundle:Entreprise")
     */
    public function getAction(Entreprise $entreprise,Request $request) {
       return new JsonResponse(array(
                    "code" => 1,
                    "entreprise" =>  $this->get('jms_serializer')->serialize($entreprise,'json')
                ));
     }

    /**
     * @Route("/state_toggle/{id}", name="app_entreprise_state_toggle")
     * @ParamConverter("entreprise", class="AppBundle:Entreprise")
     */
    public function stateToggleAction(Entreprise $entreprise,Request $request) {
        if($request->isXmlHttpRequest() and $request->isMethod('post')){
            $entreprise->setArchive($entreprise->isArchive()?false:true);
            $this->getManager()->flush();
            return new JsonResponse(array(
                'code' => 1,
                'msg' => 'Statut modifié avec succès'
            ));
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }

    /**
     * @Route("/remove/{id}", name="app_entreprise_remove")
     * @ParamConverter("entreprise", class="AppBundle:Entreprise")
     */
    public function removeAction(Entreprise $entreprise,Request $request) {
        if($request->isXmlHttpRequest() and $request->isMethod('post')){
            if($entreprise->getCommandes()->count() > 0 OR $entreprise->getFactures()->count() > 0 OR $entreprise->getGerants()->count() >0){
                return new JsonResponse(array(
                    "code" => 0,
                    "msg" =>  'Impossible de supprimer un enregistrement lié avec d\'autres !!'
                ));
            }
            $this->getManager()->remove($entreprise,true);
            return new JsonResponse(array(
                'code' => 1,
                'msg' => 'Entreprise supprimée avec succès'
            ));
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }

    /**
     * @Route("/produits/{id}", name="app_entreprise_produits")
     * @ParamConverter("entreprise", class="AppBundle:Entreprise")
     */
    public function produitsAction(Entreprise $entreprise,Request $request) {
        if($request->isMethod("POST") and $request->request->has("entreprise_produits") and $request->isXmlHttpRequest()){
            if(is_array($request->request->get("entreprise_produits"))){
                foreach ($entreprise->getProduits() as $pe){
                    $this->get('app.produit_entreprise.manager')->remove($pe);
                }
                foreach ($request->request->get("entreprise_produits") as $ligne){
                    $newPe = $this->get('app.produit_entreprise.manager')->create();
                    $newPe
                        ->setEntreprise($entreprise)
                        ->setProduit($this->get('app.produit.manager')->find($ligne['produit']))
                        ->setPrix($ligne['prix'])
                    ;
                    $this->get('app.produit_entreprise.manager')->persist($newPe);
                    $entreprise->addProduit($newPe);
                }
                $this->get('app.produit_entreprise.manager')->flush();
                return new JsonResponse(array(
                    'code' => 1,
                    'msg' => 'Modifications enregistrées avec succès'
                ));
            }else{
                return new JsonResponse(array(
                    'code' => 0,
                    'msg' => 'Erreur lors de l\'extraction des données!'
                ));
            }
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:entreprise:form.produits.html.twig",array(
                "entreprise" => $entreprise,
                "action"=>$this->generateUrl("app_entreprise_produits",array("id" => $entreprise->getId())),
                "produitsAlia" => $this->get('app.produit.manager')->AliaForSel(),
                "produitsImportation" => $this->get('app.produit.manager')->ImportationForSel()
            ))
        ));
    }

    /**
     * @return EntrepriseManager
     */
    private function getManager(){
        return $this->get("app.entreprise.manager");
    }
}