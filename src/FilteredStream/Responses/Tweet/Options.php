<?php

namespace Spatie\TwitterLabs\FilteredStream\Responses\Tweet;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class Options extends FlexibleDataTransferObject
{
    public string $position;

    public string $label;

    public string $votes;

    public string $voting_status;
}
