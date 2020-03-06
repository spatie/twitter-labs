<?php

namespace Spatie\TwitterLabs\FilteredStream\Responses\Tweet;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class Hashtag extends FlexibleDataTransferObject
{
	public int $start;

	public int $end;

	public ?string $hashtag;
}
