<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('orders'))
        {
            Schema::table('orders', function (Blueprint $table) {
                if(!Schema::hasColumn('orders','status_export')){
                    $table->smallInteger('status_export')->default(0);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('orders', function (Blueprint $table) {
            if(Schema::hasColumn('orders','status_export'))
                $table->dropColumn('status_export');
        });
    }
};
