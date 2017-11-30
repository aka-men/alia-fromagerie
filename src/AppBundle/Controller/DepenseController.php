<?php
/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 04/05/2017
 * Time: 20:04
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/depenses")
 */
class DepenseController extends Controller
{
    /**
     * @Route("/settings", name="app_depense_settings")
     */
    public function settingsAction(Request $request) {
       return $this->redirectToRoute('app_depense_fournisseur');
    }


    /**
     * @Route("/settings/fournisseur", name="app_depense_fournisseur")
     */
    public function settingsFournisseurAction(Request $request) {
        return $this->render("AppBundle:depense:settings_fournisseur.html.twig",array(

        ));
    }

    /**
     * @Route("/settings/matiere-premiere", name="app_depense_matiere_premiere")
     */
    public function settingsMatierePremiereAction(Request $request) {
        return $this->render("AppBundle:depense:settings_matiere_premiere.html.twig",array(
            'fournisseurs' => $this->get('app.fournisseur.manager')->findAll()
        ));
    }

    /**
     * @Route("/settings/produit-fini", name="app_depense_poduit_fini")
     */
    public function settingsProduitFiniAction(Request $request) {
        return $this->render("AppBundle:depense:settings_produit_fini.html.twig",array(
            'fournisseurs' => $this->get('app.fournisseur.manager')->findAll()
        ));
    }

    /**
     * @Route("/settings/designation", name="app_depense_designation")
     */
    public function settingsDesignationAction(Request $request) {
        return $this->render("AppBundle:depense:settings_designation.html.twig",array(
            'fournisseurs' => $this->get('app.fournisseur.manager')->findAll()
        ));
    }

    /**
     * @Route("/settings/analyse", name="app_depense_analyse")
     */
    public function settingsAnalyseAction(Request $request) {
        return $this->render("AppBundle:depense:settings_analyse.html.twig",array(

        ));
    }

    /**
     * @Route("/settings/banque", name="app_depense_banque")
     */
    public function settingsBanqueAction(Request $request) {
        return $this->render("AppBundle:depense:settings_banque.html.twig",array(

        ));
    }

    /**
     * @Route("/settings/mode-paiement", name="app_depense_mode_paiement")
     */
    public function settingsModePaiementAction(Request $request) {
        return $this->render("AppBundle:depense:settings_paiement.html.twig",array(

        ));
    }

    /**
     * @Route("/settings/employe", name="app_depense_employe")
     */
    public function settingsEmployeAction(Request $request) {
        return $this->render("AppBundle:depense:settings_employe.html.twig",array(

        ));
    }
}