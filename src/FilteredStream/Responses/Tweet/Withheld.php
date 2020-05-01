<?php

namespace Spatie\TwitterLabs\FilteredStream\Responses\Tweet;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class Withheld extends FlexibleDataTransferObject
{
    public bool $copyright;

    /** @var string[] */
    public array $country_codes;

    public ?string $scope;
}
