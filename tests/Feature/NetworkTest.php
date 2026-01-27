<?php

namespace Feature;

use App\Models\Network;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class NetworkTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_it_show(): void
    {
        $network = Network::factory()->create();
        $response = $this->getJson(route('network.show', ['networkId' => $network->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'description',
                    'cidr',
                    'location',
                    'status'
                ]
            ]);
    }

    public function test_it_show_with_invalid_id(): void
    {
        $networkId = fake()->numberBetween(1, 1000);
        $response = $this->getJson(route('network.show', ['networkId' => $networkId]));

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->assertJsonStructure(['message']);
    }

    public function test_it_store(): void
    {
        $response = $this->postJson(
            route('network.store'),
            $this->data()
        );

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'description',
                    'cidr',
                    'location',
                    'status'
                ]
            ]);
    }

    public function test_it_store_with_invalid_cidr(): void
    {
        $data = $this->data();
        $data['cidr'] = null;
        $response = $this->postJson(
            route('network.store'),
            $data
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['message']);
    }

    public function test_it_update(): void
    {
        $network = Network::factory()->create();
        $response = $this->putJson(
            route('network.update', ['networkId' => $network->id]),
            $this->data()
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['message']);
    }

    public function test_it_update_with_invalid_id(): void
    {
        $networkId = fake()->numberBetween(1, 1000);
        $response = $this->putJson(
            route('network.update', ['networkId' => $networkId]),
            $this->data()
        );

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->assertJsonStructure(['message']);
    }

    public function test_it_update_with_invalid_name(): void
    {
        $network = Network::factory()->create();
        $data = $this->data();
        $data['name'] = null;
        $response = $this->putJson(
            route('network.update', ['networkId' => $network->id]),
            $data
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['message']);
    }

    public function test_it_delete(): void
    {
        $network = Network::factory()->create();
        $response = $this->deleteJson(route('network.delete', ['networkId' => $network->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['message']);
    }

    public function test_it_delete_with_invalid_id(): void
    {
        $networkId = fake()->numberBetween(1, 1000);
        $response = $this->deleteJson(route('network.delete', ['networkId' => $networkId]));

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJsonStructure(['message']);
    }

    private function data(): array
    {
        return [
            'name' => fake()->name(),
            'description' => fake()->text(50),
            'cidr' => fake()->ipv4() . '/24',
            'location' => fake()->city(),
            'status' => 'active'
        ];
    }
}
