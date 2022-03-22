<?php

use Illuminate\Database\Seeder;
use App\CommentType;

class CommentTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $comment_types = array(
            'Negative',
            'Positive' ,
            'Suggestion',
            'Enquiries',
            'Neutral',
        );

        foreach ($comment_types  as $comment_type) {
            CommentType::query()->create([
                'title' => $comment_type
            ]);
        }
    }
}
