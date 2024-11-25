<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPositivelyOnToAssignedNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assigned_news', function (Blueprint $table) {
            $table->date('positively_on')->nullable()->after('empanelled_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assigned_news', function (Blueprint $table) {
            $table->dropColumn('positively_on');
        });
    }
}
