<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('account_statement', function (Blueprint $table) {
            // حذف المفتاح الأجنبي أولًا
            $table->dropForeign(['order_id']);
            // حذف العمود
            $table->dropColumn('order_id');
        });
    }

    public function down()
    {
        Schema::table('account_statement', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }
};
