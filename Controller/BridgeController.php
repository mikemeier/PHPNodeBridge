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

        $responses = $this->getBridge()->process($this->getRequest());
        foreach($responses as $response){
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
     * @Route("/userlist", name="mikemeier_phpnodebridge_userlist")
     * @Template
     */
    public function userlistAction()
    {
        foreach($this->getBridge()->getUserContainer()->getAll() as $user)
        {
            $this->getBridge()->sendMessageToUser(new \mikemeier\PHPNodeBridge\Service\Message(), $user);
        }

        return array(
            'users' => $this->getBridge()->getUserContainer()->getAll()
        );
    }

    /**
     * @return Bridge
     */
    protected function getBridge()
    {
        return $this->get('mikemeier_php_node_bridge.bridge');
    }
}