<?php

/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 08/04/2017
 * Time: 11:27
 */
namespace AppBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
class AppController extends Controller
{
    /**
     * @Route("/", name="app_index")
     */
    public function indexAction() {
        return $this->render("AppBundle::index.html.twig");
    }
}