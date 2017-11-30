<?php

namespace AppBundle\Controller;

use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of ExceptionController
 *
 * @author abdelhak
 */
class ExceptionController {

    protected $twig;

    /**
     * @var bool Show error (false) or exception (true) pages by default
     */
    protected $debug;

    public function __construct(\Twig_Environment $twig, $debug) {
        $this->twig = $twig;
        $this->debug = $debug;
    }

    /**
     * Converts an Exception to a Response.
     *
     * A "showException" request parameter can be used to force display of an error page (when set to false) or
     * the exception page (when true). If it is not present, the "debug" value passed into the constructor will
     * be used.
     *
     * @param Request              $request   The request
     * @param FlattenException     $exception A FlattenException instance
     * @param DebugLoggerInterface $logger    A DebugLoggerInterface instance
     *
     * @return Response
     *
     * @throws \InvalidArgumentException When the exception template does not exist
     */
    public function showAction(Request $request, FlattenException $exception, DebugLoggerInterface $logger = null) {
        $currentContent = $this->getAndCleanOutputBuffering($request->headers->get('X-Php-Ob-Level', -1));
        $showException = $request->attributes->get('showException', $this->debug); // As opposed to an additional parameter, this maintains BC

        $code = $exception->getStatusCode();
        switch($code){
            case 401:
                $msg = $showException ? $exception->getMessage() : "Vous devez etre connecté pour voir cette page ou d'exécuter cette action";
                $statut = "Accès refusé";
                break;
            case 403:
                $msg = $showException ? $exception->getMessage() : "Vous n'avez pas la permission de voir cette page ou d'exécuter cette action";
                $statut = "Accès refusé";
                break;
            case 404:
                $msg = $showException ? $exception->getMessage() : "Le contenu demandé n'est pas disponible";
                $statut = "Contenu non trouvée";
                break;
            case 500:
            case 503:
                $msg = $showException ? $exception->getMessage() : "Une erreur s'est produite dans l'application";
                $statut = "Erreur";
                break;
            default:
                $msg = $showException ? $exception->getMessage() : "Une erreur s'est produite dans l'application";
                $statut = isset(Response::$statusTexts[$code]) ? Response::$statusTexts[$code] : "Erreur";
                break;
        }
        if ($request->isXmlHttpRequest()) {
            return new Response(json_encode(array(
                        "status_code" => $code,
                        "status_text" => $statut,
                        'exception' => $msg
                    )), $code, array("Content-type" => "application/json"));
        }
        return new Response($this->twig->render(
                        (string) $this->findTemplate($request, "html", $code, $showException), array(
                    'status_code' => $code,
                    'status_text' => isset(Response::$statusTexts[$code]) ? Response::$statusTexts[$code] : '',
                    'exception' => $exception,
                    'logger' => $logger,
                    'currentContent' => $currentContent,
                        )
        ));
    }

    /**
     * @param int $startObLevel
     *
     * @return string
     */
    protected function getAndCleanOutputBuffering($startObLevel) {
        if (ob_get_level() <= $startObLevel) {
            return '';
        }

        Response::closeOutputBuffers($startObLevel + 1, true);

        return ob_get_clean();
    }

    /**
     * @param Request $request
     * @param string  $format
     * @param int     $code          An HTTP response status code
     * @param bool    $showException
     *
     * @return string
     */
    protected function findTemplate(Request $request, $format, $code, $showException) {
        $name = $showException ? 'exception' : 'error';
        if ($showException && 'html' == $format) {
            $name = 'exception_full';
        }

        // For error pages, try to find a template for the specific HTTP status code and format
        if (!$showException) {
            $template = sprintf('@Twig/Exception/%s%s.%s.twig', $name, $code, $format);
            if ($this->templateExists($template)) {
                return $template;
            }
        }

        // try to find a template for the given format
        $template = sprintf('@Twig/Exception/%s.%s.twig', $name, $format);
        if ($this->templateExists($template)) {
            return $template;
        }

        // default to a generic HTML exception
        $request->setRequestFormat('html');

        return sprintf('@Twig/Exception/%s.html.twig', $showException ? 'exception_full' : $name);
    }

    // to be removed when the minimum required version of Twig is >= 3.0
    protected function templateExists($template) {
        $template = (string) $template;

        $loader = $this->twig->getLoader();
        if ($loader instanceof \Twig_ExistsLoaderInterface) {
            return $loader->exists($template);
        }

        try {
            $loader->getSource($template);

            return true;
        } catch (\Twig_Error_Loader $e) {
            
        }

        return false;
    }

}
