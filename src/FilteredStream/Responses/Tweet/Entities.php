<?php

namespace Spatie\TwitterLabs\FilteredStream\Responses\Tweet;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class Entities extends FlexibleDataTransferObject
{
	/** @var null|\Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Url[] $urls */
	public ?array $urls;

	/** @var null|\Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Annotation[] $annotations */
	public ?array $annotations;

	/** @var null|\Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Hashtag[] $hashtags */
	public ?array $hashtags;

	/** @var null|\Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Mention[] $mentions */
	public ?array $mentions;

	/** @var null|\Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Cashtag[] $cashtags */
	public ?array $cashtags;
}
