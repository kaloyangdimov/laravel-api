<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UsersControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_edits_name_and_email()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/user/update/'.$user->id, ['name' => 'Test Test', 'email' => 'email@email.bg']);
        $this->assertTrue((User::find($user->id)->name == 'Test Test') && (User::find($user->id)->email == 'email@email.bg'));

        $response->assertStatus(302);
    }

    public function test_user_deletes_email()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/user/update/'.$user->id, ['name' => 'Test Test', 'email' => '']);

        $response->assertSessionHasErrors(
            [
                'email' =>  'The email field is required.'
            ]
        );

        $response->assertStatus(302);
    }

    public function test_user_updates_another_user_as_not_admin()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();

        $response = $this->actingAs($user)->post('/user/update/'.$user2->id, ['name' => 'Test Test', 'email' => 'awd@asd.bg']);

        $response->assertStatus(403);
    }

    public function test_user_updates_another_user_as_admin()
    {
        $user = User::factory()->create();
        $admin = User::factory()->create(['is_admin' => 1]);

        $response = $this->actingAs($admin)->post('/user/update/'.$user->id, ['name' => 'Test updatedbyadmin', 'email' => 'awd@asd.bg']);

        $this->assertTrue(User::find($user->id)->name == 'Test updatedbyadmin');
        $response->assertStatus(302);
    }

    public function test_user_can_delete_self()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/user/delete/'.$user->id);

        $this->assertTrue(!is_null(User::where('id', $user->id)->withTrashed()->first()->deleted_at));

        $response->assertRedirect('/');
    }

    public function test_user_cannot_set_self_as_admin_when_not_admin()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/user/update/'.$user->id, ['name' => $user->name, 'email' => $user->email, 'is_admin' => 1]);

        $response->assertSessionHasErrors(
            [
                'is_admin' => 'Error updating user'
            ]
        );

        $response->assertRedirect('/');
    }
}
