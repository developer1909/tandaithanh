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
        if (Schema::hasTable('order_products'))
        {
            Schema::table('order_products', function (Blueprint $table) {
                if(!Schema::hasColumn('order_products','warehouse')){
                    $table->smallInteger('warehouse')->nullable();
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
        Schema::table('order_products', function (Blueprint $table) {
            if(Schema::hasColumn('order_products','warehouse'))
                $table->dropColumn('warehouse');
        });
    }
};
