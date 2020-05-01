<?php

namespace Spatie\TwitterLabs\FilteredStream\Responses\Tweet;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class Domain extends FlexibleDataTransferObject
{
    public string $id;

    public string $name;

    public string $description;
}
