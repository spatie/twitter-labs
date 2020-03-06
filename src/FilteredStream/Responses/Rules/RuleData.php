<?php

namespace Spatie\TwitterLabs\FilteredStream\Responses\Rules;

use Spatie\DataTransferObject\DataTransferObject;

class RuleData extends DataTransferObject
{
    public string $value;

    public ?string $tag;

    public string $id;
}
