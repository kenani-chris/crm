<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAwarenessCreationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('awareness_creations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('aware')->nullable();
            $table->string('satisfaction')->nullable();
            $table->string('comment')->nullable();
            $table->uuid('member_id');
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('members');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('awareness_creations');
    }
}
