<?php


namespace Spatie\TwitterLabs\FilteredStream;


use JsonException;
use Psr\Http\Message\ResponseInterface;
use React\EventLoop\LoopInterface;
use React\Promise\Deferred;
use React\Promise\PromiseInterface;
use Spatie\TwitterLabs\Client;
use Spatie\TwitterLabs\FilteredStream\Responses\Rules\AddRulesResponse;
use Spatie\TwitterLabs\FilteredStream\Responses\Rules\DeleteRulesResponse;
use Spatie\TwitterLabs\FilteredStream\Responses\Rules\ListRulesResponse;
use Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Tweet;
use Throwable;

class FilteredStream
{
    protected Client $client;

    /** @var callable */
    protected $onTweet;

    /** @var \React\EventLoop\LoopInterface */
    protected LoopInterface $loop;

    public function __construct(Client $client, LoopInterface $loop)
    {
        $this->client = $client;

        $this->loop = $loop;
    }

    public function asyncAddRule(Rule $rule): PromiseInterface
    {
        return $this->asyncAddRules($rule);
    }

    public function addRule(Rule $rule): AddRulesResponse
    {
        return $this->await($this->asyncAddRule($rule));
    }

    public function asyncAddRules(Rule ...$rules): PromiseInterface
    {
        $deferred = new Deferred();

        $data = ['add' => $rules];

        $this->client
            ->postJson('https://api.twitter.com/labs/1/tweets/stream/filter/rules', $data)
            ->then(function (array $data) use ($deferred) {
                try {
                    $addRulesResponse = new AddRulesResponse($data);

                    $deferred->resolve($addRulesResponse);
                } catch (Throwable $exception) {
                    $deferred->reject($exception);
                }
            }, fn(\Exception $exception) => $deferred->reject($exception));

        return $deferred->promise();
    }

    public function addRules(Rule ...$rules): AddRulesResponse
    {
        return $this->await($this->asyncAddRules(...$rules));
    }

    public function asyncDeleteRules(string ...$ruleIds): PromiseInterface
    {
        $deferred = new Deferred();

        $data = ['delete' => ['ids' => $ruleIds]];

        $this->client
            ->postJson('https://api.twitter.com/labs/1/tweets/stream/filter/rules', $data)
            ->then(function (array $data) use ($deferred) {
                try {
                    $deleteRulesResponse = new DeleteRulesResponse($data);

                    $deferred->resolve($deleteRulesResponse);
                } catch (Throwable $exception) {
                    $deferred->reject($exception);
                }
            }, fn(Throwable $exception) => $deferred->reject($exception));

        return $deferred->promise();
    }

    public function deleteRules(string ...$ruleIds): DeleteRulesResponse
    {
        return $this->await($this->asyncDeleteRules(...$ruleIds));
    }

    public function asyncSetRules(Rule ...$rules): PromiseInterface
    {
        $deferred = new Deferred();

        $this->asyncGetRules()
            ->then(fn(ListRulesResponse $listRulesResponse) => $listRulesResponse->getRuleIds())
            ->then(fn(array $ruleIds) => empty($ruleIds) ? null : $this->asyncDeleteRules(...$ruleIds))
            ->then(fn() => $this->asyncAddRules(...$rules))
            ->then(fn(AddRulesResponse $addRulesResponse) => $deferred->resolve($addRulesResponse))
            ->otherwise(fn(Throwable $exception) => $deferred->reject($exception));

        return $deferred->promise();
    }

    public function setRules(Rule ...$rules): AddRulesResponse
    {
        return $this->await($this->asyncSetRules(...$rules));
    }

    public function asyncGetRules(): PromiseInterface
    {
        $deferred = new Deferred();

        $this->client
            ->getJson('https://api.twitter.com/labs/1/tweets/stream/filter/rules')
            ->then(function (array $data) use ($deferred) {
                try {
                    $listRulesResponse = new ListRulesResponse($data);

                    $deferred->resolve($listRulesResponse);
                } catch (Throwable $exception) {
                    $deferred->reject($exception);
                }
            }, fn(\Exception $exception) => $deferred->reject($exception));

        return $deferred->promise();
    }

    public function getRules(): ListRulesResponse
    {
        return $this->await($this->asyncGetRules());
    }

    public function onTweet(callable $onTweet): self
    {
        $this->onTweet = $onTweet;

        return $this;
    }

    public function connect(): self
    {
        $expansions = 'author_id,referenced_tweets.id,referenced_tweets.id.author_id,attachments.media_keys';

        $this->client
            ->getStream("https://api.twitter.com/labs/1/tweets/stream/filter?expansions={$expansions}&format=detailed")
            ->then(function (ResponseInterface $response) {
                /* @var $stream \React\Stream\ReadableStreamInterface */
                $stream = $response->getBody();

                $stream->on('data', fn(string $data) => $this->processStreamData($data));

                $stream->on('error', fn(Throwable $error) => print('Error: ' . $error->getMessage() . PHP_EOL));

                $stream->on('close', fn() => print('Connection closed.' . PHP_EOL));

            }, function (Throwable $error) {
                throw $error;
            });

        return $this;
    }

    /**
     * Convenience method in case you don't want to connect and run your loop manually.
     */
    public function start(): void
    {
        $this->connect();

        $this->loop->run();
    }

    protected function processStreamData(string $data)
    {
        if (is_null($this->onTweet)) {
            return;
        }

        $chunks = explode("\n", $data);
        $chunks = array_map(fn(string $chunk) => trim($chunk), $chunks);
        $chunks = array_filter($chunks);

        foreach ($chunks as $chunk) {
            try {
                $data = json_decode($chunk, $assoc = true, $depth = 512, JSON_THROW_ON_ERROR);

                dump($data);

                $tweet = new Tweet(array_merge(
                    $data['data'],
                    ['matching_rules' => $data['matching_rules']],
                    ['includes' => $data['includes']],
                ));

                ($this->onTweet)($tweet);
            } catch (JsonException $jsonException) {
            }
        }
    }

    /**
     * @param \React\Promise\PromiseInterface $promise
     *
     * @return mixed
     */
    protected function await(PromiseInterface $promise)
    {
        $promise->then(function ($result) use (&$promiseResult) {
            $promiseResult = $result;

            $this->loop->stop();
        });

        $this->loop->run();

        return $promiseResult;
    }
}
