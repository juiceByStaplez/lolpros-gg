<?php

namespace App\Controller\Core\OAuth;

use App\Controller\APIController;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\Request;

class DiscordController extends APIController
{
    /**
     * Link to this controller to start the "connect" process.
     *
     * @QueryParam(name="opener", nullable=false)
     * @Get("/connect/discord")
     */
    public function connectDiscordAction(ParamFetcher $paramFetcher, ClientRegistry $registry)
    {
        return $registry->getClient('discord')
            ->redirect(['identify', 'email'], [
                'state' => json_encode(['opener' => $paramFetcher->get('opener')]),
            ]);
    }

    /**
     * @Get("/connect/discord/check")
     */
    public function connectDiscordCheckAction(Request $request)
    {
        die(dump($request));
    }
}
