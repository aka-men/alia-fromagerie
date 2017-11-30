<?php

/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 08/04/2017
 * Time: 11:27
 */
namespace AppBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Entity\Achat;
use AppBundle\Entity\AchatMatierePremiereProduit;
use AppBundle\Form\AchatType;
use AppBundle\Manager\AchatManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/achats")
 */
class AchatController extends Controller
{
    /**
     * @Route("/", name="app_achat_index")
     */
    public function indexAction(Request $request) {
        if($request->isXmlHttpRequest() and $request->request->has('columns')){
            $param_sort = array(
                '',
                'a.id',
                'a.date',
                'a.numeroFacture',
                'a.fournisseur',
                'a.montantTtc',
                'a.tva',
                'a.modePayment',
                'a.numeroCheque',
                'a.observation'
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
                'recordsTotal' => $requestResult["total"],
                'totals' => $this->renderView('AppBundle:charge:totals.html.twig',array(
                    'totals'=>$requestResult['totals'],
                    'totalGlobal'=>$requestResult['totalGlobal']))
            );
            $ligne = 1;
            foreach ($requestResult['achats'] as $achat){
               $resultat['data'][] = array(
                    "ligne" => $ligne,
                    "id" => $achat->getId(),
                    "date" => $achat->getDate()->format('d/m/Y'),
                    "montant" => number_format($achat->getMontantTtc(),2,'.',' '),
                    "mode" => $achat->getModePayment()?$achat->getModePayment()->getLabel():null,
                    "cheque" => $achat->getNumeroCheque().(is_null($achat->getImageCheque())? null :'<a style="margin-left:5px;" title="'.$achat->getNumeroCheque().'" data-fancybox href="'.$this->generateUrl("app_images",array("nom" => $achat->getImageCheque()->getNom())).'"><i class="fa fa-eye" aria-hidden="true"></i></a>'),
                    "dateCheque" => $achat->getDateCheque() ? $achat->getDateCheque()->format('d/m/Y') : null,
                    "facture" => $achat->getNumeroFacture(),
                    "tva" => $achat->getTva()?$achat->getTva().'%':null,
                    "fournisseur" => $achat->getFournisseur()?$achat->getFournisseur()->getNom(): null,
                    "observation" => $achat->getObservation(),
                    "action" =>$this->renderView("AppBundle:achat:td.action.html.twig",array('achat' => $achat)),
                );
                $ligne++;
            }
            return new JsonResponse($resultat);
        }
        return $this->render("AppBundle:achat:index.html.twig",array(
            "modes" => $this->get('app.mode_payment.manager')->findAll(),
            "fournisseurs" => $this->get('app.fournisseur.manager')->findAll()
        ));
    }

