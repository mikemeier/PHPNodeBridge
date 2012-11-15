<?php

 namespace mikemeier\PHPNodeBridge\Service;

 class Response
 {
     /**
      * @var string
      */
     protected $eventName;

     /**
      * @var Message[]
      */
     protected $messages = array();

     /**
      * @param string $eventName
      */
     public function __construct($eventName)
     {
         $this->eventName = $eventName;
     }

     /**
      * @return string
      */
     public function getEventName()
     {
         return $this->eventName;
     }

     /**
      * @param Message $message
      * @return Response
      */
     public function addMessage(Message $message)
     {
        $this->messages[] = $message;

         return $this;
     }

     /**
      * @return Message[]
      */
     public function getMessages()
     {
         return $this->messages;
     }
 }