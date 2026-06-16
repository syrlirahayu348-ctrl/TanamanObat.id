<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Antiinflamasi',      'slug' => 'antiinflamasi',      'icon' => '🔥', 'color' => '#ef4444', 'description' => 'Tanaman obat yang memiliki khasiat meredakan peradangan pada tubuh.'],
            ['name' => 'Antioksidan',         'slug' => 'antioksidan',         'icon' => '⚡', 'color' => '#f59e0b', 'description' => 'Tanaman obat kaya antioksidan yang melindungi sel tubuh dari kerusakan.'],
            ['name' => 'Pencernaan',          'slug' => 'pencernaan',          'icon' => '🫁', 'color' => '#8b5cf6', 'description' => 'Tanaman obat yang membantu melancarkan sistem pencernaan.'],
            ['name' => 'Imun & Daya Tahan',  'slug' => 'imun-daya-tahan',    'icon' => '🛡️', 'color' => '#22c55e', 'description' => 'Tanaman obat untuk meningkatkan daya tahan tubuh.'],
            ['name' => 'Pernapasan',          'slug' => 'pernapasan',          'icon' => '💨', 'color' => '#06b6d4', 'description' => 'Tanaman obat untuk masalah pernapasan dan batuk.'],
            ['name' => 'Jantung & Darah',    'slug' => 'jantung-darah',      'icon' => '❤️', 'color' => '#ec4899', 'description' => 'Tanaman obat yang baik untuk kesehatan jantung dan sirkulasi darah.'],
            ['name' => 'Kulit & Kecantikan', 'slug' => 'kulit-kecantikan',   'icon' => '✨', 'color' => '#d97706', 'description' => 'Tanaman obat yang bermanfaat untuk kesehatan kulit dan kecantikan.'],
            ['name' => 'Anti Diabetes',      'slug' => 'anti-diabetes',      'icon' => '🍃', 'color' => '#16a34a', 'description' => 'Tanaman obat yang membantu mengontrol kadar gula darah.'],
            ['name' => 'Antipiretik',        'slug' => 'antipiretik',        'icon' => '🌡️', 'color' => '#0ea5e9', 'description' => 'Tanaman obat penurun demam alami.'],
            ['name' => 'Tonik & Stamina',   'slug' => 'tonik-stamina',      'icon' => '💪', 'color' => '#7c3aed', 'description' => 'Tanaman obat penambah energi dan vitalitas tubuh.'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}
