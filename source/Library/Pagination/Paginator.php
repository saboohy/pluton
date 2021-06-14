<?php

namespace Pluton\Library\Pagination;

class Paginator {

    /**
     *  Səhifələrmə prosesinin reallaşacağı 
     *  istiqamət(URI) adını tutacaq.
     * 
     *  @var string
     */
    private $paginatorUri;

    /**
     *  Səhifələnəcək tablonun bütün sətirlərinin
     *  sayını tutacaq.
     * 
     *  @var int
     */
    private $dataCount;

    /**
     *  Səhifələmənin limit sayını tutacaq.
     * 
     *  @var int
     */
    private $limit;

    /**
     *  Səhifələmə tənzimləməsinin dəyərlərini alacaq.
     * 
     *  @param string $paginatorUri
     *  @param int $dataCount
     *  @param int $limit
     *  @return void
     */
    public function set(string $paginatorUri, int $dataCount, int $limit) : void {

        $this->paginatorUri = preg_replace('/{([a-zA-Z0-9:@#-_,.]+)}/', '([a-zA-Z0-9:@#-_,.]+)', '/' . str_replace('/', '\/', $paginatorUri) . '/');
        $this->dataCount = $dataCount;
        $this->limit = $limit;
    }

    /**
     *  "Əvvəlki səhifə" dəyərini qaytaracaq.
     * 
     *  @return int
     */
    public function previousPage() : int {

        # Təyin olunan istiqamət mövcud HTTP istiqamətilə uyğun gələrsə...
        if(preg_match($this->paginatorUri, HTTP_PATH, $matched)) {
            
            # Nəticənin ilk indeksini silsin!
            array_shift($matched);

            # Nəticədən mövcud sayını tutur.
            $currentPageNumber = end($matched);

            /**
             *  Mövcud səhifənin nömrəsi 1 deyilsə dəyərdən
             *  1 azaldacaq, 1 olarsa sabit qaytaracaq.
             */
            if($currentPageNumber == 1) return $currentPageNumber;
            else return $currentPageNumber -1;
        }else {

            # Səhifələmə prosesi olmayıbsa 1 qaytarsın.
            return 1;
        }
    }

    /**
     *  "Növbəti səhifə" dəyərini qaytaracaq.
     * 
     *  @return int
     */
    public function nextPage() : int {

        # Təyin olunan istiqamət mövcud HTTP istiqamətilə uyğun gələrsə...
        if(preg_match($this->paginatorUri, HTTP_PATH, $matched)) {
           
            # Nəticənin ilk indeksini silsin!
            array_shift($matched);
            
            # Nəticədən mövcud sayını tutur.
            $currentPageNumber = end($matched);
            
            # Ümumi səhifənin sayı.
            $pageCount = ceil($this->dataCount / $this->limit);

            /**
             *  Mövcu səhifə nömrəsi səhifə sayına
             *  bərbar olarsa səhifə nömrəsini,
             *  olmazsa səhifə sayı + 1 qaytarsın.
             */
            if($currentPageNumber == $pageCount) return $currentPageNumber;
            else return $currentPageNumber +1; 
        }else {

            # Səhifələmə olmadığı təqdirsə 2-ci səhifə dəyərini qaytarsın.
            return 2;
        }
        
    }
}