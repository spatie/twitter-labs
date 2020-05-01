<?php

namespace Spatie\TwitterLabs\FilteredStream\Responses\Tweet;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class Entities extends FlexibleDataTransferObject
{
    /** @var null|\Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Url[] */
    public ?array $urls;

    /** @var null|\Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Annotation[] */
    public ?array $annotations;

    /** @var null|\Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Hashtag[] */
    public ?array $hashtags;

    /** @var null|\Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Mention[] */
    public ?array $mentions;

    /** @var null|\Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Cashtag[] */
    public ?array $cashtags;
}
