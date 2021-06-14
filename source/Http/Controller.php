<?php

namespace Pluton\Http;

class Controller {

    /**
     *  View fayl(lar)ı daxil edəcək və həmin fayl(lar)a
     *  təyin olduğu təqdirdə məlumatları ötürəcək.
     * 
     *  @param string $fileName
     *  @param array $data
     *  @return void
     */
    public function view(string $fileName, array $data = []) : void {

        # Faylın adı və istiqaməti.
        $viewFile = VIEW . $fileName . '.phtml';

        # Faylın mövcudluq kontrolu.
        if(file_exists($viewFile)) {

            # Göndərilmiş məlumat varsa çıxarış etsin.
            if(count($data) > 0) extract($data); 
            include_once $viewFile;
        }else {

            # Xəta! Fayl mövcud deyil.
            warning([
                'title' => '"View" xətası!',
                'hint' => $fileName . '.phtml faylı mövcud deyil.'
            ]);  
        };
        exit;
    }
}