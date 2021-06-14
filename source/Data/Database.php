<?php

namespace Pluton\Data;

class Database {
    
    /**
     *  MySQL ilə bağlantını tutacaq.
     * 
     *  @var object
     */
    private $db;

    /**
     *  MySQL sorğusunu tutacaq.
     * 
     *  @var object
     */
    private $query;

    /**
     *  Sətir məlumatlarını titacaq.
     * 
     *  @var array
     */
    private $rows = [];

    /**
     *  Sinif istifadə olunduğu anda MySQL ilə
     *  bağlantını təmin edəcək.
     * 
     *  @return void
     */
    public function __construct() {

        # Bağlantı məlumatları...
        $config = include CONFIG . 'database.php';

        # Bağlantı...
        $this->db   =   @mysqli_connect($config['host'], $config['user'], $config['pass'], $config['base'])
                    or  warning([
                        'title' => 'MySQL xətası!',
                        'hint' => 'Bağlantı təmin oluna bilmir.'
                    ]);
        # Sorğuda yazı xarakteri...
        mysqli_set_charset($this->db, $config['charset']);
    }

    /**
     *  MySQL sorğusunu icra edəcək.
     * 
     *  @param string $statement
     *  @return object
     */
    public function query(string $statement) : object {
        
        # İcraat...
        $this->query = mysqli_query($this->db, $statement);

        # Sorğu icra olunmasa...
        if(!$this->query) die("<b>MySQL xətası!</b> Sorğu icra olunmadı.");
        return $this;
    }

    /**
     *  Sorğudan alınan sətir sayını qaytaracaq.
     * 
     *  @return int
     */
    public function rowCount() : int {

        return (int) mysqli_num_rows($this->query);
    }

    /**
     *  Sorğudan alınan sətir məlumatlarını qaytaracaq.
     * 
     *  @return object
     */
    public function fetchRow() : object {

        return (object) mysqli_fetch_object($this->query);
    }

    /**
     *  Sorğudan alınan sətir məlumatlarını qaytaracaq.
     * 
     *  @return object
     */
    public function fetchAll() : object {

        # Gələn sətir məlumatları dövrdə açılsın..
        while($row = mysqli_fetch_object($this->query)) {

            # Sətir məlumatarı massivə yerləşir...
            $this->rows[] = $row;
        }

        # Massiv pbyektər çevrilərək qayıtsın...
        return (object) $this->rows;
    }
}