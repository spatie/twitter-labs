<?php

namespace Spatie\TwitterLabs\FilteredStream\Responses\Tweet;

use Spatie\DataTransferObject\FlexibleDataTransferObject;

class ContextAnnotation extends FlexibleDataTransferObject
{
    /** @var \Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Domain */
    public Domain $domain;

    /** @var \Spatie\TwitterLabs\FilteredStream\Responses\Tweet\Entity */
    public Entity $entity;
}
