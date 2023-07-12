<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;

class UserCreate extends Command
{
    use WithFaker;

    protected $signature = 'app:user:create
                                {--name=}
                                {--email=}
                                {--password=}
                                {--role=user}
                                ';

    protected $description = 'Create User';

    public function handle(): int
    {
        $faker = $this->makeFaker();

        /** @var User $newUser */
        $newUser = User::factory()->create([
            'name' => $this->option('name') ?? $faker->name,
            'email' => $this->option('email') ?? $faker->email,
            'password' => Hash::make($this->option('password') ?? 'password'),
        ]);

        $newUser->assignRole($this->option('role'));

        $this->line('User created: ');
        $this->line($newUser->toJson());

        return self::SUCCESS;
    }
}
