<?php

namespace Spatie\TwitterLabs\FilteredStream\Responses\Tweet;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class Poll extends FlexibleDataTransferObject
{
    public string $id;

    public Options $options;

    public int $end_datetime;

    public string $duration_minutes;
}
