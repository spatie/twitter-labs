## **DO NOT USE: WIP**

# PHP client for Twitter Labs endpoints

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/twitter-labs.svg?style=flat-square)](https://packagist.org/packages/spatie/twitter-labs)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/spatie/twitter-labs/run-tests?label=tests)](https://github.com/spatie/twitter-labs/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/twitter-labs.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/twitter-labs)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/twitter-labs.svg?style=flat-square)](https://packagist.org/packages/spatie/twitter-labs)




This package aims to implement some of the realtime endpoints exposed by Twitter's new API, as the old realtime streams are being deprecated. 

>Twitter Developer Labs is where you’ll have early access to new API endpoints, features and versions. We’ll use Labs to test out new ideas and invite our developer community to share their feedback to help shape our roadmap.

_(from the Twitter Developer Labs website)_

Under the hood this is using [ReactPHP](https://reactphp.org) for everything async. Even though you can use the package with no knowledge of ReactPHP, it is recommended to familiarize yourself with its [event loop](https://reactphp.org/event-loop/) concept.

If you're currently using our old [twitter-streaming-api package](https://github.com/spatie/twitter-streaming-api), making the switch to this package should be easy.

## Installation

You can install the package via composer:

```bash
composer require spatie/twitter-labs
```

## Usage

Currently, only the filtered stream endpoints are implemented. We accept PRs for the other features Twitter Labs exposes.

### Filtered stream

You can find the Twitter Labs filtered stream API docs [here](https://developer.twitter.com/en/docs/labs/filtered-stream/overview). 

Twitter's filtered stream consists of one streaming endpoint that returns tweets in realtime and three endpoints to control what tweets are included in the realtime endpoint:

- GET /labs/1/tweets/stream/filter (realtime)
- POST /labs/1/tweets/stream/filter/rules (delete)
- GET /labs/1/tweets/stream/filter/rules
- POST /labs/1/tweets/stream/filter/rules (create)

To use any of these filtered stream endpoints, you'll need a `FilteredStream` instance. Use the `\Spatie\TwitterLabs\FilteredStream\FilteredStreamFactory` to create this instance for you. The factory's `create` method takes your API credentials and optionally and event loop instance.

#### Basic usage

``` php
$filteredStream = \Spatie\TwitterLabs\FilteredStream\FilteredStreamFactory::create('token', 'secret');

$filteredStream->addRule(
    new \Spatie\TwitterLabs\FilteredStream\Rule('cat has:media', 'cat photos')
)

$filteredStrean
    ->onTweet(fn (Tweet $tweet) => print($tweet->text . PHP_EOL))
    ->start();
```

The event loop will start when calling  `start`. All code after this call will not be executed.

#### Managing filters/rules

Filters for realtime streams work slightly different in Twitter Labs compared to the old Twitter API. The main difference being that filter rules are actually stored per API key and are always applied when connecting to the stream. When adding or removing rules, these changes are also applied in realtime to the streaming endpoint without having to reconnect.

The following methods are available to manage filter rules:

- `public function addRule(\Spatie\TwitterLabs\FilteredStream\Rule $rule): PromiseInterface` 
  promise resolves to `\Spatie\TwitterLabs\FilteredStream\Responses\Rules\AddRulesResponse`
- `public function addRules(\Spatie\TwitterLabs\FilteredStream\Rule ...$rules): PromiseInterface` 
  promise resolves to `\Spatie\TwitterLabs\FilteredStream\Responses\Rules\AddRulesResponse`
- `public function deleteRules(string ...$ruleIds): PromiseInterface` 
  promise resolves to `\Spatie\TwitterLabs\FilteredStream\Responses\Rules\DeleteRulesResponse`
- `public function getRules(?string ...$ids): PromiseInterface`
  promise resolves to `\Spatie\TwitterLabs\FilteredStream\Responses\Rules\ListRulesResponse`
  
⚠ You need to run the event loop to use these endpoints.

Basic example that adds a rule:

```php
use React\EventLoop\Factory;
use Spatie\TwitterLabs\FilteredStream\FilteredStreamFactory;
use Spatie\TwitterLabs\FilteredStream\Rule;

$loop = Factory::create();

FilteredStreamFactory::create('token', 'secret', $loop)
    ->asyncAddRule(new Rule('@spatie_be', 'mentioning_spatie'));

$loop->run();
```

Advanced example that gets all existing rules, then deletes them and adds a new rule.

```php
use React\EventLoop\Factory;
use Spatie\TwitterLabs\FilteredStream\FilteredStreamFactory;
use Spatie\TwitterLabs\FilteredStream\Responses\Rules\ListRulesResponse;
use Spatie\TwitterLabs\FilteredStream\Rule;

$loop = Factory::create();

FilteredStreamFactory::create('token', 'secret', $loop)
    ->asyncGetRules()
    ->then(
        function (ListRulesResponse $listRulesResponse) use ($rules, $filteredStream) {
            $ruleIds = $listRulesResponse->getRuleIds();

            if ($ruleIds) {
                $filteredStream
                    ->asyncDeleteRules(...$ruleIds)
                    ->then(fn ($r) => true, fn($e) => print($e));
            }

            $filteredStream
                ->asyncAddRules(new Rule('cat photos'))
                ->then(fn ($r) => true, fn($e) => print($e));
        },
        fn($error) => print($error),
    );

$loop->run();
```

#### Listening for tweets

You can specify a callback to be executed when Twitter hears a tweet that passes your filter rules. The callback will be executed with just one parameter: `\Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Tweet $tweet`. When everything is set-up you can use the `connect()` method to start listening.

```php
use React\EventLoop\Factory;
use Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Tweet;
use Spatie\TwitterLabs\FilteredStream\FilteredStreamFactory;

$loop = Factory::create();

FilteredStreamFactory::create('token', 'secret', $loop)
    ->onTweet(fn(Tweet $tweet) => print($tweet->text))
    ->connect();

$loop->run();
```

As a shorthand, `$filteredStream->start()` is also available. It will connect to the realtime endpoint and start the event loop, even when no event loop was given. This is especially nice when you don't need the event loop anywhere else:

```php
use React\EventLoop\Factory;
use Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Tweet;
use Spatie\TwitterLabs\FilteredStream\FilteredStreamFactory;

FilteredStreamFactory::create('token', 'secret')
    ->onTweet(fn(Tweet $tweet) => print($tweet->text))
    ->start();
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Postcardware

You're free to use this package, but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Samberstraat 69D, 2060 Antwerp, Belgium.

We publish all received postcards [on our company website](https://spatie.be/en/opensource/postcards).

## Credits

- [Alex Vanderbist](https://github.com/AlexVanderbist)
- [All Contributors](../../contributors)

## Support us

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

Does your business depend on our contributions? Reach out and support us on [Patreon](https://www.patreon.com/spatie). 
All pledges will be dedicated to allocating workforce on maintenance and new awesome stuff.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
