<?php

namespace mikemeier\PHPNodeBridge\Transport;

use mikemeier\PHPNodeBridge\Service\Message;
use mikemeier\PHPNodeBridge\User\User;

interface TransportInterface
{

    /**
     * @param Message $message
     * @param User $user
     */
    public function sendMessageToUser(Message $message, User $user);

    /**
     * @param array $messages
     * @param User $user
     */
    public function sendMessagesToUser(array $messages, User $user);

    /**
     * @param Message $message
     * @param array $users
     */
    public function sendMessageToUsers(Message $message, array $users);

    /**
     * @param array $messages
     * @param array $users
     */
    public function sendMessagesToUsers(array $messages, array $users);

}