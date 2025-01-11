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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name',255);
            $table->string('description')->nullable();
            $table->integer('price_per_unit')->unsigned()->default(0);
            $table->string('unit',60)->nullable();
            $table->string('vendor_item_code',40)->nullable();
            $table->string('vendor_item_category',80)->nullable();
            $table->bigInteger('vendor_id')->unsigned()->index();
            $table->bigInteger('item_category_id')->unsigned()->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
