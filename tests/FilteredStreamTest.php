<?php

namespace Spatie\TwitterLabs\Tests;

use Mockery;
use React\EventLoop\Factory;
use React\Promise\Deferred;
use React\Promise\PromiseInterface;
use Spatie\TwitterLabs\Client;
use Spatie\TwitterLabs\FilteredStream\FilteredStream;
use Spatie\TwitterLabs\FilteredStream\Responses\Rules\AddRulesResponse;
use Spatie\TwitterLabs\FilteredStream\Responses\Rules\DeleteRulesResponse;
use Spatie\TwitterLabs\FilteredStream\Responses\Rules\ListRulesResponse;
use Spatie\TwitterLabs\FilteredStream\Rule;

class FilteredStreamTest extends TestCase
{
    /** @test */
    public function it_can_synchronously_get_a_list_of_stream_rules()
    {
        $loop = Factory::create();
        $deferred = new Deferred();

        $client = Mockery::mock(Client::class);
        $client->expects()
            ->getJson('https://api.twitter.com/labs/1/tweets/stream/filter/rules')
            ->andReturn($deferred->promise())
            ->once();

        $deferred->resolve([
            'data' => [['value' => 'rule1', 'id' => '1'], ['value' => 'rule2', 'id' => '2']],
            'meta' => [],
        ]);

        $filteredStream = new FilteredStream($client, $loop);

        $rules = $filteredStream->getRules();
        $this->assertInstanceOf(ListRulesResponse::class, $rules);
        $this->assertCount(2, $rules->data);
    }

    /** @test */
    public function it_can_asynchronously_get_a_list_of_stream_rules()
    {
        $loop = Factory::create();

        $client = Mockery::mock(Client::class);
        $client->expects()
            ->getJson('https://api.twitter.com/labs/1/tweets/stream/filter/rules')
            ->andReturn((new Deferred())->promise())
            ->once();

        $filteredStream = new FilteredStream($client, $loop);

        $this->assertInstanceOf(PromiseInterface::class, $filteredStream->asyncGetRules());
    }

    /** @test */
    public function it_can_synchronously_set_stream_rules()
    {
        $loop = Factory::create();
        $rulesDeferred = new Deferred();
        $deleteDeferred = new Deferred();
        $addDeferred = new Deferred();

        $rule1 = new Rule('rule1');
        $rule2 = new Rule('rule2');

        $client = Mockery::mock(Client::class);
        $client->expects()
            ->getJson('https://api.twitter.com/labs/1/tweets/stream/filter/rules')
            ->andReturn($rulesDeferred->promise())
            ->once();
        $client->expects()
            ->postJson('https://api.twitter.com/labs/1/tweets/stream/filter/rules', ['delete' => ['ids' => [3]]])
            ->andReturn($deleteDeferred->promise())
            ->once();
        $client->expects()
            ->postJson('https://api.twitter.com/labs/1/tweets/stream/filter/rules', ['add' => [$rule1, $rule2]])
            ->andReturn($addDeferred->promise())
            ->once();

        $rulesDeferred->resolve(['data' => [['value' => 'rule3', 'id' => '3']], 'meta' => []]);
        $deleteDeferred->resolve(['meta' => []]);
        $addDeferred->resolve(['data' => [['value' => 'rule1', 'id' => '1'], ['value' => 'rule2', 'id' => '2']], 'meta' => []]);

        $filteredStream = new FilteredStream($client, $loop);

        $ruleResponse = $filteredStream->addRules($rule1, $rule2);
        $this->assertInstanceOf(AddRulesResponse::class, $ruleResponse);
        $this->assertEquals('rule1', $ruleResponse->data[0]->value);
        $this->assertEquals('rule2', $ruleResponse->data[1]->value);
    }

    /** @test */
    public function it_can_synchronously_add_a_stream_rule()
    {
        $loop = Factory::create();
        $deferred = new Deferred();

        $rule = new Rule('rule1');

        $client = Mockery::mock(Client::class);
        $client->expects()
            ->postJson('https://api.twitter.com/labs/1/tweets/stream/filter/rules', ['add' => [$rule]])
            ->andReturn($deferred->promise())
            ->once();

        $deferred->resolve(['data' => [['value' => 'rule1', 'id' => '1']], 'meta' => []]);

        $filteredStream = new FilteredStream($client, $loop);

        $ruleResponse = $filteredStream->addRule($rule);
        $this->assertInstanceOf(AddRulesResponse::class, $ruleResponse);
        $this->assertEquals('rule1', $ruleResponse->data[0]->value);
    }

    /** @test */
    public function it_can_asynchronously_add_one_stream_rule()
    {
        $loop = Factory::create();

        $rule = new Rule('rule');

        $client = Mockery::mock(Client::class);
        $client->expects()
            ->postJson('https://api.twitter.com/labs/1/tweets/stream/filter/rules', ['add' => [$rule]])
            ->andReturn((new Deferred())->promise())
            ->once();

        $filteredStream = new FilteredStream($client, $loop);

        $this->assertInstanceOf(PromiseInterface::class, $filteredStream->asyncAddRule($rule));
    }

    /** @test */
    public function it_can_asynchronously_add_stream_rules()
    {
        $loop = Factory::create();

        $rule1 = new Rule('rule');
        $rule2 = new Rule('rule', 'tag');

        $client = Mockery::mock(Client::class);
        $client->expects()
            ->postJson('https://api.twitter.com/labs/1/tweets/stream/filter/rules', ['add' => [$rule1, $rule2]])
            ->once()
            ->andReturn((new Deferred())->promise());

        $filteredStream = new FilteredStream($client, $loop);

        $this->assertInstanceOf(PromiseInterface::class, $filteredStream->asyncAddRules($rule1, $rule2));
    }

    /** @test */
    public function it_can_synchronously_delete_stream_rules()
    {
        $loop = Factory::create();
        $deferred = new Deferred();

        $client = Mockery::mock(Client::class);
        $client->expects()
            ->postJson('https://api.twitter.com/labs/1/tweets/stream/filter/rules', ['delete' => ['ids' => [1, 2, 3]]])
            ->andReturn($deferred->promise())
            ->once();

        $deferred->resolve(['meta' => []]);

        $filteredStream = new FilteredStream($client, $loop);

        $response = $filteredStream->deleteRules(1, 2, 3);
        $this->assertInstanceOf(DeleteRulesResponse::class, $response);
    }

    /** @test */
    public function it_can_asynchronously_delete_stream_rules()
    {
        $loop = Factory::create();

        $client = Mockery::mock(Client::class);
        $client->expects()
            ->postJson('https://api.twitter.com/labs/1/tweets/stream/filter/rules', ['delete' => ['ids' => [1, 2, 3]]])
            ->andReturn((new Deferred())->promise())
            ->once();

        $filteredStream = new FilteredStream($client, $loop);

        $this->assertInstanceOf(PromiseInterface::class, $filteredStream->asyncDeleteRules(1, 2, 3));
    }
}
