<?php

namespace Spatie\TwitterLabs\FilteredStream\Responses\Tweet;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class Cashtag extends FlexibleDataTransferObject
{
	public int $start;

	public int $end;

	public ?string $cashtag;
}
