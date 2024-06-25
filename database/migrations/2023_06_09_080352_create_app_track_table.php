<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppTrackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_track', function (Blueprint $table) {
            $table->id();
            $table->integer('next_user_id');
            $table->date('process_date');
            $table->string('process_flag',2);
            $table->foreignId('advertisement_id')
                ->constrained('advertisement')
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
        Schema::dropIfExists('app_track');
    }
}
