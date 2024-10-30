<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStatusColumnTypeInAdvertisementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('advertisement', function (Blueprint $table) {
            $table->text('status', 20)->change();
        });
    }

    public function down()
    {
        Schema::table('advertisement', function (Blueprint $table) {
            $table->text('status', 1)->change();
        });
    }
}
