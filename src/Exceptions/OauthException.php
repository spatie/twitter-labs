<?php

namespace Spatie\TwitterLabs\Exceptions;

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class OauthException extends \Exception
{
    public function __construct(string $responseBody)
    {
        parent::__construct("OAuth error. Are the client ID and client secret correct? Twitter API returned the following response:\n\r{$responseBody}");
    }

    public static function fromIdentityProviderException(IdentityProviderException $identityProviderException)
    {
        $responseBody = $identityProviderException->getResponseBody();

        if (is_array($responseBody)) {
            $responseBody = json_encode($responseBody, JSON_PRETTY_PRINT);
        }

        return new self($responseBody);
    }
}
