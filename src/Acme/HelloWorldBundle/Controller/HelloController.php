<?php
namespace Acme\HelloWorldBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/** Klasa kontrollera obsługująca HelloWorldBundle.
 * Oczywiście w jednym Bundle może być wiele kontrollerów, do obsługi róznych zdarzeń, jednak...
 * należy robić to z głową, aby utrzymać esencje atomowości (Zasada obiektowego progromowania)
 * Class HelloController
 * @package Acme\HelloWorldBundle\Controller
 */
class HelloController extends Controller {

    public function indexAction($name) {
        /**
         * Renderuje używając response-a templatkę z danymi, podanymi w formie tablicy jako drugi argument.
         * Konwencja:
         * BundleName:ControllerName:TemplateName, gdzie TemplateName: /path/to/BundleName/Resources/views/ControllerName/TemplateName
         * Korzystamy z twiga, do tworzenia templatek. Jest to bardzo mocny język? przygotowany do tworzenia templatek :)
         */
        return $this->render('AcmeHelloWorldBundle:Hello:index.html.twig', array('name' => $name));
    }
} 