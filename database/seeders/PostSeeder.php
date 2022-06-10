<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1; $i <= 20; $i++){
            $date = '0';
            if($i >= 10)
                $date = $i;
            else
                $date .= $i;

            DB::table('posts')->insert([
                'title' => 'Post '.$i,
                'slug' => 'post-'.$i,
                'short_description' => 'Short description of post '.$i."<br>Need some placeholder text in your code? Type lorem and press Tab. If needed, you can add the text together with a tag:",
                'description' => 'Need some placeholder text in your code? Type lorem and press Tab. If needed, you can add the text together with a tag: just add a tag name and > before lorem. If you want to generate a specific number of words, add a number after lorem, e.g. lorem5, then press Tab. Description'.$i,
                'date_publish' => date('Y-m-'.$date.' H:i:s'),
                'deleted' => 0
            ]);
        }
    }
}
