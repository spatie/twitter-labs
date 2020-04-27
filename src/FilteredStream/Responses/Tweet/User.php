<?php

namespace Spatie\TwitterLabs\FilteredStream\Responses\Tweet;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class User extends FlexibleDataTransferObject
{
    public string $id;

    public string $created_at;

    public string $name;

    public bool $protected;

    public string $location;

    public string $url;

    public string $description;

    public bool $verified;

    /** @var null|\Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Entities */
    public ?Entities $entities;

    public string $profile_image_url;

    public string $most_recent_tweet_id;

    public string $format;
}
