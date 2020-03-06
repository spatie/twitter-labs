<?php

namespace Spatie\TwitterLabs\FilteredStream\Responses\Rules;

use Spatie\DataTransferObject\DataTransferObject;

class DeleteRulesResponse extends DataTransferObject
{
    public array $meta;

    public function amountDeleted(): int
    {
        return $this->meta['summary']['deleted'];
    }

    public function amountNotDeleted(): int
    {
        return $this->meta['summary']['not_deleted'];
    }
}
