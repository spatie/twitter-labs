<?php


namespace Spatie\TwitterLabs\FilteredStream\Responses\Rules;

use Spatie\DataTransferObject\DataTransferObject;

class ListRulesResponse extends DataTransferObject
{
    /** @var null|\Spatie\TwitterLabs\FilteredStream\Responses\Rules\RuleData[] */
    public ?array $data;

    public array $meta;

    public function getRuleIds(): array
    {
        return array_map(fn(RuleData $ruleData) => $ruleData->id, $this->data ?? []);
    }
}
