<?php

namespace Database\Seeders;

use App\Models\LoginSlide;
use Illuminate\Database\Seeder;

class LoginSlideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (LoginSlide::count() > 0) {
            return;
        }

        $slides = [
            [
                'title' => 'Sistem Manajemen Audit',
                'subtitle' => 'Transparansi dan Akuntabilitas',
                'description' => 'Kelola audit dan tindak lanjut dengan efektif untuk meningkatkan integritas organisasi.',
                'button_text' => null,
                'button_url' => null,
                'image_path' => 'https://images.unsplash.com/photo-1556740749-887f6717d7e4?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'title' => 'Kolaborasi Tim',
                'subtitle' => 'Informasi Terpadu',
                'description' => 'Satukan data audit, konsumsi, pemeliharaan, dan agenda dalam satu platform terintegrasi.',
                'button_text' => null,
                'button_url' => null,
                'image_path' => 'https://images.unsplash.com/photo-1520607162513-77705c0f0d4a?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'title' => 'Pengambilan Keputusan Cepat',
                'subtitle' => 'Dashboard Real-time',
                'description' => 'Pantau aktivitas penting dan buat keputusan berbasis data dari mana saja.',
                'button_text' => null,
                'button_url' => null,
                'image_path' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1200&q=80',
            ],
        ];

        foreach ($slides as $index => $slide) {
            LoginSlide::create(array_merge($slide, [
                'display_order' => $index,
                'is_active' => true,
            ]));
        }
    }
}
