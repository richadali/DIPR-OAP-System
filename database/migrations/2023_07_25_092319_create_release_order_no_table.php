<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReleaseOrderNoTable extends Migration
{
    
    public function up()
    {
        Schema::create('release_order_no', function (Blueprint $table) {
            $table->integer('release_order_no');
            $table->string('fin_year',9);
            $table->primary(['release_order_no', 'fin_year']);
        });
    }


    public function down()
    {
        Schema::dropIfExists('release_order_no');
    }
}
