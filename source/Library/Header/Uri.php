<?php

namespace Pluton\Library\Header;

class Uri {
    
    /**
     *  Yönləndiriləcək istiqaməti tutacaq.
     * 
     *  @var string
     */
    private $uri;

    /**
     *  Yönləndiriləcək istiqaməti alacaq.
     * 
     *  @param string $uri
     *  @return void
     */
    public function __construct(string $uri) {

        ob_start();
        $this->uri = $uri;
    }

    /**
     *  Təyin olunmuş istiqamətə yönləndirəcək.
     * 
     *  @return void
     */
    public function redirect() : void {

        header("Location: {$this->uri}");
        exit;
    }

    /**
     *  Təyin olunmuş istiqamətə alacağı saniyə
     *  dəyərinə görə (yeniləyərək) yönləndirəcək.
     * 
     *  @param int $second
     *  @return void
     */
    public function refresh(int $second) : void {

        $second = (string) $second;
        header("Refresh:{$second}; url={$this->uri}");
        exit;
    }
}