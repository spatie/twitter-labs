<?php

namespace Spatie\TwitterLabs\FilteredStream\Responses\Tweet;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class Includes extends FlexibleDataTransferObject
{
    /** @var null|\Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Tweet[] */
    public ?array $tweets;

    /** @var null|\Spatie\TwitterLabs\FilteredStream\Responses\Tweet\User[] */
    public ?array $users;

    /** @var null|\Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Place[] */
    public ?array $places;

    /** @var null|\Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Media[] */
    public ?array $media;

    /** @var null|\Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Poll[] */
    public ?array $polls;
}
