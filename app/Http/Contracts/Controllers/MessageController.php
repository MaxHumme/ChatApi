<?php
namespace MaxHumme\ChatApi\Http\Contracts\Controllers;

/**
 * Interface MessageController
 *
 * @author Max Humme <max@humme.nl>
 */
interface MessageController
{
    /**
     * Gets the messages for the user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMessagesForUser();

    /**
     * Sends the message to the user with $username.
     *
     * @param string $toUsername
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessageTo(string $toUsername);
}
