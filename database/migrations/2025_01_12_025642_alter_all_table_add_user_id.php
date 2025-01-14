<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('item_categories', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned()->index();
        });
        Schema::table('items', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned()->index();
        });
        Schema::table('vendors', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
        Schema::table('item_categories', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
};
