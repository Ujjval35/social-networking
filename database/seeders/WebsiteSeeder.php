<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class WebsiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('websites')->insert([
            [ 'name' => 'Google', 'url' => 'www.google.com' ],
            [ 'name' => 'Yahoo', 'url' => 'www.yahoo.com' ],
            [ 'name' => 'bing', 'url' => 'www.bing.com' ]
        ]);
    }
}
