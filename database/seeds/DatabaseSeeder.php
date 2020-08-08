<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {
		for ($i = 1; $i <= 10; $i++) {
			DB::table('categories')->insert([
				'category_name' => Str::random(8),
			]);
    }
    
    for ($i = 1; $i <= 15; $i++) {
      $post_name = Str::random(8);
			DB::table('posts')->insert([
        'post_slug' => $post_name,
        'post_name' => $post_name,
        'cat_id' => 1,
        'post_image' => '2076158609.jpg',
        'post_description' => Str::random(30),
        'created_at' => date('Y-m-d'),
        'updated_at' => date('Y-m-d'),
			]);
    }
    
	}
}
