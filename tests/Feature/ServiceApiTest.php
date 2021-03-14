<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\User;
use Tests\TestCase;

use Illuminate\Http\Response;

class ServiceApiTest extends TestCase
{
    public function test_add_service_api()
    {
        $user = User::find(2);
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();

        $this->post('/api/services/add', [
            'product_id' => 1,
            'name' => 'Service #1 by ' . $user->name
        ])->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'status',
            'message'
        ])->assertJson([
            'status' => true
        ]);
    }

    public function test_add_service_api_unauth()
    {
        $this->post('/api/services/add', [
            'product_id' => 1,
            'name' => 'Service #1 by UnAuth User'
        ])->assertStatus(Response::HTTP_FOUND)->assertHeader('Location', env('APP_URL') . '/login');
    }

    public function test_add_service_api_validation_error()
    {
        $user = User::find(2);
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();

        $this->post('/api/services/add', [
            'product_id' => 3,
            'name' => 'Service #1 by ' . $user->name . ' ' . bin2hex(openssl_random_pseudo_bytes(255))
        ])->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'status',
            'messages'
        ])->assertJson([
            'status' => false
        ]);
    }

    public function test_edit_service_api()
    {
        $user = User::find(2);
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();

        $service = Service::where('user_id', $user->id)->where('product_id', 1)->first();
        $this->assertNotNull($service);

        $this->post('/api/services/edit/' . $service->ID, [
            'name' => $service->name
        ])->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'status',
            'message'
        ])->assertJson([
            'status' => true
        ]);
    }

    public function test_edit_service_api_unauth()
    {
        $this->post('/api/services/edit/1', [
            'name' => 'Service #1 by UnAuth User'
        ])->assertStatus(Response::HTTP_FOUND)->assertHeader('Location', env('APP_URL') . '/login');
    }

    public function test_edit_service_api_forbidden()
    {
        $user = User::find(2);
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();

        $service = Service::where('user_id', 1)->where('product_id', 1)->first();
        $this->assertNotNull($service);

        $this->post('/api/services/edit/' . $service->ID, [
            'name' => $service->name
        ])->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_edit_service_api_validation_error()
    {
        $user = User::find(2);
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();

        $service = Service::where('user_id', $user->id)->where('product_id', 1)->first();
        $this->assertNotNull($service);

        $this->post('/api/services/edit/' . $service->ID, [
            'name' => $service->name . ' ' . bin2hex(openssl_random_pseudo_bytes(255))
        ])->assertStatus(Response::HTTP_OK)->assertJsonStructure([
            'status',
            'messages'
        ])->assertJson([
            'status' => false
        ]);
    }

    public function test_list_service_api()
    {
        $user = User::find(2);
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();

        $this->get('/api/services/')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'status',
                'services'
            ])
            ->assertJson([
                'status' => true
            ]);
    }

    public function test_list_service_api_unauth()
    {
        $this->get('/api/services/')
            ->assertStatus(Response::HTTP_FOUND)
            ->assertHeader('Location', env('APP_URL') . '/login');
    }

    public function test_delete_service_api()
    {
        $user = User::find(2);
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();

        $service = Service::where('user_id', $user->id)->where('product_id', 1)->orderBy('created', 'desc')->first();
        $this->assertNotNull($service);

        $this->get('/api/services/delete/' . $service->ID)
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'status',
                'message'
            ])
            ->assertJson([
                'status' => true
            ]);
    }

    public function test_delete_service_api_unauth()
    {
        $this->get('/api/services/delete/1')
            ->assertStatus(Response::HTTP_FOUND)
            ->assertHeader('Location', env('APP_URL') . '/login');
    }

    public function test_delete_service_api_forbidden()
    {
        $user = User::find(2);
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();

        $service = Service::where('user_id', 1)->where('product_id', 1)->orderBy('created', 'desc')->first();
        $this->assertNotNull($service);

        $this->get('/api/services/delete/' . $service->ID)
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_upgrade_service_api()
    {
        $user = User::find(1);
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();

        $service = Service::where('user_id', $user->id)->where('product_id', 1)->orderBy('created', 'desc')->first();
        $this->assertNotNull($service);

        $this->post('/api/services/upgrade/' . $service->ID . '/1', [])
            ->assertStatus(Response::HTTP_OK)->assertJsonStructure([
                'status',
                'message'
            ])->assertJson([
                'status' => true
            ]);
    }

    public function test_upgrade_service_api_unauth()
    {
        $this->post('/api/services/upgrade/1/2', [])
            ->assertStatus(Response::HTTP_FOUND)
            ->assertHeader('Location', env('APP_URL') . '/login');
    }

    public function test_upgrade_service_api_forbidden()
    {
        $user = User::find(2);
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();

        $service = Service::where('user_id', 1)->where('product_id', 1)->orderBy('created', 'desc')->first();
        $this->assertNotNull($service);

        $this->post('/api/services/upgrade/' . $service->ID . '/2', [])
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_upgrade_service_api_validation_error_not_exists_product()
    {
        $user = User::find(1);
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();

        $service = Service::where('user_id', $user->id)->where('product_id', 1)->orderBy('created', 'desc')->first();
        $this->assertNotNull($service);

        $this->post('/api/services/upgrade/' . $service->ID . '/3', [])
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_downgrade_service_api()
    {
        $user = User::find(1);
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();

        $service = Service::where('user_id', $user->id)->where('product_id', 2)->orderBy('created', 'desc')->first();
        $this->assertNotNull($service);

        $this->post('/api/services/downgrade/' . $service->ID . '/2', [])
            ->assertStatus(Response::HTTP_OK)->assertJsonStructure([
                'status',
                'message'
            ])->assertJson([
                'status' => true
            ]);
    }

    public function test_downgrade_service_api_unauth()
    {
        $this->post('/api/services/downgrade/2/1', [])
            ->assertStatus(Response::HTTP_FOUND)
            ->assertHeader('Location', env('APP_URL') . '/login');
    }

    public function test_downgrade_service_api_forbidden()
    {
        $user = User::find(2);
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();

        $service = Service::where('user_id', 1)->where('product_id', 2)->orderBy('created', 'desc')->first();
        $this->assertNotNull($service);

        $this->post('/api/services/downgrade/' . $service->ID . '/1', [])
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_downgrade_service_api_validation_error_not_exists_product()
    {
        $user = User::find(1);
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();

        $service = Service::where('user_id', $user->id)->where('product_id', 2)->orderBy('created', 'desc')->first();
        $this->assertNotNull($service);

        $this->post('/api/services/downgrade/' . $service->ID . '/3', [])
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_downgrade_service_api_validation_error_disk_size()
    {
        $user = User::find(1);
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();

        $service = Service::where('user_id', $user->id)->where('product_id', 2)->orderBy('created', 'desc')->first();
        $this->assertNotNull($service);

        $this->post('/api/services/downgrade/' . $service->ID . '/1', [])
            ->assertStatus(Response::HTTP_OK)->assertJsonStructure([
                'status',
                'messages'
            ])->assertJson([
                'status' => false
            ]);
    }
}
