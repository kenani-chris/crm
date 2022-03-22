<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_leads', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('DateOut')->nullable();
            $table->string('CustomerName')->nullable();
            $table->string('MakeModel')->nullable();
            $table->string('Registration')->nullable();
            $table->string('CompanyName')->nullable();
            $table->string('MobileNumber')->nullable();
            $table->string('ServiceAdvisor')->nullable();
            $table->string('BranchCode1')->nullable();
            $table->string('lastDisposition')->default('Pending');
            $table->dateTime('callback')->nullable();
            $table->integer('attempts')->default(0);
            $table->uuid('user_id');
            $table->timestamps();
        });



        Schema::table('service_leads', function($table) {
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
        Schema::dropIfExists('service_leads');
    }
}
