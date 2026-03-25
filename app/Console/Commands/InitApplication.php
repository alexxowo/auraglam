<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

#[Signature('app:init {--fresh : Whether to run migrate:fresh instead of migrate} {--no-migrate : Skip running migrations}')]
#[Description('Initialize the application migrations and create an admin user.')]
class InitApplication extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        if (! $this->option('no-migrate')) {
            $fresh = $this->option('fresh');

            if ($fresh) {
                $this->info('Running migrate:fresh...');
                Artisan::call('migrate:fresh', ['--force' => true], $this->getOutput());
            } else {
                $this->info('Running migrate...');
                Artisan::call('migrate', ['--force' => true], $this->getOutput());
            }
        } else {
            $this->info('Skipping migrations as requested.');
        }

        $this->newLine();
        $this->info('Configuración de usuario administrador:');

        $name = text(
            label: 'Nombre completo',
            placeholder: 'Ej: Juan Pérez',
            required: true
        );

        $email = text(
            label: 'Correo electrónico',
            placeholder: 'admin@auraglam.com',
            required: true,
            validate: fn (string $value) => match (true) {
                ! filter_var($value, FILTER_VALIDATE_EMAIL) => 'The email address is invalid.',
                User::where('email', $value)->exists() => 'The email address is already taken.',
                default => null
            }
        );

        $passwordValue = password(
            label: 'Clave',
            placeholder: 'Mínimo 8 caracteres',
            required: true,
            validate: fn (string $value) => strlen($value) < 8
                ? 'The password must be at least 8 characters.'
                : null
        );

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($passwordValue),
            'type' => 'admin',
        ]);

        $this->newLine();
        $this->info("¡Usuario administrador '{$name}' creado con éxito!");
    }
}
