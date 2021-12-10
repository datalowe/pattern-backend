<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoggTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logg', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('customer_id')->nullable()->index('customer_id');
            $table->integer('scooter_id')->nullable()->index('scooter_id');
            $table->dateTime('start_time')->nullable()->useCurrent();
            $table->dateTime('end_time')->nullable();
            $table->decimal('start_lat', 9, 6)->nullable();
            $table->decimal('start_lon', 9, 6)->nullable();
            $table->decimal('end_lat', 9, 6)->nullable();
            $table->decimal('end_lon', 9, 6)->nullable();
            $table->decimal('start_cost', 7)->nullable()->default(0);
            $table->decimal('travel_cost', 7)->nullable()->default(0);
            $table->decimal('parking_cost', 7)->nullable()->default(0);
            $table->decimal('total_cost', 7)->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logg');
    }
}
