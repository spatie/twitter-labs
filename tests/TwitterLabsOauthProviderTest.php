<?php

namespace Spatie\TwitterLabs\Tests;

use Spatie\TwitterLabs\TwitterLabsOauthProvider;

class TwitterLabsOauthProviderTest extends TestCase
{
    /** @test */
    public function it_can_be_initialized_for_twitter_labs()
    {
        $oauthProvider = new TwitterLabsOauthProvider('client123', 'secret123');

        $this->assertEquals('https://api.twitter.com/oauth2/token', $oauthProvider->getBaseAccessTokenUrl([]));
    }
}
