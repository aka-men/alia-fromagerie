<?php
/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 10/04/2017
 * Time: 17:06
 */

namespace AppBundle\Controller;
use AppBundle\Entity\EDocument;
use AppBundle\Form\_FileType;
use AppBundle\Form\EDocumentType;
use AppBundle\Manager\EDocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/e-document")
 */
class EDocumentController extends Controller
{
    /**
     * @Route("/", name="app_e_document_index")
     */
    public function indexAction(Request $request) {
        $documents = $this->getManager()->findBy(array(),array("date"=>"DESC"));
        $document = $this->getManager()->create();
        $form = $this->createForm(EDocumentType::class,$document);
        return $this->render("AppBundle:edocument:index.html.twig",array(
            "documents" => $documents,
            "action" => $this->generateUrl('app_e_document_add'),
            "formDocument" => $form->createView()
        ));
    }

    /**
     * @Route("/add", name="app_e_document_add")
     */
    public function addAction(Request $request) {
        $document = $this->getManager()->create();
        $form = $this->createForm(EDocumentType::class,$document);
        if($request->isMethod("POST") and $request->request->has("appbundle_edocument") and $request->isXmlHttpRequest()){
            $form->handleRequest($request);
            if($form->isValid()) {
                $document->setUser($this->getUser());
                $this->getManager()->persist($document, true);
                $documents = $this->getManager()->findBy(array(),array("date"=>"DESC"));
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Document ajouté avec succès",
                    "document" =>  $this->get('jms_serializer')->serialize($document,'json'),
                    'items' => $this->renderView("AppBundle:edocument:items.html.twig",array(
                        "documents" => $documents
                    ))
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>(string)$form->getErrors(true)));
        }
        throw $this->createAccessDeniedException();
    }

    /**
     * @Route("/edit/{id}", name="app_e_document_edit")
     * @ParamConverter("document", class="AppBundle:EDocument")
     */
    public function editAction(EDocument $document,Request $request) {
        $form = $this->createForm(EDocumentType::class,$document);
        if($request->isMethod("POST") and $request->request->has("appbundle_edocument") and $request->isXmlHttpRequest()){
            $form->handleRequest($request);
            if($form->isValid()){
                $this->getManager()->flush();
                $documents = $this->getManager()->findBy(array(),array("date"=>"DESC"));
                return new JsonResponse(array(
                    "code" => 1,
                    "msg" => "Document modifié avec succès",
                    "document" =>  $this->get('jms_serializer')->serialize($document,'json'),
                    'items' => $this->renderView("AppBundle:edocument:items.html.twig",array(
                        "documents" => $documents
                    ))
                ));
            }else
                return new JsonResponse(array("code"=>0,"msg"=>(string)$form->getErrors(true)));
        }
        return new JsonResponse(array(
            "code" => 1,
            "form" => $this->renderView("AppBundle:edocument:form_edit.html.twig",array(
                "formDocument" => $form->createView(),
                "action"=>$this->generateUrl("app_e_document_edit",array("id"=>$document->getId()))))
        ));
    }

    /**
     * @Route("/{id}", name="app_e_document_get")
     * @ParamConverter("document", class="AppBundle:EDocument")
     */
    public function getAction(EDocument $document,Request $request) {
        return new JsonResponse(array(
            "code" => 1,
            "document" =>  $this->get('jms_serializer')->serialize($document,'json')
        ));
    }

    /**
     * @Route("/remove/{id}", name="app_e_document_remove")
     * @ParamConverter("document", class="AppBundle:EDocument")
     */
    public function removeAction(EDocument $document,Request $request) {
        if($request->isXmlHttpRequest() and $request->isMethod('post')){
           $this->getManager()->remove($document,true);
            $documents = $this->getManager()->findBy(array(),array("date"=>"DESC"));
            return new JsonResponse(array(
                'code' => 1,
                'msg' => 'Document supprimé avec succès',
                'items' => $this->renderView("AppBundle:edocument:items.html.twig",array(
                    "documents" => $documents
                ))
            ));
        }
        throw $this->createAccessDeniedException("L'URL demandée n'est pas autorisée");
    }

    /**
     * @Route("/{id}/file", name="app_e_document_file")
     * @ParamConverter("document", class="AppBundle:EDocument")
     */
    public function fileAction(EDocument $document,Request $request) {
        $file = $document->getFile();
        if (!$file) {
            throw $this->createNotFoundException("L'image demandée n'existe pas");
        }
        $file = $this->getParameter("web_dir") . "files/upload/" . $file->getFullName();
        if (!file_exists($file) or ! is_file($file)) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException("Impossible de trouver le fichier demandée");
        }
        return new \Symfony\Component\HttpFoundation\Response(file_get_contents($file), 200, array(
            "Content-Type" => mime_content_type($file)
        ));
    }

    /**
     * @Route("/{id}/file/downnload", name="app_e_document_download_file")
     * @ParamConverter("document", class="AppBundle:EDocument")
     */
    public function fileDownloadAction(EDocument $document,Request $request) {
        $file = $document->getFile();
        if (!$file) {
            throw $this->createNotFoundException("L'image demandée n'existe pas");
        }
        $file = $this->getParameter("web_dir") . "files/upload/" . $file->getFullName();
        if (!file_exists($file) or ! is_file($file)) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException("Impossible de trouver le fichier demandée");
        }
        return new \Symfony\Component\HttpFoundation\Response(file_get_contents($file), 200, array(
            "Content-Type" => mime_content_type($file),
            'Content-Disposition' => 'attachment;filename="'.$document->getTitre().'.'.$document->getFile()->getExtension().'"'
        ));
    }




    /**
     * @return EDocumentManager
     */
    private function getManager(){
        return $this->get("app.e_document.manager");
    }
}