    /**
     * @Route("/produits-finis", name="app_achat_produit_fini_index")
     */
    public function indexPFAction(Request $request) {
        if($request->isXmlHttpRequest() and $request->request->has('columns')){
            $param_sort = array(
                '',
                'a.id',
                'a.date',
                'a.numeroFacture',
                'a.fournisseur',
                'a.montantTtc',
                'a.tva',
                'a.modePayment',
                'a.numeroCheque',
                'a.observation'
            );
            $start = $request->request->get("start");
            $length = $request->request->get("length");
            $criteres = $request->request->get("criteres",array());
            $sort = $param_sort[$request->request->get("order")[0]['column']];
            $dir = $request->request->get("order")[0]['dir'];
            $requestResult = $this->getManager()->listeDataTablePF($criteres,$sort, $dir, $start, $length);
            $resultat = array(
                "data" => array(),
                'recordsFiltered' => $requestResult["totalFiltred"],
                'recordsTotal' => $requestResult["total"],
                'totals' => $this->renderView('AppBundle:charge:totals.html.twig',array(
                    'totals'=>$requestResult['totals'],
                    'totalGlobal'=>$requestResult['totalGlobal']))
            );
            $ligne = 1;
            foreach ($requestResult['achats'] as $achat){
                $resultat['data'][] = array(
                    "ligne" => $ligne,
                    "id" => $achat->getId(),
                    "date" => $achat->getDate()->format('d/m/Y'),
                    "montant" => number_format($achat->getMontantTtc(),2,'.',' '),
                    "mode" => $achat->getModePayment()?$achat->getModePayment()->getLabel():null,
                    "cheque" => $achat->getNumeroCheque().(is_null($achat->getImageCheque())? null :'<a style="margin-left:5px;" title="'.$achat->getNumeroCheque().'" data-fancybox href="'.$this->generateUrl("app_images",array("nom" => $achat->getImageCheque()->getNom())).'"><i class="fa fa-eye" aria-hidden="true"></i></a>'),
                    "dateCheque" => $achat->getDateCheque() ? $achat->getDateCheque()->format('d/m/Y') : null,
                    "facture" => $achat->getNumeroFacture(),
                    "tva" => $achat->getTva()?$achat->getTva().'%':null,
                    "fournisseur" => $achat->getFournisseur()?$achat->getFournisseur()->getNom(): null,
                    "observation" => $achat->getObservation(),
                    "action" =>$this->renderView("AppBundle:achat:td.action.html.twig",array('achat' => $achat))
                );
                $ligne++;
            }
            return new JsonResponse($resultat);
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }

    /**
     * @Route("/add", name="app_achat_add")
     */
    public function addAction(Request $request) {
        $achat = $this->getManager()->create();
        $form = $this->createForm(AchatType::class,$achat);
        if($request->isXmlHttpRequest() and $request->request->has('appbundle_achat')){
            $form->handleRequest($request);
                if($form->isValid()){
                    $achat->setUser($this->getUser());
                    $this->getManager()->persist($achat,true);
                    return new JsonResponse(array(
                        "code" => 1,
                        "msg" => 'Achat (Id = '.$achat->getId().') ajouté avec succès'
                    ));
                }else{
                    return new JsonResponse(array(
                        "code" => 0,
                        "msg" => (string)$form->getErrors(true)
                    ));
                }
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView('AppBundle:achat:form.html.twig',array(
                "formAchat" => $form->createView(),
                "produits" => $this->get('app.produit.manager')->findAll(),
                "fournisseurs" => $this->get('app.fournisseur.manager')->findBy(array("typeVente" => $request->query->get('type'))),
                "type" => $request->query->get('type')
            ))
        ));
    }




    /**
     * @Route("/options-productions-form", name="app_charge_options_production_form")
     */
    public function productionOptionsFormAction(Request $request) {
        $produit = $this->get('app.produit.manager')->find($request->request->get('id',0));
        if(!$produit or($produit and !$produit->isMatierePremiere()))
            throw $this->createNotFoundException('Produit Introuvable!!');
        return new JsonResponse(array(
            "code" => 1,
            "formProduction" => $this->renderView('AppBundle:achat:form.production.html.twig',array(
                "produit" => $produit,
                'index' => $request->request->get('index',0)
            )),
            "formOptions" => $this->renderView('AppBundle:achat:form.options.html.twig',array(
                "produit" => $produit,
                "options" => $this->get('app.option.manager')->findAll(),
                'index' => $request->request->get('index',0)
            ))));
    }

