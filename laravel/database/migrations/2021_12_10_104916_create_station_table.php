<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('station', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('city_id')->nullable()->index('city_id');
            $table->string('location', 20)->nullable();
            $table->decimal('lat_center', 9, 6)->nullable();
            $table->decimal('lon_center', 9, 6)->nullable();
            $table->decimal('radius', 4, 3)->nullable()->default(0.002);
            $table->enum('type', ['charge', 'park'])->nullable()->default('charge');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('station');
    }
}
