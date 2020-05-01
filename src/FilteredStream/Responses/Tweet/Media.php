<?php

namespace Spatie\TwitterLabs\FilteredStream\Responses\Tweet;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class Media extends FlexibleDataTransferObject
{
    public string $media_key;

    public int $height;

    public int $width;

    public ?string $url;

    public ?string $preview_image_url;

    public ?int $duration_ms;

    public string $type;
}
