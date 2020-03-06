<?php

namespace Spatie\TwitterLabs\FilteredStream\Responses\Tweet;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class Entity extends FlexibleDataTransferObject
{
	public string $id;

	public string $name;

	public string $description;
}
