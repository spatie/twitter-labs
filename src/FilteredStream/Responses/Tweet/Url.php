<?php

namespace Spatie\TwitterLabs\FilteredStream\Responses\Tweet;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class Url extends FlexibleDataTransferObject
{
	public int $start;

	public int $end;

	public string $url;

	public ?string $expanded_url;

	public ?string $display_url;

	public ?int $status;

	public ?string $title;

	public ?string $description;
}
