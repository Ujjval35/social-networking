<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $postArray = [
            [
                'user_id' => 1,
                'website_id' => 1,
                'name' => 'Post Title 1',
                'description' => 'Post Description 1, it is very long. Its about the post title.'
            ],
            [
                'user_id' => 2,
                'website_id' => 2,
                'name' => 'Post Title 2',
                'description' => 'Post Description 2, it is very long. Its about the post title.'
            ],
            [
                'user_id' => 3,
                'website_id' => 3,
                'name' => 'Post Title 3',
                'description' => 'Post Description 3, it is very long. Its about the post title.'
            ],
        ];
        
        DB::table('posts')->insert($postArray);
    }
}
