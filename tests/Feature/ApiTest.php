<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Transaction;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_mock_api_accepted()
    {
        $response = $this->get('/api/mock', ['X-Mock-Status' => 'accepted']);
        $response->assertStatus(200)
                 ->assertJson(['status' => 'accepted']);
    }

    public function test_mock_api_failed()
    {
        $response = $this->get('/api/mock', ['X-Mock-Status' => 'failed']);
        $response->assertStatus(400)
                 ->assertJson(['status' => 'failed']);
    }

    public function test_payment_api()
    {
        $response = $this->post('/api/payment', [
            'amount' => 100.00,
            'user_id' => 1,
        ], ['X-Mock-Status' => 'accepted']);

        $response->assertStatus(200)
                 ->assertHeader('Cache-Control');

        $this->assertTrue(
            strpos($response->headers->get('Cache-Control'), 'no-store') !== false,
            'Cache-Control header does not contain no-store'
        );

        $response->assertJsonStructure(['transaction_id', 'status']);
    }

    public function test_callback_api()
    {
        $transaction = Transaction::factory()->create([
            'transaction_id' => (string) \Illuminate\Support\Str::uuid(),
            'status' => 'failed',
        ]);

        $response = $this->post('/api/callback', [
            'transaction_id' => $transaction->transaction_id,
            'status' => 'accepted'
        ]);

        $response->assertStatus(200)
                ->assertJson(['message' => 'Transaction updated successfully']);
    }
}
