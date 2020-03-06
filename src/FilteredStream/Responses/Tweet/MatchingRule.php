<?php

namespace Spatie\TwitterLabs\FilteredStream\Responses\Tweet;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class MatchingRule extends FlexibleDataTransferObject
{
	public string $id;

	public ?string $tag;
}
