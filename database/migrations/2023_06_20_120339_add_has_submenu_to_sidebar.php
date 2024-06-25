<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHasSubmenuToSidebar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sidebar', function (Blueprint $table) {
            $table->boolean('has_submenu')->default(false)->after('sidebar_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sidebar', function (Blueprint $table) {
            $table->dropColumn('has_submenu');
        });
    }
}
