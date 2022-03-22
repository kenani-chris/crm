<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('question_id');
            $table->text('answer');
            $table->text('real_answer');
            $table->integer('redirect_to');
            $table->boolean('has_text_box')->default(false);
            $table->boolean('has_date_picker')->default(false);
            $table->index('question_id', 'idx_question_id');
            $table->timestamps();
        });
         
        Schema::table('answers', function($table) {
            $table->foreign('question_id')->references('id')->on('questions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answers');
    }
}
