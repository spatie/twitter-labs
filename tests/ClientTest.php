<?php

namespace Spatie\TwitterLabs\Tests;

use React\EventLoop\Factory;
use Spatie\TwitterLabs\Client;
use Spatie\TwitterLabs\TwitterLabsOauthProvider;

class ClientTest extends TestCase
{
    /** @test */
    public function it_can_be_initialized()
    {
        $loop = Factory::create();
        $twitterLabsOauthProvider = new TwitterLabsOauthProvider('client123', 'secret123');

        $client = new Client($twitterLabsOauthProvider, $loop);

        $this->assertInstanceOf(Client::class, $client);
    }

    /** @test */
    public function it_can_send_a_request_to_a_json_endpoint()
    {
        $this->markTestIncomplete('TODO: Implement tests somehow');
    }

    /** @test */
    public function it_can_stream_a_long_running_request_endpoint()
    {
        $this->markTestIncomplete('TODO: Implement tests somehow');
    }
}