    /**
     * @Route("/edit/{id}", name="app_achat_edit")
     * @ParamConverter("achat", class="AppBundle:Achat")
     */
    public function editAction(Achat $achat,Request $request) {
        $previousProduits = clone $achat->getProduits();
        $previousProductions = clone $achat->getProductions();
        $values = array();
        $valuesPA= array();
        foreach ($previousProduits as $pa){
            $valuesPA[$pa->getId()] = $pa->getQuantite();
        }
        foreach ($previousProductions as $pra){
            $values[$pra->getId()] = $pra->getValeur();
        }
        $previousOptions = clone $achat->getOptions();
        $form = $this->createForm(AchatType::class,$achat);
        if($request->isMethod("POST") and $request->request->has("appbundle_achat") and $request->isXmlHttpRequest()){
            $form->handleRequest($request);
            if($form->isValid()){
                foreach ($previousProductions as $pra){
                    if(!$achat->getProductions()->contains($pra)){
                        $produit = $pra->getProduit();
                        $produit->increaseStock(0 - $values[$pra->getId()]);
                        $this->get('app.achat_production.manager')->remove($pra);
                    }
                    else{
                        $praActuel = $achat->getProductions()->get($achat->getProductions()->indexOf($pra));
                        if (is_null($pra->getValeur())){
                            $praActuel->getProduit()->increaseStock($praActuel->getValeur() ? $praActuel->getValeur() : 0 );
                        }else {
                            $produit = $praActuel->getProduit();
                            if (is_null($praActuel->getValeur()))
                                $produit->increaseStock(0 - $values[$pra->getId()]);
                            else
                                $produit->increaseStock($praActuel->getValeur() - $values[$pra->getId()]);
                        }
                    }
                }
                foreach ($previousOptions as $oa){
                    if(!$achat->getOptions()->contains($oa))
                        $this->get('app.achat_option.manager')->remove($oa);
                }
                foreach ($previousProduits as $pa){
                    if(!$achat->getProduits()->contains($pa)){
                        $pa->getProduit()->increaseStock(0 - $valuesPA[$pa->getId()]);
                        $this->get('app.produit_achat.manager')->remove($pa);
                    }
                    else{
                        $paActuel = $achat->getProduits()->get($achat->getProduits()->indexOf($pa));
                        if(!$pa->getProduit()->isMatierePremiere()){
                            $pa->getProduit()->increaseStock($paActuel->getQuantite() - $valuesPA[$pa->getId()]);
                        }
                    }
                }

                if($achat->getImageCheque() and $achat->getImageCheque()->getFile()){
                    if(is_null($achat->getImageCheque()->getId()))
                        $achat->getImageCheque()->preUpload();
                    $achat->getImageCheque()->upload();
                }
                if($achat->getImage() and $achat->getImage()->getFile()){
                    if(is_null($achat->getImage()->getId()))
                        $achat->getImage()->preUpload();
                    $achat->getImage()->upload();
                }
                $achat->preUpdate();
                $this->getManager()->flush($achat);
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Achat modifié avec succès",
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>(string)$form->getErrors(true)));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:achat:form.edit.html.twig",array(
                "formAchat" => $form->createView(),
                'achat' => $achat,
                "fournisseurs" => $this->get('app.fournisseur.manager')->findAll(),
                //"produits" => $this->get('app.produit.manager')->findAll()
        ))));
    }

    /**
     * @Route("/remove/{id}", name="app_achat_remove")
     * @ParamConverter("achat", class="AppBundle:Achat")
     */
    public function removeAction($achat,Request $request) {
        $this->getManager()->remove($achat,true);
        return new JsonResponse(array(
            "code" => 1,
            "msg" => "Achat supprimée avec succès",
        ));
    }


    /**
     * @Route("/details/{id}", name="app_achat_details")
     * @ParamConverter("achat", class="AppBundle:Achat")
     */
    public function detailsAction($achat,Request $request) {

        return new JsonResponse(array(
            "code" => 1,
            "html" => $this->renderView("AppBundle:achat:block.analyses.productions.html.twig",array(
                'achat' => $achat
            )),
        ));
    }

    /**
     * @Route("/productions", name="app_achat_productions")
     */
    public function productionsAction(Request $request) {
        $production = $this->get('app.achat_production.manager')->production($request->request->all());
        return new JsonResponse(array(
            "code" => 1,
            "html" => $this->renderView("AppBundle:achat:production.html.twig",array(
                'production' => $production,
                'params' => $request->request->all()
            )),
        ));
    }

    /**
     * @return AchatManager
     */
    private function getManager(){
        return $this->get('app.achat.manager');
    }
}