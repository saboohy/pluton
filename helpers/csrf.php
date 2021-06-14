<?php

/**
 *  Anlıq csrf token dəyəri yaradıb
 *  sessiyaya əlavə edəcək.
 * 
 *  @return string
 */
if(!function_exists('csrf_token')) {

    function csrf_token() : string {

        # Sessiya kitabxanası...
        $session = lib('session');

        # Yaradılan token dəyəri...
        $token = bin2hex(random_bytes(32));

        # Token varsa silsin, yenisini yaratsın...
        $session->set('_csrf', $token);
        
        # Token dəyərini qaytarsın...
        return $token;
    }
}

/**
 *  Tokenin mövcudluğunu bildirəcək.
 * 
 *  @return bool
 */
if(!function_exists('csrf_check')) {

    function csrf_check() : bool {

        # Sessiya kitabxanası...
        $session = lib('session');

        # Mövcuddursa, uyğundursa doğru qaytarsın.
        if(
            isset($_REQUEST['_token'])
            and $_REQUEST['_token'] === $session->get('_csrf')
        ) return true;
        else return false;
    }
}