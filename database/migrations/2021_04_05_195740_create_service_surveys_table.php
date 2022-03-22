<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceSurveysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_surveys', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('intro')->nullable();
            $table->index('intro','idx_intro');
            $table->string('q1')->nullable();
            $table->string('q2')->nullable();
            $table->datetime('callback')->nullable();
            $table->string('q3')->nullable();
            $table->text('q3_comments')->nullable();
            $table->uuid('q4_comment_type_id')->nullable(); //q4
            $table->index('q4_comment_type_id','idx_q4_comment_type_id');
            $table->uuid('q4_channel_id')->nullable(); //q4
            $table->index('q4_channel_id','idx_q4_channel_id');
            $table->uuid('q4_comment_summary_id')->nullable(); //q4
            $table->index('q4_comment_summary_id','idx_q4_comment_summary_id');
            $table->string('q4_action_required')->nullable(); //q4
            $table->string('q5')->nullable();
            $table->string('q6')->nullable();
            $table->string('q7')->nullable();
            $table->string('q8')->nullable();
            $table->string('q9')->nullable();
            $table->text('q9_comments')->nullable();
            $table->uuid('q10_comment_type_id')->nullable(); //q10
            $table->index('q10_comment_type_id','idx_q10_comment_type_id');
            $table->uuid('q10_channel_id')->nullable(); //q10
            $table->index('q10_channel_id','idx_q10_channel_id');
            $table->uuid('q10_comment_summary_id')->nullable(); //q10
            $table->index('q10_comment_summary_id','idx_q10_comment_summary_id');
            $table->string('q10_action_required')->nullable(); //q10
            $table->string('q11')->nullable();
            $table->uuid('disposition_id')->nullable();
            $table->index('disposition_id','idx_disposition_id');
            $table->uuid('service_lead_id')->nullable();
            $table->index('service_lead_id','idx_service_lead_id');
            $table->string('lastQuestion')->nullable();
            $table->uuid('user_id')->nullable();
            $table->timestamps();
        });

        Schema::table('service_surveys', function($table) {
            $table->foreign('intro')->references('id')->on('disposition_types');
            $table->foreign('q4_channel_id')->references('id')->on('channels');
            $table->foreign('q10_channel_id')->references('id')->on('channels');
            $table->foreign('q4_comment_type_id')->references('id')->on('comment_types');
            $table->foreign('q10_comment_type_id')->references('id')->on('comment_types');
            $table->foreign('q4_comment_summary_id')->references('id')->on('comment_summaries');
            $table->foreign('q10_comment_summary_id')->references('id')->on('comment_summaries');
            $table->foreign('disposition_id')->references('id')->on('dispositions');
            $table->foreign('service_lead_id')->references('id')->on('service_leads');
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
        Schema::dropIfExists('service_surveys');
    }
}
