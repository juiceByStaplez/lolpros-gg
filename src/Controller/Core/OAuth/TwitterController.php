<?php

namespace App\Controller\Core\OAuth;

use App\Controller\APIController;
use FOS\RestBundle\Controller\Annotations\Get;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Component\HttpFoundation\Request;
use Wohali\OAuth2\Client\Provider\TwitterResourceOwner;

class TwitterController extends APIController
{
    /**
     * Link to this controller to start the "connect" process.
     *
     * @Get("/connect/twitter")
     */
    public function connectTwitterAction()
    {
        $this->get('app.oauth.twitter')->getTemporaryCredentials();
    }

    /**
     * After going to Facebook, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml.
     *
     * @Get("/connect/twitter/check")
     */
    public function connectTwitterCheckAction(Request $request)
    {
        $token = $request->get('oauth_token');
        $verifier = $request->get('oauth_verifier');

        if (!$token || !$verifier) {
            die(dump('Something went wrong'));
        }

        try {
            // the exact class depends on which provider you're using
            /** @var TwitterResourceOwner $user */
            $user = $client->fetchUser();

            $this->get('app.oauth.twitter')->getTokenCredentials();

            // do something with all this new power!
            // e.g. $name = $user->getFirstName();
            var_dump($user);
            die;
            // ...
        } catch (IdentityProviderException $e) {
            // something went wrong!
            // probably you should return the reason to the user
        }
    }
}
