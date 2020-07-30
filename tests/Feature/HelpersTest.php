<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HelpersTest extends TestCase
{
    public function test_current_user()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $this->assertEquals($user->id, current_user()->id);
    }

    public function test_current_user_returns_null_if_user_is_not_authenticated()
    {
        $this->assertNull(current_user());
    }

    public function test_format_float()
    {
        $precision = config('exchange.decimal_precision');

        $this->assertEquals(sprintf("%.{$precision}f", 1.0001), format_float(1.0001));
    }
}
