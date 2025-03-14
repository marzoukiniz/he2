<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLengthIdAndColorIdToCartsTable extends Migration
{
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->unsignedBigInteger('length_id')->nullable(); // Add nullable if you don't want this to be required
            $table->unsignedBigInteger('color_id')->nullable();  // Add nullable if you don't want this to be required

            // Optionally, you can add foreign keys if you want to associate these with other tables:
            // $table->foreign('length_id')->references('id')->on('product_lengths')->onDelete('cascade');
            // $table->foreign('color_id')->references('id')->on('product_colors')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn('length_id');
            $table->dropColumn('color_id');
        });
    }
}

