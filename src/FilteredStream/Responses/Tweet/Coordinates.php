<?php

namespace Spatie\TwitterLabs\FilteredStream\Responses\Tweet;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class Coordinates extends FlexibleDataTransferObject
{
    public string $type;

    /** @var null|float[] */
    public ?array $coordinates;

    public ?string $properties;
}
