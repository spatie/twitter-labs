<?php

namespace Spatie\TwitterLabs\FilteredStream\Responses\Tweet;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class Stats extends FlexibleDataTransferObject
{
	public string $retweet_count;

	public string $reply_count;

	public string $like_count;

	public string $quote_count;
}
