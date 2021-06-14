<?php

namespace Pluton;

class App {

    /**
     *  Layihənin işə düşməsi üçün hazırlıq
     *  tədbirlərini yerinə yetirəcək.
     * 
     *  @return void
     */
    public static function ready() : void {

        # Tənzimləmələr...
        $app = include CONFIG . 'app.php';

        # Zaman qurşağının təyinatı...
        date_default_timezone_set($app['timezone']);

        # Xəta göstərişi...
        error_reporting($app['report_e']);

        # Rota...
        include_once HTTP . 'routes.php';
    }
}