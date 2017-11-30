<?php
/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 10/04/2017
 * Time: 17:06
 */

namespace AppBundle\Controller;
use AppBundle\Entity\Paiement;
use AppBundle\Manager\PaiementManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/paiement")
 */
class PaiementController extends Controller
{

    /**
     * @Route("/", name="app_paiement_index")
     */
    public function indexAction(Request $request) {
        if($request->isXmlHttpRequest() and $request->request->has('columns')){
            $param_sort = array(
                '',
                'p.date',
                '',
                '',
                'p.prix',
                'p.modePayment',
                'p.numeroReglement'
            );
            $start = $request->request->get("start");
            $length = $request->request->get("length");
            $criteres = $request->request->get("criteres",array());
            $sort = $param_sort[$request->request->get("order")[0]['column']];
            $dir = $request->request->get("order")[0]['dir'];
            $requestResult = $this->getManager()->listeDataTableParent($criteres,$sort, $dir, $start, $length);
            $resultat = array(
                "data" => array(),
                'recordsFiltered' => $requestResult["totalFiltred"],
                'recordsTotal' => $requestResult["total"],
            );
            $ligne = 1;
            foreach ($requestResult["paiements"] as $paiement) {
                $resultat['data'][] = array(
                    "ligne" => $this->renderView("AppBundle:paiement:td.html.twig",array(
                        "td" => 'ligne',
                        'ligne' => $ligne
                    )),
                   "date" => $this->renderView("AppBundle:paiement:td.html.twig",array(
                        "td" => 'date',
                        "paiement" => $paiement
                    )),
                    "client" => $this->renderView("AppBundle:paiement:td.html.twig",array(
                        "td" => 'client',
                        "paiement" => $paiement
                    )),
                    "commande" => $this->renderView("AppBundle:paiement:td.html.twig",array(
                        "td" => 'commande',
                        "paiement" => $paiement
                    )),
                    "montant" => $this->renderView("AppBundle:paiement:td.html.twig",array(
                        "td" => 'montant',
                        "paiement" => $paiement
                    )),
                    "mode" => $this->renderView("AppBundle:paiement:td.html.twig",array(
                        "td" => 'mode',
                        "paiement" => $paiement
                    )),
                    "numero" => $this->renderView("AppBundle:paiement:td.html.twig",array(
                        "td" => 'numero',
                        "paiement" => $paiement
                    )),
                );
                $ligne++;
            }
            return new JsonResponse($resultat);
        }
        return $this->render("AppBundle:paiement:index.html.twig",array(
            "modes" => $this->get('app.mode_payment.manager')->findAll(),
            "clients" => $this->get('app.client.manager')->findBy(array("isCommercial" => false)),
            "commerciales" => $this->get('app.client.manager')->findBy(array("isCommercial" => true)),
            "entreprises" => $this->get('app.entreprise.manager')->findAll(),
            'queryParams' => $request->query->all()
        ));
    }



    /**
     * @Route("/invalide", name="app_paiement_invalide")
     */
    public function paiementInvalideAction(Request $request) {
        if($request->isMethod("POST") and $request->isXmlHttpRequest()){
            $param_sort = array(
                '',
                'p.date',
                '',
                'p.commande',
                'p.prix'
            );
            $start = $request->request->get("start");
            $length = $request->request->get("length");
            $criteres = $request->request->get("criteres",array());
            $sort = $param_sort[$request->request->get("order")[0]['column']];
            $dir = $request->request->get("order")[0]['dir'];
            $requestResult = $this->getManager()->listeDataTable($criteres,$sort, $dir, $start, $length,false);
            $resultat = array(
                "data" => array(),
                'recordsFiltered' => $requestResult["totalFiltred"],
                'recordsTotal' => $requestResult["total"]
            );

            $ligne = 1;
            foreach ($requestResult["paiements"] as $paiement) {
                $resultat['data'][] = array(
                    "ligne" => $this->renderView("AppBundle:paiement:invalide.td.html.twig",array(
                        "td" => 'ligne',
                        "paiement" => $paiement,
                        'ligne' => $ligne
                    )),
                    "date" => $this->renderView("AppBundle:paiement:invalide.td.html.twig",array(
                        "td" => 'date',
                        "paiement" => $paiement
                    )),
                    "montant" => $this->renderView("AppBundle:paiement:invalide.td.html.twig",array(
                        "td" => 'montant',
                        "paiement" => $paiement
                    )),
                    "commande" => $this->renderView("AppBundle:paiement:invalide.td.html.twig",array(
                        "td" => 'commande',
                        "paiement" => $paiement
                    )),
                    "client" => $this->renderView("AppBundle:paiement:invalide.td.html.twig",array(
                        "td" => 'client',
                        "paiement" => $paiement
                    )),
                    "action" => $this->renderView("AppBundle:paiement:invalide.td.html.twig",array(
                        "td" => 'action',
                        "paiement" => $paiement
                    )),
                );
                $ligne++;
            }
            return new JsonResponse($resultat);
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }


    /**
     * @Route("/reglement-a-deposer", name="app_paiement_reglement_a_deposer")
     */
    public function reglementADeposerAction(Request $request) {
        if($request->isMethod("POST") and $request->isXmlHttpRequest()){
            $start = $request->request->get("start");
            $length = $request->request->get("length");
            $modes = array('Chèque','Effet');
            $requestResult = $this->getManager()->reglementDeposer($modes,3, $start, $length);
            $resultat = array(
                "data" => array(),
                'recordsFiltered' => $requestResult["totalFiltred"],
                'recordsTotal' => $requestResult["total"]
            );

            $ligne = 1;
            foreach ($requestResult["paiements"] as $paiement) {
                $resultat['data'][] = array(
                    "ligne" => $this->renderView("AppBundle:paiement:reglement_deposer.td.html.twig",array(
                        "td" => 'ligne',
                        "paiement" => $paiement,
                        'ligne' => $ligne
                    )),
                    "date" => $this->renderView("AppBundle:paiement:reglement_deposer.td.html.twig",array(
                        "td" => 'date',
                        "paiement" => $paiement
                    )),
                    "numero" => $this->renderView("AppBundle:paiement:reglement_deposer.td.html.twig",array(
                        "td" => 'numero',
                        "paiement" => $paiement
                    )),
                    "type" => $this->renderView("AppBundle:paiement:reglement_deposer.td.html.twig",array(
                        "td" => 'type',
                        "paiement" => $paiement
                    )),
                    "montant" => $this->renderView("AppBundle:paiement:reglement_deposer.td.html.twig",array(
                        "td" => 'montant',
                        "paiement" => $paiement
                    ))
                );
                $ligne++;
            }
            return new JsonResponse($resultat);
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }

    /**
     * @Route("/valider/{id}", name="app_paiement_valider")
     * @ParamConverter("paiement", class="AppBundle:Paiement")
     */
    public function validerAction(Paiement $paiement,Request $request) {
        $paiement->setValid(true);
        $this->getManager()->flush();
        return new JsonResponse(array(
            "code" => 1,
            "msg" =>  'Paiement validé avec succès'
        ));
    }

    /**
     * @return PaiementManager
     */
    private function getManager(){
        return $this->get("app.paiement.manager");
    }
}