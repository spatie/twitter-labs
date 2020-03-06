<?php

namespace Spatie\TwitterLabs\FilteredStream\Responses\Tweet;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class Annotation extends FlexibleDataTransferObject
{
	public int $start;

	public int $end;

	public ?float $probability;

	public ?string $type;

	public ?string $normalized_text;
}
