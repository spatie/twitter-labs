<?php

namespace Spatie\TwitterLabs\FilteredStream\Responses\Tweet;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class ContextAnnotations extends FlexibleDataTransferObject
{
	/** @var \Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Domain[] $domain */
	public array $domain;

	/** @var \Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Entity[] $entity */
	public array $entity;
}
