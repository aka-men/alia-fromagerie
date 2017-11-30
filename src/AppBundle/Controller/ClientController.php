<?php

/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 25/05/2017
 * Time: 19:26
 */
namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use AppBundle\Form\ClientType;
use AppBundle\Manager\ClientManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/clients")
 */
class ClientController extends Controller
{
    /**
     * @Route("/", name="app_client_index")
     */
    public function indexAction(Request $request) {
       if($request->isMethod("POST") and $request->isXmlHttpRequest()){
            $param_sort = array(
                '',
                'c.id',
                'c.nom',
                'c.cin',
                'c.email',
                'c.gsm'
            );
            $start = $request->request->get("start");
            $length = $request->request->get("length");
            $criteres = $request->request->get("criteres",array());
            $sort = $param_sort[$request->request->get("order")[0]['column']];
            $dir = $request->request->get("order")[0]['dir'];
            $requestResult = $this->getManager()->listeDataTableClient($criteres,$sort, $dir, $start, $length);
            $resultat = array(
                "data" => array(),
                'recordsFiltered' => $requestResult["totalFiltred"],
                'recordsTotal' => $requestResult["total"]
            );
            $ligne = 1;
            foreach ($requestResult["clients"] as $client) {
                $resultat['data'][] = array(
                    "ligne" => $ligne,
                    "id" => $client->getId(),
                    "nom" => $this->renderView('AppBundle:client:td.html.twig',array(
                        "client" => $client,
                        'td' => 'nom'
                    )),
                    "cin" => $client->getCin(),
                    "email" => $client->getEmail(),
                    "gsm" => $client->getGsm(),
                    "entreprise" => $client->getEntreprise() ? $client->getEntreprise()->getNom() : '<button data-loading-text=\'Veuillez patienter<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>\' data-title="Atacher <strong style=\'color:cornflowerblue;\'>'.$client->getFullName().'</strong> à une entreprise" title="Atacher '.$client->getNom().' '.$client->getPrenom().' à une entreprise" data-href="'.$this->generateUrl("app_client_entreprise_atache",array("id" => $client->getId())).'" class="btn client-atach-company btn-table btn-success">Attacher</button>',
                    "action" => $this->renderView('AppBundle:client:td.html.twig',array(
                        "client" => $client,
                        "type" => 'client',
                        'td' => 'action'
                    ))
                );
                $ligne++;
            }
            return new JsonResponse($resultat);
        }
        return $this->render("AppBundle:client:client_index.html.twig");
    }

    /**
     * @Route("/commerciaux", name="app_commerciale_index")
     */
    public function commercialeAction(Request $request) {
        if($request->isMethod("POST") and $request->isXmlHttpRequest()){
            $param_sort = array(
                '',
                'c.id',
                'c.nom',
                'c.cin',
                'c.email',
                'c.gsm'
            );
            $start = $request->request->get("start");
            $length = $request->request->get("length");
            $criteres = $request->request->get("criteres",array());
            $sort = $param_sort[$request->request->get("order")[0]['column']];
            $dir = $request->request->get("order")[0]['dir'];
            $requestResult = $this->getManager()->listeDataTableCommerciale($criteres,$sort, $dir, $start, $length);
            $resultat = array(
                "data" => array(),
                'recordsFiltered' => $requestResult["totalFiltred"],
                'recordsTotal' => $requestResult["total"]
            );
            $ligne = 1;
            foreach ($requestResult["clients"] as $client) {
                $resultat['data'][] = array(
                    "ligne" => $ligne,
                    "id" => $client->getId(),
                    "nom" => $this->renderView('AppBundle:client:td.html.twig',array(
                        "client" => $client,
                        'td' => 'nom'
                    )),
                    "cin" => $client->getCin(),
                    "email" => $client->getEmail(),
                    "gsm" => $client->getGsm(),
                    "isEmploye" => $client->isEmploye(),
                    "action" => $this->renderView('AppBundle:client:td.html.twig',array(
                        "client" => $client,
                        "type" => 'commerciale',
                        'td' => 'action'
                    )),
                );
                $ligne++;
            }
            return new JsonResponse($resultat);
        }
        return $this->render("AppBundle:client:commerciale_index.html.twig");
    }

