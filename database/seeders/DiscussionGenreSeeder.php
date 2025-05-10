<?php

namespace Database\Seeders;

use App\Models\DiscussionGenre;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DiscussionGenreSeeder extends Seeder
{
    public function run()
    {
        $genres = [
            [
                'name' => 'Main Forum',
                'description' => 'General discussions about goals, productivity, and personal development.',
            ],
            [
                'name' => 'Off Topic',
                'description' => 'Casual conversations and discussions not directly related to goals.',
            ],
            [
                'name' => 'Team Collaboration',
                'description' => 'Discussions about team goals, collaboration, and team management.',
            ],
            [
                'name' => 'Tips & Tricks',
                'description' => 'Share and discover tips for achieving goals and improving productivity.',
            ],
        ];

        foreach ($genres as $genre) {
            DiscussionGenre::create([
                'name' => $genre['name'],
                'slug' => Str::slug($genre['name']),
                'description' => $genre['description'],
            ]);
        }
    }
} 