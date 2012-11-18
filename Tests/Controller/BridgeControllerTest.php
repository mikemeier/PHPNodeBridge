<?php

namespace mikemeier\PHPNodeBridge\Tests\Controller;

use mikemeier\PHPNodeBridge\Controller\BridgeController;
use mikemeier\PHPNodeBridge\Service\Config;
use mikemeier\PHPNodeBridge\Identification\IdentificationStrategyInterface;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;
use Symfony\Component\DomCrawler\Crawler;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

use Symfony\Component\DependencyInjection\ContainerInterface;

class BridgeControllerTest extends WebTestCase
{

    const SOCKET_ID = 'testSocketId';
    const IDENTIFICATION = 'testIdentification';
    const CALL_ROUTE_NAME = 'mikemeier_phpnodebridge_call';

    /**
     * @covers mikemeier\PHPNodeBridge\Controller\BridgeController::callAction
     */
    public function testCallUserContainerAddUserAction()
    {
        $eventResponse = $this->validateAllInOne('user.connection', array(), 'bridge', 'string');

        $this->assertContains(self::SOCKET_ID, $eventResponse, 'Socket-Id not found');
        $this->assertContains('socket', $eventResponse, 'User not found');
        $this->assertContains('add', $eventResponse, 'Add not found');
    }

    /**
     * @covers mikemeier\PHPNodeBridge\Controller\BridgeController::callAction
     */
    public function testCallUserContainerRemoveUserAction()
    {
        $eventResponse = $this->validateAllInOne('user.disconnection', array(), 'bridge', 'string');

        $this->assertContains(self::SOCKET_ID, $eventResponse, 'Socket-Id not found');
        $this->assertContains('socket', $eventResponse, 'User not found');
        $this->assertContains('remove', $eventResponse, 'Remove not found');
    }

    /**
     * @covers mikemeier\PHPNodeBridge\Controller\BridgeController::callAction
     */
    public function testCallClearUserContainer()
    {
        $eventResponse = $this->validateAllInOne('server.restart', array(), 'bridge', 'string');

        $this->assertContains('clear', $eventResponse, 'clear not found');
        $this->assertContains('usercontainer', $eventResponse, 'usercontainer not found');
    }

    /**
     * @covers mikemeier\PHPNodeBridge\Controller\BridgeController::callAction
     */
    public function testCallReturnValueAction()
    {
        $eventResponse = $this->validateAllInOne('returnvalue', array('paraA', 'paraB'), 'returnvalue', 'array');
        $this->assertSame(array('paraA', 'paraB'), $eventResponse, 'Parameters not found');
    }

    /**
     * @param string $eventName
     * @param array $eventParameters
     * @param string $eventResponseName
     * @param string $eventResponseType
     * @return mixed
     */
    protected function validateAllInOne($eventName, array $eventParameters, $eventResponseName, $eventResponseType)
    {
        $parameters = $this->getEventParameters($eventName, $eventParameters);
        $client = $this->request($parameters);

        $content = $this->validateResponse($client->getResponse());
        $eventResponses = $this->validateEvent($eventName, $content);

        return $this->validateEventResponse($eventResponseName, $eventResponseType, $eventResponses);
    }

    /**
     * @param Response $response
     * @return mixed
     */
    protected function validateResponse(Response $response)
    {
        $this->assertEquals('application/json', $response->headers->get('Content-Type'), 'Response is not application/json: '. $response->getContent());

        $content = @json_decode($response->getContent(), true);

        $this->assertInternalType('array', $content, 'Content is not valid json');
        $this->assertArrayHasKey('events', $content, 'Key "events" not found in Response');

        return $content;
    }

    /**
     * @param $eventName
     * @param $content
     * @return array
     */
    protected function validateEvent($eventName, $content)
    {
        $events = $content['events'];
        $this->assertArrayHasKey($eventName, $events, 'Key "'. $eventName .'" not found in events Response');

        $eventResponses = $content['events'][$eventName];
        $this->assertInternalType('array', $eventResponses, 'Event-Array from Event "'. $eventName .'" is not an array');

        return $content['events'][$eventName];
    }

    /**
     * @param string $eventResponseName
     * @param string $type
     * @param array $eventResponses
     * @return mixed
     */
    protected function validateEventResponse($eventResponseName, $type, array $eventResponses)
    {
        $this->assertArrayHasKey($eventResponseName, $eventResponses, 'EventResponse "'. $eventResponseName .'" not found');
        $eventResponse = $eventResponses[$eventResponseName];

        $this->assertInternalType($type, $eventResponse, 'EventResponse for "'. $eventResponseName .'" is not type "'. $type .'"');

        return $eventResponse;
    }

    /**
     * @param array $parameters
     * @param string $method
     * @return Client
     */
    protected function request(array $parameters, $method = "POST")
    {
        $client = $this->getClient();
        $client->request(
            $method,
            $this->getRouter()->generate(self::CALL_ROUTE_NAME),
            $parameters
        );

        return $client;
    }

    /**
     * @param string $eventName
     * @param array $parameters
     * @return array
     */
    protected function getEventParameters($eventName, array $parameters = array())
    {
        return array(
            'socketId' => self::SOCKET_ID,
            'identification' => $this->getIdentificationStrategy()->getEncryptedIdentification(self::IDENTIFICATION),
            'events' => json_encode(array(
                array(
                    'name' => $eventName,
                    'parameters' => $parameters
                )
            ))
        );
    }

    /**
     * @param array $options
     * @param array $server
     * @return Client
     */
    protected function getClient(array $options = array(), array $server = array())
    {
        return static::createClient($options, $server);
    }

    /**
     * @return ContainerInterface
     */
    protected function getContainer()
    {
        return $this->getClient()->getContainer();
    }

    /**
     * @return IdentificationStrategyInterface
     */
    protected function getIdentificationStrategy()
    {
        return $this->getContainer()->get('mikemeier_php_node_bridge.identificationstrategy');
    }

    /**
     * @return Router
     */
    protected function getRouter()
    {
        return $this->getContainer()->get('router');
    }
}
