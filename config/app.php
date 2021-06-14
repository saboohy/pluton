<?php

return [
    
    /**
     *  Zaman qurşağı...
     */
    'timezone'  => 'Asia/Baku',

    /**
     *  Plutonun mövcud versiyası...
     */
    'version'   => '1.0.0',

    /**
     *  Xəta göstərilməsi...
     */
    'report_e'  => 0,

    /**
     *  Kitabxanalar...
     */
    'lib'       => [
        
        'session' => \Pluton\Library\Session\Session::class,
        'cookie' => \Pluton\Library\Cookie\Cookie::class,
        'paginator' => \Pluton\Library\Pagination\Paginator::class,
        'validator' => \Pluton\Library\Validation\Validator::class
    ]
];