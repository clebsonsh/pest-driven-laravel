<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AddGivenCoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if ($this->isDataAlreadyGiven()) return;

        Course::create([
            'slug' => Str::of('Laravel For Beginners')->slug(),
            'title' => 'Laravel For Beginners',
            'tagline' => 'Make you fierst steps as a Laravel dev.',
            'description' => 'A video course to teach you Laravel from scratch.',
            'image_name' => 'laravel_for_beginners.png',
            'learnings' => [
                'How to start with Laravel',
                'Where to start with Laravel',
                'Build your first Laravel app',
            ],
            'released_at' => now(),
        ]);

        Course::create([
            'slug' => Str::of('Advanced Laravel')->slug(),
            'title' => 'Advanced Laravel',
            'tagline' => 'Make you fierst steps as a Laravel dev.',
            'description' => 'A video course to teach you Laravel from scratch.',
            'image_name' => 'laravel_for_beginners.png',
            'learnings' => [
                'How to start with Laravel',
                'Where to start with Laravel',
                'Build your first Laravel app',
            ],
            'released_at' => now(),
        ]);

        Course::create([
            'slug' => Str::of('TDD The Laravel Way')->slug(),
            'title' => 'TDD The Laravel Way',
            'tagline' => 'Make you fierst steps as a Laravel dev.',
            'description' => 'A video course to teach you Laravel from scratch.',
            'image_name' => 'laravel_for_beginners.png',
            'learnings' => [
                'How to start with Laravel',
                'Where to start with Laravel',
                'Build your first Laravel app',
            ],
            'released_at' => now(),
        ]);
    }

    private function isDataAlreadyGiven(): bool
    {
        return Course::where('title', 'Laravel For Beginners')->exists()
            && Course::where('title', 'Advanced Laravel')->exists()
            && Course::where('title', 'TDD The Laravel Way')->exists();
    }
}
