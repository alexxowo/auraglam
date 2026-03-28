<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\password;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

#[Signature('user:create {name?} {email?} {password?} {--type= : El tipo de usuario (admin, saler)}')]
#[Description('Crea un nuevo usuario en la base de datos.')]
class CreateUser extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = $this->argument('name') ?? text(
            label: 'Nombre completo',
            placeholder: 'Ej: Juan Pérez',
            required: true
        );

        $email = $this->argument('email') ?? text(
            label: 'Correo electrónico',
            placeholder: 'usuario@auraglam.com',
            required: true,
            validate: fn (string $value) => match (true) {
                ! filter_var($value, FILTER_VALIDATE_EMAIL) => 'The email address is invalid.',
                User::where('email', $value)->exists() => 'The email address is already taken.',
                default => null
            }
        );

        $passwordValue = $this->argument('password') ?? password(
            label: 'Clave',
            placeholder: 'Mínimo 8 caracteres',
            required: true,
            validate: fn (string $value) => strlen($value) < 8
                ? 'The password must be at least 8 characters.'
                : null
        );

        $type = $this->option('type') ?? select(
            label: 'Tipo de usuario',
            options: [
                'admin' => 'Administrador',
                'saler' => 'Vendedor',
            ],
            default: 'saler'
        );

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($passwordValue),
            'type' => $type,
        ]);

        $this->info("¡Usuario '{$name}' ({$email}) de tipo '{$type}' creado con éxito!");

        return Command::SUCCESS;
    }
}
