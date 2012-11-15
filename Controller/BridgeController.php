<?php

namespace mikemeier\PHPNodeBridge\Controller;

use mikemeier\PHPNodeBridge\Service\Bridge;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class BridgeController extends Controller
{

    /**
     * @Route("/call", name="mikemeier_phpnodebridge_call")
     */
    public function callAction()
    {
        $content = array(
            'events' => array()
        );

        foreach($this->getBridge()->process($this->getRequest()) as $response){
            $eventName = $response->getEventName();
            $content['events'][$eventName] = array();

            foreach($response->getMessages() as $message){
                $content['events'][$eventName][$message->getName()] = $message->getData();
            }
        }

        return new Response(json_encode($content), 200, array(
            'Content-Type' => 'application/json'
        ));
    }

    /**
     * @return Bridge
     */
    protected function getBridge()
    {
        return $this->get('mikemeier_php_node_bridge.bridge');
    }
}