<?php

namespace Spatie\TwitterLabs;

use Clue\React\Buzz\Browser;
use Clue\React\Buzz\Message\MessageFactory;
use Exception;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Psr\Http\Message\ResponseInterface;
use React\EventLoop\LoopInterface;
use React\Promise\Deferred;
use React\Promise\PromiseInterface;
use RingCentral\Psr7\Request;
use Spatie\TwitterLabs\Exceptions\OauthException;
use Spatie\TwitterLabs\FilteredStream\Responses\Rules\ListRulesResponse;
use Throwable;

class Client
{
    protected TwitterLabsOauthProvider $twitterLabsOauthProvider;

    protected LoopInterface $loop;

    protected ?AccessTokenInterface $accessToken = null;

    public function __construct(TwitterLabsOauthProvider $twitterLabsOauthProvider, LoopInterface $loop)
    {
        $this->twitterLabsOauthProvider = $twitterLabsOauthProvider;

        $this->loop = $loop;
    }

    protected function authenticate(): void
    {
        if ($this->accessToken) {
            return;
        }

        try {
            $this->accessToken = $this->twitterLabsOauthProvider->getAccessToken('client_credentials');
        } catch (IdentityProviderException $exception) {
            throw OauthException::fromIdentityProviderException($exception);
        }
    }

    protected function getAccessToken(): string
    {
        return (string) $this->accessToken;
    }

    public function getStream(string $url): PromiseInterface
    {
        $this->authenticate();

        $browser = new Browser($this->loop);

        $streamingBrowser = $browser->withOptions(['streaming' => true]);

        $headers = ['Authorization' => "Bearer {$this->getAccessToken()}"];

        return $streamingBrowser->get($url, $headers);
    }

    public function getJson(string $url, array $queryParameters = []): PromiseInterface
    {
        $queryString = http_build_query($queryParameters);

        return $this->requestJson('GET', "{$url}?{$queryString}");
    }

    public function postJson(string $url, array $data = []): PromiseInterface
    {
        return $this->requestJson('POST', $url, $data);
    }

    protected function requestJson(string $method, string $url, ?array $data = null): PromiseInterface
    {
        $this->authenticate();

        $deferred = new Deferred();

        $browser = new Browser($this->loop);

        $headers = [
            'Authorization' => "Bearer {$this->getAccessToken()}",
            'Content-Type' => 'application/json',
        ];

        $body = $data ? json_encode($data) : null;

        $request = new Request($method, $url, $headers, $body);

        $browser->send($request)
            ->then(function (ResponseInterface $response) use ($deferred) {
                $data = json_decode($response->getBody(), $assoc = true, $depth = 512, JSON_THROW_ON_ERROR);

                $deferred->resolve($data);
            }, fn(Throwable $exception) => $deferred->reject($exception));

        return $deferred->promise();
    }
}
