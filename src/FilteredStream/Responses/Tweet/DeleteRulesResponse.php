<?php

namespace Spatie\TwitterLabs\FilteredStream\Responses\Tweet;

use Spatie\DataTransferObject\FlexibleDataTransferObject;
use Spatie\TwitterLabs\FilteredStream\Responses\AddRulesResponse\Meta;

class DeleteRulesResponse extends FlexibleDataTransferObject
{
	public Meta $meta;
}
