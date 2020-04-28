<?php

namespace Spatie\TwitterLabs\FilteredStream\Responses\Tweet;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class Stats extends FlexibleDataTransferObject
{
	public int $retweet_count;

	public int $reply_count;

	public int $like_count;

	public int $quote_count;
}