    /**
     * @Route("/add", name="app_client_add")
     */
    public function addAction(Request $request) {
        $client = $this->getManager()->create();
        $form = $this->createForm(ClientType::class,$client);
        if($request->isMethod("POST") and $request->request->has("appbundle_client") and $request->isXmlHttpRequest()){
            $form->handleRequest($request);
            if($form->isValid()) {
                $this->getManager()->persist($client, true);
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Enregistrement ajouté avec succès",
                    "client" =>  $this->get('jms_serializer')->serialize($client,'json')
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>(string)$form->getErrors(true)));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:client:form.html.twig",array(
                "formClient" => $form->createView(),
                "action"=>$this->generateUrl("app_client_add"),
                "params" => $request->query->all()
            ))
        ));
    }

    /**
     * @Route("/import/from/employe", name="app_client_import_from_employe")
     */
    public function importFromEmployeAction(Request $request) {
        if($request->isMethod("POST") and $request->request->has("appbundle_client") and $request->isXmlHttpRequest()){
            if(is_array($request->request->get("appbundle_client")) and array_key_exists('employe',$request->request->get("appbundle_client")) and is_array($request->request->get("appbundle_client")['employe'])){
                foreach ($request->request->get("appbundle_client")['employe'] as $id){
                    $employe = $this->get('app.employe.manager')->find($id);
                    if($employe){
                        $commerciale = $this->getManager()->create();
                        $commerciale
                            ->setCin($employe->getCin())
                            ->setGsm($employe->getGsm())
                            ->setEmail($employe->getEmail())
                            ->setPrenom($employe->getPrenom())
                            ->setNom($employe->getNom())
                            ->setAdresse($employe->getAdresse())
                            ->setIsCommercial(true)
                            ->setArchive(false)
                            ->setEmploye($employe)
                        ;
                        $employe->setCommerciale($commerciale);
                        $this->getManager()->persist($commerciale);
                    }
                }
                $this->getManager()->flush();
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Enregistrement(s) importé(s) avec succès",
                ));
            }
            return new JsonResponse(array(
                "code" => 0,
                "msg" => "Erreur lors de l'extraction des données",
            ));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:client:form.employe.html.twig",array(
                "action"=>$this->generateUrl("app_client_import_from_employe"),
                'employes' => $this->get('app.employe.manager')->findBy(array("archive" => false))
            ))
        ));
    }

    /**
     * @Route("/{id}/entreprise", name="app_client_entreprise_atache")
     * @ParamConverter("client", class="AppBundle:Client")
     */
    public function atacheEntrepriseAction(Client $client,Request $request) {
       if($request->isMethod("POST") and $request->request->has("entreprise_id") and $request->isXmlHttpRequest()){
           $entreprise = $this->get('app.entreprise.manager')->find($request->request->get('entreprise_id'));
           if($entreprise){
               $client->setEntreprise($entreprise);
               $this->getManager()->flush();
               return new JsonResponse(array(
                  'code' => 1,
                   'msg' => $client->getFullName().' est bien attaché à l\'entreprise '.$entreprise->getNom().'.'
               ));
           }else{
               return new JsonResponse(array(
                   'code' => 0,
                   'msg' => 'Entreprise introuvable !!'
               ));
           }
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:client:form.entreprise.html.twig",array(
                'entreprises' => $this->get('app.entreprise.manager')->findAll(),
                "action"=>$this->generateUrl("app_client_entreprise_atache",array(
                    'id'=> $client->getId()
                ))
            ))
        ));
    }


    /**
     * @Route("/edit/{id}", name="app_client_edit")
     * @ParamConverter("client", class="AppBundle:Client")
     */
    public function editAction(Client $client,Request $request) {
        $form = $this->createForm(ClientType::class,$client);
        if($request->isMethod("POST") and $request->request->has("appbundle_client") and $request->isXmlHttpRequest()){
            $form->handleRequest($request);
            if($form->isValid()){
                if($client->isEmploye()){
                    $employe = $client->getEmploye();
                    $employe
                        ->setAdresse($client->getAdresse())
                        ->setCin($client->getCin())
                        ->setNom($client->getNom())
                        ->setPrenom($client->getPrenom())
                        ->setEmail($client->getEmail())
                        ->setGsm($client->getGsm())
                    ;
                }
                $this->getManager()->flush();
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Enregistrement modifié avec succès",
                    "client" =>  $this->get('jms_serializer')->serialize($client,'json')
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>(string)$form->getErrors(true)));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:client:form.html.twig",array(
                "formClient" => $form->createView(),
                "client" => $client,
                "action"=>$this->generateUrl("app_client_edit",array("id" => $client->getId())),
                "params" => $request->query->all()
            ))
        ));
    }

    /**
     * @Route("/state_toggle/{id}", name="app_client_state_toggle")
     * @ParamConverter("client", class="AppBundle:Client")
     */
    public function stateToggleAction($client,Request $request) {
        if($request->isXmlHttpRequest() and $request->isMethod('post')){
            $client->setArchive($client->isArchive()?false:true);
            $this->getManager()->flush();
            return new JsonResponse(array(
                'code' => 1,
                'msg' => 'Statut modifié avec succès'
            ));
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }

    /**
     * @Route("/remove/{id}", name="app_client_remove")
     * @ParamConverter("client", class="AppBundle:Client")
     */
    public function removeAction(Client $client,Request $request) {
        if($request->isXmlHttpRequest() and $request->isMethod('post')){
            if($client->getCommandes()->count() > 0){
                return new JsonResponse(array(
                    "code" => 0,
                    "msg" =>  'Impossible de supprimer un enregistrement lié avec d\'autres !!'
                ));
            }
            $this->getManager()->remove($client,true);
            return new JsonResponse(array(
                'code' => 1,
                'msg' => 'Client supprimé avec succès'
            ));
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }

    /**
     * @Route("/produits/{id}", name="app_client_produits")
     * @ParamConverter("client", class="AppBundle:Client")
     */
    public function produitsAction(Client $client,Request $request) {
        if($request->isMethod("POST") and $request->request->has("client_produits") and $request->isXmlHttpRequest()){
            if(is_array($request->request->get("client_produits"))){
                foreach ($client->getProduits() as $pc){
                    $this->get('app.produit_client.manager')->remove($pc);
                }
                foreach ($request->request->get("client_produits") as $ligne){
                    $newPc = $this->get('app.produit_client.manager')->create();
                    $newPc
                        ->setClient($client)
                        ->setProduit($this->get('app.produit.manager')->find($ligne['produit']))
                        ->setPrix($ligne['prix'])
                    ;
                    $this->get('app.produit_client.manager')->persist($newPc);
                    $client->addProduit($newPc);
                }
                $this->get('app.produit_client.manager')->flush();
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
            "form" => $this->renderView("AppBundle:client:form.produits.html.twig",array(
                "client" => $client,
                "action"=>$this->generateUrl("app_client_produits",array("id" => $client->getId())),
                "produitsAlia" => $this->get('app.produit.manager')->AliaForSel(),
                "produitsImportation" => $this->get('app.produit.manager')->ImportationForSel()
            ))
        ));
    }

    /**
     * @Route("/{id}", name="app_client_get")
     * @ParamConverter("client", class="AppBundle:Client")
     */
    public function getAction(Client $client,Request $request) {
        return new JsonResponse(array(
            "code" => 1,
            "client" =>  $this->get('jms_serializer')->serialize($client,'json')
        ));
    }


    /**
     * @return ClientManager
     */
    private function getManager(){
        return $this->get("app.client.manager");
    }


}