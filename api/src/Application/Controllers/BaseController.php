<?php

namespace App\Application\Controllers;

use Psr\Http\Message\ResponseInterface as Response;

class BaseController
{
    /**
     * Json encodes the data and send them back to the client.
     *
     * @param Response $response The response.
     * @param mixed $data Anything you want to reply to the client.
     *
     * @return Response
     */
    protected function jsonResponse(Response $response, $data): Response
    {
        $body = json_encode($data);
        $stream = $response->getBody();
        $stream->write($body);

        return $response
            ->withHeader("Content-type", "application/json;charset=utf-8")
            ->withBody($stream);
    }
}