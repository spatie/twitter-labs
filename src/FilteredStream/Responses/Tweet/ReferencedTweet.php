<?php

namespace Spatie\TwitterLabs\FilteredStream\Responses\Tweet;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class ReferencedTweet extends FlexibleDataTransferObject
{
	public string $type;

	public string $id;
}
