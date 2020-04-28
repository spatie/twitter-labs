<?php

namespace Spatie\TwitterLabs\FilteredStream\Responses\Tweet;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class UserStats extends FlexibleDataTransferObject
{
    public int $followers_count;

    public int $following_count;

    public int $tweet_count;

    public int $listed_count;
}
