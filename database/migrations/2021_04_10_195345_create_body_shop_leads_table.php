<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBodyShopLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('body_shop_leads', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('DateOut')->nullable();
            $table->string('CustomerName')->nullable();
            $table->string('MakeModel')->nullable();
            $table->string('Registration')->nullable();
            $table->string('CompanyName')->nullable();
            $table->string('MobileNumber')->nullable();
            $table->string('ServiceAdvisor')->nullable();
            $table->string('lastDisposition')->default('Pending');
            $table->dateTime('callback')->nullable();
            $table->integer('attempts')->default(0);
            $table->uuid('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('body_shop_leads');
    }
}
