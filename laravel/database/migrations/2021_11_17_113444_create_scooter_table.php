<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScooterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scooter', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('customer_id')->nullable()->index('customer_id');
            $table->integer('city_id')->nullable()->index('city_id');
            $table->integer('station_id')->nullable()->index('station_id');
            $table->decimal('lat_pos', 9, 6)->nullable();
            $table->decimal('lon_pos', 9, 6)->nullable();
            $table->boolean('active')->nullable()->default(true);
            $table->integer('speed_kph')->nullable()->default(0);
            $table->integer('battery_level')->nullable()->default(100);
            $table->enum('status', ['active', 'inactive', 'maintenance'])->nullable()->default('active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scooter');
    }
}
