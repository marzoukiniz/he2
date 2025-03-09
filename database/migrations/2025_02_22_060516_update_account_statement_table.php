<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('account_statement', function (Blueprint $table) {
            $table->decimal('in', 10, 2)->nullable()->after('total_expense');
            $table->decimal('out', 10, 2)->nullable()->after('in');
            $table->text('notes')->nullable()->after('out');
        });
    }

    public function down()
    {
        Schema::table('account_statement', function (Blueprint $table) {
            $table->dropColumn(['in', 'out', 'notes']);
        });
    }
};
