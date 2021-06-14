<?php

/**
 *  Kitabxana daxil edəcək.
 * 
 *  @param string $libName
 *  @return object
 */
if(!function_exists('lib')) {

    function lib(string $libName) {

        # Ümumi konfiq. 
        $config = include CONFIG . 'app.php';

        # Arqumentlər gələn dəyərin massivdəki mövcudluğu...
        if(array_key_exists($libName, $config['lib'])) {

            # Dəyərə uyğun sinfi döndürsün.
            return new $config['lib'][$libName];
        }else {

            # Xəta!
            warning([
                'title' => 'Kitabxana xətası!',
                'hint' => 'Təyin olunan dəyər səhvdir.'
            ]);
        }
    }
}