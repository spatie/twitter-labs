<?php

namespace Spatie\TwitterLabs\FilteredStream\Responses\Tweet;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class Geo extends FlexibleDataTransferObject
{
    public ?string $type;

    public ?string $bbox;

    public ?string $geometry;

    /** @var null|\Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Coordinates */
    public ?Coordinates $coordinates;
}
