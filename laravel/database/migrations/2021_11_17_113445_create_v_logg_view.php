<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateVLoggView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE VIEW `v_logg` AS select `cus`.`username` AS `username`,`s`.`location` AS `location`,`l`.`start_time` AS `start_time`,`l`.`end_time` AS `end_time`,`l`.`start_lat` AS `start_lat`,`l`.`start_lon` AS `start_lon`,`l`.`end_lat` AS `end_lat`,`l`.`end_lon` AS `end_lon`,`l`.`total_cost` AS `total_cost`,`c`.`name` AS `name` from ((((`sctr`.`customer` `cus` left join `sctr`.`logg` `l` on((`cus`.`id` = `l`.`customer_id`))) left join `sctr`.`scooter` `sc` on((`sc`.`id` = `l`.`scooter_id`))) left join `sctr`.`station` `s` on((`s`.`id` = `sc`.`station_id`))) left join `sctr`.`city` `c` on((`c`.`id` = `s`.`id`)))");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS `v_logg`");
    }
}
