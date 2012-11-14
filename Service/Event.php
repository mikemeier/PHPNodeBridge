<?php

namespace mikemeier\PHPNodeBridge\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\EventDispatcher\Event as SymfonyEvent;

class Event extends SymfonyEvent
{

    /**
     * @var Request
     */
    protected $request;
    
    /**
     * @var Response 
     */
    protected $response;
    
    /**
     * @var User 
     */
    protected $user;
    
    /**
     * @var string 
     */
    protected $name;

    /**
     * @param Request $request
     * @param Response $response
     * @param User $user
     * @param $name
     */
    public function __construct(Request $request, Response $response, User $user, $name)
    {
        $this->request = $request;
        $this->response = $response;
        $this->user = $user;
        $this->name = $name;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }
    
    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
}