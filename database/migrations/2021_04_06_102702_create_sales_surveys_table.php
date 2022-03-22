<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_surveys', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->uuid('intro')->nullable();
        $table->index('intro','idx_intro');
        $table->string('q1')->nullable(); //
        $table->string('q2')->nullable(); //
        $table->datetime('callback')->nullable(); //
        $table->string('q3')->nullable(); //
        $table->text('q3_comments')->nullable(); //
        $table->string('q4')->nullable();
        $table->text('q4_yes_comments')->nullable();
        $table->uuid('q5_comment_type_id')->nullable(); //q5
        $table->index('q5_comment_type_id','idx_q5_comment_type_id');
        $table->uuid('q5_channel_id')->nullable(); //q5
        $table->index('q5_channel_id','idx_q5_channel_id');
        $table->uuid('q5_comment_summary_id')->nullable(); //q5
        $table->index('q5_comment_summary_id','idx_q5_comment_summary_id');
        $table->string('q5_action_required')->nullable(); //q5
        $table->string('q6')->nullable();
        $table->string('q7')->nullable();
        $table->string('q8')->nullable();
        $table->string('q9')->nullable();
        $table->string('q10')->nullable();
        $table->text('q10_comments')->nullable();
        $table->uuid('q11_comment_type_id')->nullable(); //q10
        $table->index('q11_comment_type_id','idx_q11_comment_type_id');
        $table->uuid('q11_channel_id')->nullable(); //q11
        $table->index('q11_channel_id','idx_q11_channel_id');
        $table->uuid('q11_comment_summary_id')->nullable(); //q11
        $table->index('q11_comment_summary_id','idx_q11_comment_summary_id');
        $table->string('q11_action_required')->nullable(); //q11
        $table->string('q12')->nullable();
        $table->uuid('disposition_id')->nullable();
        $table->index('disposition_id','idx_disposition_id');
        $table->uuid('sales_lead_id')->nullable();
        $table->index('sales_lead_id','idx_sales_lead_id');
        $table->string('lastQuestion')->nullable();
        $table->uuid('user_id')->nullable();
        $table->timestamps();
    });

    Schema::table('sales_surveys', function($table) {
        $table->foreign('intro')->references('id')->on('disposition_types');
        $table->foreign('q5_channel_id')->references('id')->on('channels');
        $table->foreign('q11_channel_id')->references('id')->on('channels');
        $table->foreign('q5_comment_type_id')->references('id')->on('comment_types');
        $table->foreign('q11_comment_type_id')->references('id')->on('comment_types');
        $table->foreign('q5_comment_summary_id')->references('id')->on('comment_summaries');
        $table->foreign('q11_comment_summary_id')->references('id')->on('comment_summaries');
        $table->foreign('disposition_id')->references('id')->on('dispositions');
        $table->foreign('sales_lead_id')->references('id')->on('sales_leads');
        $table->foreign('user_id')->references('id')->on('users');
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_surveys');
    }
}
