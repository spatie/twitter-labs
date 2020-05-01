<?php

namespace Spatie\TwitterLabs\Tests;

use React\EventLoop\Factory;
use Spatie\TwitterLabs\Client;
use Spatie\TwitterLabs\FilteredStream\FilteredStream;
use Spatie\TwitterLabs\FilteredStream\FilteredStreamFactory;
use Spatie\TwitterLabs\TwitterLabsOauthProvider;

class FilteredStreamFactoryTest extends TestCase
{
    /** @test */
    public function it_can_create_a_filtered_stream()
    {
        $loop = Factory::create();

        $filteredStream = FilteredStreamFactory::create('client123', 'secret123', $loop);

        $this->assertInstanceOf(FilteredStream::class, $filteredStream);
    }

    /** @test */
    public function it_can_create_a_filtered_stream_with_a_given_event_loop()
    {
        $loop = Factory::create();

        $filteredStream = FilteredStreamFactory::create('client123', 'secret123', $loop);

        $this->assertInstanceOf(FilteredStream::class, $filteredStream);
    }
}
