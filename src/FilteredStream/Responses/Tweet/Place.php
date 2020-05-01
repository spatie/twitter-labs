<?php

namespace Spatie\TwitterLabs\FilteredStream\Responses\Tweet;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class Place extends FlexibleDataTransferObject
{
    public string $id;

    public string $name;

    public string $full_name;

    public string $place_type;

    public string $country_code;

    public string $country;

    public string $contained_within;

    public Geo $geo;
}
