<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMiprFileNoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mipr_file_no', function (Blueprint $table) {
            $table->id();
            $table->integer('mipr_file_no')->default(1);
            $table->timestamps();
        });

        // Insert the default row with file_no = 1
        DB::table('mipr_file_no')->insert([
            'mipr_file_no' => 1
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mipr_file_no_');
    }
}
