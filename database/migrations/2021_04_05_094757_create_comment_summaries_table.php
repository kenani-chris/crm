<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_summaries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('comment_type_id')->nullable();
            $table->uuid('channel_id')->nullable();
            $table->index('comment_type_id','idx_comment_type_id');
            $table->string('comment_summary')->nullable();
            $table->index('channel_id','idx_channel_id');
            $table->timestamps();
        });

        Schema::table('comment_summaries', function($table) {
            $table->foreign('comment_type_id')->references('id')->on('comment_types');
            $table->foreign('channel_id')->references('id')->on('channels');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comment_summaries');
    }
}
