<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlizzardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_character_get_fails_with_strings()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/view/char/test/test');
        $response->assertStatus(302);

        $response->assertSessionHasErrors(
            [
                'charID'  => 'Character ID must be an integer',
                'realmID' => 'Realm ID must be an integer'
            ]
        );
    }

    public function test_character_get_fails_with_missing_route_params()
    {
        $response = $this->get('/view/char/');
        $response->assertStatus(404);
    }

    public function test_character_get_fails_as_guest()
    {
        $this->assertGuest();
        $response = $this->get('/view/char/554/154464592');
        $response->assertStatus(302);
    }

    public function test_create_authorization_link_with_logged_user()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/authAccess');
        $this->assertTrue(
            $response->getTargetUrl() === 'https://eu.battle.net/oauth/authorize?auth_flow=auth_code&scope=wow.profile&client_id=c5571007a7f6411ea1f08e5ea315241e&response_type=code&redirect_uri=http%3A%2F%2F127.0.0.1%3A8000%2Fcreatetoken'
        );
    }
}
