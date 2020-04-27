<?php

use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use Spatie\TwitterLabs\FilteredStream\FilteredStream;
use Spatie\TwitterLabs\FilteredStream\Responses\Rules\ListRulesResponse;
use Spatie\TwitterLabs\FilteredStream\Responses\Rules\RuleData;
use Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Tweet;
use Spatie\TwitterLabs\FilteredStream\Rule;
use Spatie\TwitterLabs\FilteredStream\FilteredStreamFactory;

require_once "vendor/autoload.php";

$loop = Factory::create();

$filteredStream = FilteredStreamFactory::create('8ZkFfeOoTD15HFsFm3uuyGqu7', 'Uj6jeplSVW0xm9DqeWpBNnoe1KAQLsw86suyj542ICYPxfTCmd', $loop);

// Connecting to stream
$filteredStream->onTweet(fn(Tweet $tweet) => print($tweet->text . PHP_EOL));
$filteredStream->connect();

$loop->run();
// $filteredStream->start();





// Rules

function getRules(
    LoopInterface $loop,
    FilteredStream $filteredStream
) {
    $filteredStream->asyncGetRules()
        ->then(function (ListRulesResponse $rulesResponse) use ($loop) {
        dump($rulesResponse);

        $loop->stop();
    });

    $loop->run();

    // rest
}

//getRules($loop, $filteredStream);


// Real life example, but bad

function listenToRules(
    LoopInterface $loop,
    FilteredStream $filteredStream
) {
    /** @var Rule[] $rules */
    $rules = [
        new Rule('cat has:media', 'cat photos'),
        new Rule('meme has:images', 'memes'),
        new Rule('coronavirus', 'serious tweets'),
    ];


    $filteredStream->asyncSetRules(...$rules)->then(fn() => print('----- DONE ----'), fn($e) => print('yikes'));

    $loop->addPeriodicTimer(10, function () use ($rules, $filteredStream) {
        $filteredStream
            ->asyncGetRules()
            ->then(
                function (ListRulesResponse $listRulesResponse) use ($rules, $filteredStream) {
                    $ruleIds = array_map(fn(RuleData $ruleData) => $ruleData->id, $listRulesResponse->data ?? []);

                    if ($ruleIds) {
                        $filteredStream
                            ->asyncDeleteRules(...$ruleIds)
                            ->then(fn($r) => true, fn($e) => dump($e));
                    }

                    $randomRule = $rules[array_rand($rules)];

                    dump($randomRule->tag);

                    $filteredStream
                        ->asyncAddRules($randomRule)
                        ->then(fn($r) => true, fn($e) => dump($e));
                },
                fn($error) => dump($error),
                );
    });

    $filteredStream->onTweet(fn(Tweet $tweet) => print($tweet->text . PHP_EOL));

    $filteredStream->onTweet(function (Tweet $tweet) {
        return print($tweet->matching_rules[0]->tag . PHP_EOL);
    });

//    $filteredStream->connect();
//
//    $loop->run();

    $filteredStream->start();
}

//listenToRules($loop, $filteredStream);




// Syncing rules
