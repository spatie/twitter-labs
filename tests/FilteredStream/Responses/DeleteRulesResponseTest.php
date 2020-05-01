<?php

namespace Spatie\TwitterLabs\Tests\FilteredStream\Responses;

use Spatie\TwitterLabs\FilteredStream\Responses\Rules\DeleteRulesResponse;
use Spatie\TwitterLabs\Tests\TestCase;

class DeleteRulesResponseTest extends TestCase
{
    /** @var \Spatie\TwitterLabs\FilteredStream\Responses\Rules\DeleteRulesResponse */
    private $response;

    protected function setUp(): void
    {
        parent::setUp();

        $this->response = new DeleteRulesResponse([
            'meta' => [
                'summary' => [
                    'deleted' => 2,
                    'not_deleted' => 1,
                ],
            ],
        ]);
    }

    /** @test */
    public function it_knows_how_many_rules_were_deleted()
    {
        $this->assertEquals(2, $this->response->amountDeleted());
    }

    /** @test */
    public function it_knows_how_many_rules_were_not_deleted()
    {
        $this->assertEquals(1, $this->response->amountNotDeleted());
    }
}
