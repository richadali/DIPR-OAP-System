<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() 
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->integer('bill_no');
            $table->date('bill_date');
            $table->string('paid_by',2);
            $table->foreignId('user_id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');  
            $table->foreignId('ad_id') // Notice. tender 
                ->constrained('advertisement');
            $table->foreignId('empanelled_id') 
                ->constrained('empanelled');
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
        Schema::dropIfExists('bills');
    }
}
