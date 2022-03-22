<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDispositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispositions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->uuid('disposition_type_id');
            $table->boolean('isactive')->default(1);
            $table->index('disposition_type_id', 'idx_disposition_type_id');
            $table->index('title', 'idx_title');
            $table->index('slug', 'idx_slug');
            $table->timestamps();
        });

        Schema::table('dispositions', function($table) {
            $table->foreign('disposition_type_id')->references('id')->on('disposition_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dispositions');
    }
}
