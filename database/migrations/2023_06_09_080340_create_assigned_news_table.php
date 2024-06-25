<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignedNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assigned_news', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advertisement_id')
                ->constrained('advertisement')
                ->onUpdate('cascade')
                ->onDelete('cascade');  
            $table->foreignId('empanelled_id')
                ->constrained('empanelled')
                ->onUpdate('cascade')
                ->onDelete('cascade');  
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
        Schema::dropIfExists('assigned_news');
    }
}
