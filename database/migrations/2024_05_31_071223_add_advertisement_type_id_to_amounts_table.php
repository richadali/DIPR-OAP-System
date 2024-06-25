<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdvertisementTypeIdToAmountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('amount', function (Blueprint $table) {
            $table->foreignId('advertisement_type_id')
                  ->constrained('advertisement_type')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('amounts', function (Blueprint $table) {
            $table->dropForeign(['advertisement_type_id']);
            $table->dropColumn('advertisement_type_id');
        });
    }
}
