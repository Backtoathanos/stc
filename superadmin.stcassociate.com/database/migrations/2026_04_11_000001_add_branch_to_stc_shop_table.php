<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddBranchToStcShopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('stc_shop')) {
            return;
        }

        if (!Schema::hasColumn('stc_shop', 'branch')) {
            Schema::table('stc_shop', function (Blueprint $table) {
                $table->string('branch', 255)->nullable()->after('shopname');
            });
        }

        DB::statement('UPDATE stc_shop SET branch = shopname');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('stc_shop') && Schema::hasColumn('stc_shop', 'branch')) {
            Schema::table('stc_shop', function (Blueprint $table) {
                $table->dropColumn('branch');
            });
        }
    }
}
