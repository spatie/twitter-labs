<?php

namespace Spatie\TwitterLabs\FilteredStream;

use JsonSerializable;

class Rule implements JsonSerializable
{
    public string $value;

    public ?string $tag;

    public function __construct(string $value, ?string $tag = null)
    {
        $this->value = $value;
        $this->tag = $tag;
    }

    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'tag' => $this->tag,
        ];
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
