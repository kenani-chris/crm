<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_leads', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('DistributorNameOutletName')->nullable();
            $table->string('RetailOutletDealerCode')->nullable();
            $table->string('Title')->nullable();
            $table->string('Initials')->nullable();
            $table->string('Surname')->nullable();
            $table->string('Landline')->nullable();
            $table->string('Mobile')->nullable();
            $table->string('TransactionType')->nullable();
            $table->string('CompanyName')->nullable();
            $table->string('FleetGovernmentPrivate')->nullable();
            $table->string('ModelCode')->nullable();
            $table->string('ModelName')->nullable();
            $table->string('RegistrationVIN')->nullable();
            $table->string('TransactionDate')->nullable();
            $table->string('SalesPersonName')->nullable();
            $table->string('lastDisposition')->default('Pending');
            $table->dateTime('callback')->nullable();
            $table->integer('attempts')->default(0);
            $table->uuid('user_id');
            $table->timestamps();
        });

        Schema::table('sales_leads', function($table) {
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
        Schema::dropIfExists('sales_leads');
    }
}
