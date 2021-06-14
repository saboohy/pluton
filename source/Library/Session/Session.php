<?php

namespace Pluton\Library\Session;

class Session {

    /**
     *  Initial constructor
     * 
     *  Sinif çağırılan zaman sessiya başladacaq.
     */
    public function __construct() {

        if(!isset($_SESSION)) session_start();
    }

    /**
     *  Mövcud sessiya və dəyərləri ləğv edəcək.
     * 
     *  @return void
     */
    public function close() : void {

        session_unset();
        session_destroy();
    }

    /**
     *  Təyin olunmuş sessiyanın mövcudluğunu bildirəcək.
     * 
     *  @param string $key
     *  @return bool
     */
    public function isset(string $key) : bool {

        $found = (isset($_SESSION[$key])) ? true : false;
        return $found;
    }

    /**
     *  Sessiya üzvünü ləğv edəcək.
     * 
     *  @param string $key
     *  @return void
     */
    public function unset(string $key) : void {

        if($this->isset($key) === true) unset($_SESSION[$key]); 
    }

    /**
     *  Mövcud sessiya dəyərini qaytaracaq.
     *  
     *  @param string $key
     *  @return mixed
     */
    public function get(string $key) {

        if($this->isset($key) === true) return $_SESSION[$key]; 
    }

    /**
     *  Sessiya dəyərlərini təyin edəcək.
     * 
     *  @param mixed $key
     *  @param mixed $value
     *  @return void
     */
    public function set($key, $value = null) : void {

        # Sessiya toplu şəkildə (array) təyin olunarsa...
        if(is_array($key)) {

            # Massivi açarsöz və dəyərə görə dövrdə açmaq...
            foreach($key as $sessionKey => $sessionValue) {

                /**
                 *  Dəyər say, string, bool və açarsöz string
                 *  tipində olarsa sessiya təyin oluna bilər.
                 */
                if(
                    is_numeric($sessionValue) or
                    is_string($sessionValue) or
                    is_bool($sessionValue) and
                    is_string($sessionKey)
                ) {

                    # Təyinat...
                    $_SESSION[$sessionKey] = $sessionValue;
                }else {

                    # Xəta!
                    warning([
                        'title' => 'Sessiya xətası!',
                        'hint' => 'Məlumat tipləri uyğun deyil.'
                    ]);
                }
            }
        }
        # Xüsusi sessiya təyinatı.
        else if(
            is_string($key) and
            (
                !is_null($value) and (is_string($value) or is_numeric($value))
            )
        ) {

            # Təyinat...
            $_SESSION[$key] = $value;
        }
        # Xəta!
        else {
            warning([
                'title' => 'Sessiya xətası!',
                'hint' => 'Məlumat tipləri uyğun deyil.'
            ]);
        }
    }
}