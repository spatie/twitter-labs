<?php

namespace Spatie\TwitterLabs\FilteredStream\Responses\Tweet;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class Includes extends FlexibleDataTransferObject
{
    /** @var \Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Tweet[] $tweets */
	public array $tweets;

    /** @var \Spatie\TwitterLabs\FilteredStream\Responses\Tweet\User[] $users */
    public array $users;

	/** @var \Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Place[] $places */
	public array $places;

	/** @var \Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Media[] $media */
	public array $media;

	/** @var \Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Poll[] $polls */
	public array $polls;
}
