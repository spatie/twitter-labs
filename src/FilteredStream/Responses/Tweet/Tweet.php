<?php

namespace Spatie\TwitterLabs\FilteredStream\Responses\Tweet;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class Tweet extends FlexibleDataTransferObject
{
	public string $id;

	public string $created_at;

	public string $text;

	public string $author_id;

	public ?string $in_reply_to_user_id;

	/** @var null|\Spatie\TwitterLabs\FilteredStream\Responses\Tweet\ReferencedTweet[] $referenced_tweets */
	public ?array $referenced_tweets;

    /** @var null|\Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Attachments */
    public ?Attachments $attachments;

    /** @var null|\Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Geo */
    public ?Geo $geo;

	/** @var null|\Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Entities */
	public ?Entities $entities;

    /** @var null|\Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Withheld */
    public ?Withheld $withheld;

    /** @var null|\Spatie\TwitterLabs\FilteredStream\Responses\Tweet\TweetStats */
	public ?TweetStats $stats;

	public ?bool $possibly_sensitive;

	public ?string $lang;

	public ?string $source;

    /** @var null|\Spatie\TwitterLabs\FilteredStream\Responses\Tweet\ContextAnnotation[] */
    public ?array $context_annotations;

	public ?string $format;

    /** @var null|\Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Includes */
    public ?Includes $includes;

	/** @var null|\Spatie\TwitterLabs\FilteredStream\Responses\Tweet\MatchingRule[] $matching_rules */
	public ?array $matching_rules;
}
