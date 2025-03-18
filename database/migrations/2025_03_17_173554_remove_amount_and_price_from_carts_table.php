<?php


// Migration to remove amount and price columns
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveAmountAndPriceFromCartTable extends Migration
{
    public function up()
    {
        Schema::table('cart', function (Blueprint $table) {
            $table->dropColumn(['amount', 'price']);
        });
    }

    public function down()
    {
        Schema::table('cart', function (Blueprint $table) {
            $table->decimal('amount', 10, 2)->nullable();
            $table->decimal('price', 10, 2)->nullable();
        });
    }
}
