<?php

namespace Spatie\TwitterLabs\Tests\FilteredStream\Responses;

use Spatie\TwitterLabs\FilteredStream\Responses\Rules\AddRulesResponse;
use Spatie\TwitterLabs\Tests\TestCase;

class AddRulesResponseTest extends TestCase
{
    /** @var \Spatie\TwitterLabs\FilteredStream\Responses\Rules\AddRulesResponse */
    private $response;

    protected function setUp(): void
    {
        parent::setUp();

        $this->response = new AddRulesResponse([
            'meta' => [
                'summary' => [
                    'created' => 2,
                    'not_created' => 1,
                ],
            ],
            'errors' => ['ANGRY'],
            'data' => [
                ['value' => 'rule1', 'tag' => 'tag1', 'id' => '1'],
                ['value' => 'rule2', 'tag' => 'tag2', 'id' => '2'],
            ],
        ]);
    }

    /** @test */
    public function it_knows_how_many_rules_were_created()
    {
        $this->assertEquals(2, $this->response->amountCreated());
    }

    /** @test */
    public function it_knows_how_many_rules_were_not_created()
    {
        $this->assertEquals(1, $this->response->amountNotCreated());
    }

    /** @test */
    public function it_knows_whether_it_has_errors()
    {
        $this->assertTrue($this->response->hasErrors());
        $this->assertFalse((new AddRulesResponse(['meta' => [], 'errors' => []]))->hasErrors());
    }
}
