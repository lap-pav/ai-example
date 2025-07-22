<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\AuthService;
use App\Repositories\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    private AuthService $authService;
    private UserRepositoryInterface $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = app(UserRepositoryInterface::class);
        $this->authService = new AuthService($this->userRepository);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        // Arrange
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $credentials = [
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        // Act
        $result = $this->authService->login($credentials);

        // Assert
        $this->assertArrayHasKey('token', $result);
        $this->assertArrayHasKey('user', $result);
        $this->assertEquals($user->id, $result['user']['id']);
        $this->assertEquals($user->email, $result['user']['email']);
    }

    public function test_get_user_profile_returns_formatted_data(): void
    {
        // Arrange
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        // Act
        $profile = $this->authService->getUserProfile($user);

        // Assert
        $this->assertArrayHasKey('id', $profile);
        $this->assertArrayHasKey('name', $profile);
        $this->assertArrayHasKey('email', $profile);
        $this->assertArrayHasKey('email_verified_at', $profile);
        $this->assertEquals('John Doe', $profile['name']);
        $this->assertEquals('john@example.com', $profile['email']);
    }
}
