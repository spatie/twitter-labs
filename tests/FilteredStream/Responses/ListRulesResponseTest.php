<?php

namespace Spatie\TwitterLabs\Tests\FilteredStream\Responses;

use Spatie\TwitterLabs\FilteredStream\Responses\Rules\ListRulesResponse;
use Spatie\TwitterLabs\Tests\TestCase;

class ListRulesResponseTest extends TestCase
{
    /** @var \Spatie\TwitterLabs\FilteredStream\Responses\Rules\ListRulesResponse */
    private $response;

    protected function setUp(): void
    {
        parent::setUp();

        $this->response = new ListRulesResponse([
            'meta' => [],
            'data' => [
                ['value' => 'rule1', 'tag' => 'tag1', 'id' => '1'],
                ['value' => 'rule2', 'tag' => 'tag2', 'id' => '2'],
            ],
        ]);
    }

    /** @test */
    public function it_can_get_an_array_of_ids()
    {
        $this->assertEquals(['1', '2'], $this->response->getRuleIds());
    }
}
