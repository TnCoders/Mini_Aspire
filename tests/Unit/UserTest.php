<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->json('POST', '/api/login', ['email' => 'paul@client.com','password' => '987654321']);

        $response

            ->assertJsonFragment([
                'token_type' => 'bearer',
            ]);
    }
    /**
     * TODO Write User TEST UNIT
     */
}
