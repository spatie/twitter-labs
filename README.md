# PHP client for Twitter Labs endpoints

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/twitter-labs.svg?style=flat-square)](https://packagist.org/packages/spatie/twitter-labs)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/spatie/twitter-labs/run-tests?label=tests)](https://github.com/spatie/twitter-labs/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/twitter-labs.svg?style=flat-square)](https://packagist.org/packages/spatie/twitter-labs)

This package aims to implement some of the realtime endpoints exposed by Twitter's new API, as the old realtime Twitter streams are being deprecated. 

>Twitter Developer Labs is where you’ll have early access to new API endpoints, features and versions. We’ll use Labs to test out new ideas and invite our developer community to share their feedback to help shape our roadmap.

_(from the Twitter Developer Labs website)_

Under the hood this is using [ReactPHP](https://reactphp.org) for everything async. Even though you can use the package with no knowledge of ReactPHP, it is recommended to familiarize yourself with its [event loop](https://reactphp.org/event-loop/) concept.

If you're currently using our old [twitter-streaming-api package](https://github.com/spatie/twitter-streaming-api), making the switch to this package should be easy.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/twitter-labs.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/twitter-labs)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require spatie/twitter-labs
```

## Usage

Currently, only the **filtered stream endpoints** are implemented. We accept PRs for the other features Twitter Labs exposes.

⚠ You'll need a Twitter Developer account with Twitter Dev Labs access enabled and an application that's enrolled in the filtered streams preview. Have a look at https://developer.twitter.com/en/labs.

### Filtered streams overview

You can find the Twitter Labs filtered stream API docs [here](https://developer.twitter.com/en/docs/labs/filtered-stream/overview). 

Twitter's filtered stream consists of one streaming endpoint that returns tweets in realtime and three endpoints to control what tweets are included in the realtime endpoint:

- `GET /labs/1/tweets/stream/filter` (realtime)
- `POST /labs/1/tweets/stream/filter/rules` (delete)
- `GET /labs/1/tweets/stream/filter/rules`
- `POST /labs/1/tweets/stream/filter/rules` (create)

To use any of these filtered stream endpoints, you'll need a `FilteredStream` instance. Use the `\Spatie\TwitterLabs\FilteredStream\FilteredStreamFactory` to create this instance for you. The factory's `create` method takes your API credentials and optionally and event loop instance.

### Basic usage: listening for Tweets

``` php
$filteredStream = \Spatie\TwitterLabs\FilteredStream\FilteredStreamFactory::create('twitter api token', 'twitter api secret');

$filteredStream->addRule(
    new \Spatie\TwitterLabs\FilteredStream\Rule('cat has:media', 'cat photos')
)

$filteredStrean
    ->onTweet(fn (Tweet $tweet) => print($tweet->text . PHP_EOL))
    ->start();
```

The event loop will start when calling `start`. All code after this call will not be executed unless something goes wrong and the event loop is stopped.

#### Managing filters/rules

Filters for realtime streams work slightly different in Twitter Labs compared to the old Twitter API. The main difference being that filter rules are actually stored per API key and are always applied when connecting to the stream. When adding or removing rules, these changes are also applied in realtime to the streaming endpoint without having to reconnect.

The following methods are available to manage filter rules:

```php
public function asyncAddRule(\Spatie\TwitterLabs\FilteredStream\Rule $rule): PromiseInterface;
public function asyncAddRules(\Spatie\TwitterLabs\FilteredStream\Rule ...$rules): PromiseInterface; 
public function asyncDeleteRules(string ...$ruleIds): PromiseInterface; 
public function asyncSetRules(\Spatie\TwitterLabs\FilteredStream\Rule ...$rules): PromiseInterface;
public function asyncGetRules(): PromiseInterface;

public function addRule(\Spatie\TwitterLabs\FilteredStream\Rule $rule): \Spatie\TwitterLabs\FilteredStream\Responses\Rules\AddRulesResponse;
public function addRules(\Spatie\TwitterLabs\FilteredStream\Rule ...$rules): \Spatie\TwitterLabs\FilteredStream\Responses\Rules\AddRulesResponse;
public function deleteRules(string ...$ruleIds): \Spatie\TwitterLabs\FilteredStream\Responses\Rules\DeleteRulesResponse;
public function setRules(\Spatie\TwitterLabs\FilteredStream\Rule ...$rules): \Spatie\TwitterLabs\FilteredStream\Responses\Rules\ListRulesResponse;
public function getRules(): \Spatie\TwitterLabs\FilteredStream\Responses\Rules\ListRulesResponse;
```
  
You can either use `async` endpoints asynchronously (don't forget to run `$filteredStream->run()` to start the event loop) or use the regular endpoint synchronously.

Basic example that adds a rule:

```php
use Spatie\TwitterLabs\FilteredStream\FilteredStreamFactory;
use Spatie\TwitterLabs\FilteredStream\Rule;

FilteredStreamFactory::create('token', 'secret')
    ->addRule(new Rule('@spatie_be', 'mentioning_spatie'));
```

Basic example that adds a rule asynchronously (can be combined with other tasks running in the same event loop):

```php
use React\EventLoop\Factory;
use Spatie\TwitterLabs\FilteredStream\FilteredStreamFactory;
use Spatie\TwitterLabs\FilteredStream\Rule;

$loop = Factory::create();

FilteredStreamFactory::create('token', 'secret', $loop)
    ->asyncAddRule(new Rule('@spatie_be', 'mentioning_spatie'));

$loop->run();
```

Example that syncs rules by getting all existing rules, then deleting them and adding the new rules.

```php
use React\EventLoop\Factory;
use Spatie\TwitterLabs\FilteredStream\FilteredStreamFactory;
use Spatie\TwitterLabs\FilteredStream\Rule;

$loop = Factory::create();

FilteredStreamFactory::create('token', 'secret', $loop)
    ->asyncSetRules(new Rule('cat photos')); // or use `setRules()` synchronously

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

## Credits

- [Alex Vanderbist](https://github.com/AlexVanderbist)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
