<?php

namespace Spatie\TwitterLabs\Tests;

use Spatie\TwitterLabs\FilteredStream\Rule;

class RuleTest extends TestCase
{
    /** @test */
    public function a_rule_can_consist_of_a_filter()
    {
        $rule = new Rule('#filter');

        $this->assertEquals(['value' => '#filter', 'tag' => null], $rule->toArray());
    }
    /** @test */
    public function a_rule_can_consist_of_a_filter_and_a_tag()
    {
        $ruleWithTag = new Rule('#filter', 'id:123');

        $this->assertEquals(['value' => '#filter', 'tag' => 'id:123'], $ruleWithTag->toArray());
    }

    /** @test */
    public function a_rule_is_json_serializable()
    {
        $ruleWithTag = new Rule('#filter', 'id:123');

        $this->assertEquals(json_encode(['value' => '#filter', 'tag' => 'id:123']), json_encode($ruleWithTag));
    }
}
