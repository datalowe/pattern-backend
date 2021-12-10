<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToLoggTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logg', function (Blueprint $table) {
            $table->foreign(['customer_id'], 'logg_ibfk_1')->references(['id'])->on('customer')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['scooter_id'], 'logg_ibfk_2')->references(['id'])->on('scooter')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logg', function (Blueprint $table) {
            $table->dropForeign('logg_ibfk_1');
            $table->dropForeign('logg_ibfk_2');
        });
    }
}
