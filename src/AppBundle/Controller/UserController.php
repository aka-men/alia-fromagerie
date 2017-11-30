<?php
/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 10/04/2017
 * Time: 17:06
 */

namespace AppBundle\Controller;
use AppBundle\Entity\User;
use AppBundle\Form\ProfilType;
use AppBundle\Form\UserType;
use AppBundle\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/settings/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/", name="app_user_index")
     */
    public function indexAction(Request $request) {
       if($request->isMethod("POST") and $request->isXmlHttpRequest()){
            $param_sort = array(
                '',
                'u.id',
                'u.username',
                'u.email',
                'u.roles',
                'u.lastLogin',
                'u.enabled',
            );
            $start = $request->request->get("start");
            $length = $request->request->get("length");
            $sort = $param_sort[$request->request->get("order")[0]['column']];
            $dir = $request->request->get("order")[0]['dir'];
            $search = $request->request->get("search",null);
            $requestResult = $this->getManager()->listeDataTable($this->getUser()->getId(),$search['value'], $sort, $dir, $start, $length);
            $resultat = array(
                "data" => array(),
                'recordsFiltered' => $requestResult["totalFiltred"],
                'recordsTotal' => $requestResult["total"]
            );
            $ligne = 1;
            foreach ($requestResult["users"] as $user) {
                $resultat['data'][] = array(
                    "ligne" => $ligne,
                    "id"=> $user->getId(),
                    "username" => $user->getUsername(),
                    "email" => $user->getEmail(),
                    "role" => $user->getRole(),
                    "lastLogin" => $user->getLastLogin() ? $user->getLastLogin()->format("d/m/Y"):'',
                    "active" => $this->renderView("AppBundle:user:td.html.twig",array(
                        "user" => $user,
                        "td" => 'active'
                    )),
                    "action" => $this->renderView("AppBundle:user:td.html.twig",array(
                        "user" => $user,
                        "td" => 'action'
                    )),
                );
                $ligne++;
            }
            return new JsonResponse($resultat);
        }
        return $this->render("AppBundle:user:index.html.twig");
    }

    /**
     * @Route("/add", name="app_user_add")
     */
    public function addAction(Request $request) {
        $user = $this->getManager()->create();
        $form = $this->createForm(UserType::class,$user);
        if($request->isMethod("POST") and $request->request->has("appbundle_user") and $request->isXmlHttpRequest()){
            $form->handleRequest($request);
            if($form->isValid()) {
                $encoder = $this->container->get('security.password_encoder');
                $password = $encoder->encodePassword($user, $user->getPlainPassword(),$user->getSalt());
                $user->setPassword($password);
                $this->getManager()->persist($user, true);
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Utilisateur ajouté avec succès"
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>(string)$form->getErrors(true)));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:user:form.html.twig",array(
                "formUser" => $form->createView(),
                "action"=>$this->generateUrl("app_user_add")
            ))
        ));
    }

    /**
     * @Route("/edit/{id}", name="app_user_edit")
     * @ParamConverter("user", class="AppBundle:User")
     */
    public function editAction(User $user,Request $request) {
        $form = $this->createForm(UserType::class,$user);
        if($request->isMethod("POST") and $request->request->has("appbundle_user") and $request->isXmlHttpRequest()){
            $form->handleRequest($request);
            if($form->isValid()){
                $this->getManager()->flush();
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Utilisateur modifié avec succès"
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>(string)$form->getErrors(true)));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:user:form.edit.html.twig",array(
                "formUser" => $form->createView(),
                "action"=>$this->generateUrl("app_user_edit",array("id"=>$user->getId())),
                "user" => $user
            ))
        ));
    }

    /**
     * @Route("/profil", name="app_user_profil")
     */
    public function profilAction(Request $request) {
        /**
         * @var $user User
         */
        $user = $this->getUser();
        $form = $this->createForm(ProfilType::class,$user);
        $formPasswordChange = $this->get('fos_user.change_password.form.factory')->createForm();
        $formPasswordChange->setData($user);
        if($request->isMethod('POST') and $request->request->has('appbundle_profil')){
            $form->handleRequest($request);
            if($form->isValid()) {
                if ($request->request->has("check_image") and $request->request->get("check_image") === "true") {
                    if (!is_null($user->getImage()) and ! is_null($user->getImage()->getId())) {
                        if (!is_null($user->getImage()->getFile())) {
                            $user->getImage()->upload();
                        } else {
                            $this->get('app.image.manager')->remove($user->getImage());
                            $user->setImage();
                        }
                    }
                }
                $this->getManager()->flush();
                $request->getSession()->getFlashBag()->add('success', 'Modification enregistré avec succès !');
            }else{
                $errors = explode('ERROR: ',(string)$form->getErrors(true));
                array_shift($errors);
                $request->getSession()->getFlashBag()->add('error', implode('<br/> <i class="fa fa-times-circle" aria-hidden="true"></i> ',$errors));
            }
        }
        if($request->isMethod('POST') and $request->request->has('fos_user_change_password_form')){
            $formPasswordChange->handleRequest($request);
            if($formPasswordChange->isValid()) {
                $encoder = $this->container->get('security.password_encoder');
                $password = $encoder->encodePassword($user, $user->getPlainPassword(),$user->getSalt());
                $user->setPassword($password);
                $this->getManager()->flush();
                $request->getSession()->getFlashBag()->add('success', 'Mot de passe enregistré avec succès !');
            }else{
                $errors = explode('ERROR: ',(string)$formPasswordChange->getErrors(true));
                array_shift($errors);
                $request->getSession()->getFlashBag()->add('error', implode('<br/> <i class="fa fa-times-circle" aria-hidden="true"></i> ',$errors));
            }
        }
        return $this->render('AppBundle:user:profil.html.twig',[
            'formProfil' => $form->createView(),
            'formPasswordChange' => $formPasswordChange->createView(),
            'action' => $this->generateUrl('app_user_profil')
        ]);
    }

    /**
     * @Route("/state_toggle", name="app_user_state_toggle")
     */
    public function stateToggleAction(Request $request) {
        if($request->isXmlHttpRequest()){
            $user = $this->getManager()->find($request->request->get("id_user",0));
            if(!$user)
                throw $this->createNotFoundException("Objet Introuvable");
            $user->toggleState();
            $this->getManager()->flush();
            return new JsonResponse(array(
               "code" => 1
            ));
        }

    }
    /**
     * @return UserManager
     */
    private function getManager(){
        return $this->get("app.user.manager");
    }
}