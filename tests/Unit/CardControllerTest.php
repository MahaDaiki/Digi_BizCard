<?php

namespace Tests\Unit;

use App\Models\Card;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CardControllerTest extends TestCase
{
    use DatabaseTransactions;
    public function test_get_all_cards()
    {
        $response = $this->get('/api/cards');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'cards'
            ]);
    }

    public function test_store_card()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $file = UploadedFile::fake()->image('logo.jpg');
        $data = [
            'logo' => $file,
            'title' => 'Test Title',
            'slogan' => 'Test Slogan',
            'phonenumber' => '1234567890',
            'email' => 'test@example.com',
            'address' => 'Test Address',
            'website' => 'http://www.example.com',
        ];

        $response = $this->actingAs($user)->post('/api/cards/add', $data);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Card Added Successfully'
            ]);
    }

 
    public function test_show_card()
    {
        $user = User::factory()->create();
        $card = Card::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/api/cards/' . $card->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'card'
            ]);
    }

  
    public function test_edit_card()
    {
        $user = User::factory()->create();
        $card = Card::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/api/cards/' . $card->id . '/edit');;

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'card'
            ]);
    }

    public function test_update_card()
    {
        $user = User::factory()->create();
        $card = Card::factory()->create(['user_id' => $user->id]);

        $data = [
            'title' => 'Updated Title',
            'slogan' => 'Updated Slogan',
            'phonenumber' => '1234567891',
            'email' => 'updated@example.com',
            'address' => 'Updated Address',
            'website' => 'http://www.updated.com',
        ];

        $response = $this->actingAs($user)->put('/api/cards/' . $card->id . '/update', $data);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Card Updated Successfully'
            ]);
    }


    public function test_destroy_card()
    {
        $user = User::factory()->create();
        $card = Card::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete('/api/cards/' . $card->id . '/destroy');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Deleted Successfully'
            ]);
    }
    public function test_get_user_cards()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Card::factory()->create(['user_id' => $user->id]);
        Card::factory()->create(['user_id' => $user->id]);

        $response = $this->get('/api/cards/user');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'cards',
        ]);

        $cardsCount = Card::where('user_id', $user->id)->count();
        $response->assertJsonCount($cardsCount, 'cards');
    }

}
