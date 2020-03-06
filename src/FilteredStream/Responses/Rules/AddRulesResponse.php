<?php


namespace Spatie\TwitterLabs\FilteredStream\Responses\Rules;

use Spatie\DataTransferObject\DataTransferObject;

class AddRulesResponse extends DataTransferObject
{
    /** @var null|\Spatie\TwitterLabs\FilteredStream\Responses\Rules\RuleData[] */
    public ?array $data;

    public array $meta;

    public ?array $errors;

    public function hasErrors(): bool
    {
        return ! is_null($this->errors);
    }

    public function amountCreated(): int
    {
        return $this->meta['summary']['created'];
    }

    public function amountNotCreated(): int
    {
        return $this->meta['summary']['not_created'];
    }
}
