<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeAdCategoryIdNullableInAmountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('amount', function (Blueprint $table) {
            $table->bigInteger('ad_category_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('amount', function (Blueprint $table) {
            $table->bigInteger('ad_category_id')->nullable(false)->change();
        });
    }
}
