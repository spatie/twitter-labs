<?php

namespace Spatie\TwitterLabs;

use League\OAuth2\Client\Provider\GenericProvider;

class TwitterLabsOauthProvider extends GenericProvider
{
    public function __construct(string $clientId, string $clientSecret)
    {
        parent::__construct([
            'clientId' => $clientId,
            'clientSecret' => $clientSecret,
            'urlAccessToken' => 'https://api.twitter.com/oauth2/token',
            'redirectUri' => 'http://my.example.com/your-redirect-url/',
            'urlAuthorize' => 'http://service.example.com/authorize',
            'urlResourceOwnerDetails' => 'http://service.example.com/resource',
            'responseError' => 'errors',
        ]);
    }
}
