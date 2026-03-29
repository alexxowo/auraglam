<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Tops', 'slug' => 'tops', 'description' => 'Tops de moda', 'is_active' => true],
            ['name' => 'Pantalones', 'slug' => 'pantalones', 'description' => 'Pantalones de moda', 'is_active' => true],
            ['name' => 'Monos', 'slug' => 'monos', 'description' => 'Monos de moda', 'is_active' => true],
            ['name' => 'Leggins', 'slug' => 'leggins', 'description' => 'Leggins de moda', 'is_active' => true],
            ['name' => 'Faldas', 'slug' => 'faldas', 'description' => 'Faldas de moda', 'is_active' => true],
            ['name' => 'Vestidos', 'slug' => 'vestidos', 'description' => 'Vestidos de moda', 'is_active' => true],
            ['name' => 'Accesorios', 'slug' => 'accesorios', 'description' => 'Accesorios de moda', 'is_active' => true],
            ['name' => 'Calzado', 'slug' => 'calzado', 'description' => 'Zapatos y sandalias', 'is_active' => true],
        ];

        Category::insert($categories);
    }
}
