<?php

namespace Spatie\TwitterLabs\FilteredStream\Responses\Tweet;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class Attachments extends FlexibleDataTransferObject
{
    public ?array $media_keys;

    /** @var null|string[] */
    public ?array $poll_ids;
}
