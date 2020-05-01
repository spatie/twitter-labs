<?php


namespace Spatie\TwitterLabs\FilteredStream;

use React\EventLoop\Factory as LoopFactory;
use React\EventLoop\LoopInterface;
use Spatie\TwitterLabs\Client;
use Spatie\TwitterLabs\TwitterLabsOauthProvider;

class FilteredStreamFactory
{
    public static function create(string $clientId, string $clientSecret, ?LoopInterface $loop = null): FilteredStream
    {
        $loop ??= LoopFactory::create();

        $client = new Client(new TwitterLabsOauthProvider($clientId, $clientSecret), $loop);

        return new FilteredStream($client, $loop);
    }
}
