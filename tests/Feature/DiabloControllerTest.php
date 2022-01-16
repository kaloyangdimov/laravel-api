<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class DiabloControllerTest extends TestCase
{
    use RefreshDatabase;

    const ACCESS_TOKEN = 'EU4FjlBzdnrJSvA4VDWeLcAl2Sv8Yovtb7';

    public function test_list_diablo_characters_with_valid_token_succeeds()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->withSession(['blizzAccessToken' => self::ACCESS_TOKEN])->get('/getApiAccount');

        $response->assertStatus(200);
        $response->assertSeeText('Cringe');
    }

    public function test_list_diablo_characters_with_invalid_token_fails()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->withSession(['blizzAccessToken' => '123'])->get('/getApiAccount');

        $response->assertStatus(302);
        $response->assertRedirect('/dashboard');
        $response->assertSessionHasErrors(
            ['token']
        );
    }
}
