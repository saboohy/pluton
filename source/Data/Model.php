<?php

namespace Pluton\Data;

use \Pluton\Data\Database;
use \Pluton\Library\Session\Session;

class Model {

    /**
     *  Database sinfini tutacaq.
     * 
     *  @var object
     */
    protected $db;

    /**
     *  Session sinfini tutacaq.
     * 
     *  @var object
     */
    private $session;

    /**
     *  Törəyən sinfə sonradan əlavə olunacaq obyektləri
     *  massiv şəklində aldıqdan sonra həmin dəyərləri
     *  tablonun sütunu və dəyəri kimi tutacaq.
     *  
     *  @var array
     */
    private $tableFields = [];

    /**
     *  Sətir məlumatlarının təsdiq təyəri.
     * 
     *  @var bool
     */
    public $auth = false;

    /**
     *  Sinfə müraciət olunan zaman Database və Session
     *  sinfini işə salacaq.
     * 
     *  @return void
     */
    public function __construct() {

        $this->db = new Database;
        $this->session = new Session;
    }

    /**
     *  Törəyən sinfin dəyişənlərini qaytaracaq.
     * 
     *  @return object
     */
    private static function getCalledVars() : object {

        # Törəyən sinif...
        $getChildClass = get_called_class();

        # Törəyən sinfin dəyişənləri...
        return (object) get_object_vars(new $getChildClass);
    }

    /**
     *  Təyin olunan tablodan bütün sətir məlumatlarını qaytaracaq.
     * 
     *  @param string $cending
     *  @param array $where
     *  @param array $limit
     *  @return object
     */
    public static function all(array $where = [], array $limit = [], string $cending = 'ASC') : object {

        # Dəyişənlər...
        $childVars = self::getCalledVars();

        # Gələn dəyəri böyütsün...
        $cending = strtoupper($cending);

        # İcra olunacaq SQL cümləsi...
        $statement = null;

        # Limit təyinatı...
        $limited = null;

        # Meyar və limit təyinatı varsa...
        if(count($where) > 0 and count($limit) > 0) {

            # Sadəcə limit dəyəri...
            if(count($limit) === 1) {

                $limited = "{$limit[0]}";
            }

            # Səhifələmə limiti...
            else {

                $start = $limit[0] * $limit[1] - $limit[1];
                $limited = "{$start}, {$limit[1]}";
            };

            # SQL cümləsi...
            $statement = "
                SELECT * FROM `{$childVars->table}`
                WHERE ". arrayToStatement($where) ."
                ORDER BY `{$childVars->primaryKey}`
                {$cending}
                LIMIT {$limited}
            ";
            
        }
        # Meyar təyinatı varsa, limit təyinatı yoxdursa...
        elseif(count($where) > 0 and count($limit) === 0) {

            # SQL cümləsi...
            $statement = "
                SELECT * FROM `{$childVars->table}`
                WHERE ". arrayToStatement($where) ."
                ORDER BY `{$childVars->primaryKey}`
                {$cending}
            ";
        }
        # Meyar təyinatı yoxdursa, limit təyinatı varsa...
        elseif(count($where) === 0 and count($limit) > 0) {

            # Sadəcə limit dəyəri...
            if(count($limit) === 1) {

                $limited = "{$limit[0]}";
            }

            # Səhifələmə limiti...
            else {

                $start = $limit[0] * $limit[1] - $limit[1];
                $limited = "{$start}, {$limit[1]}";
            };

            # SQL cümləsi...
            $statement = "
                SELECT * FROM `{$childVars->table}`
                ORDER BY `{$childVars->primaryKey}`
                {$cending}
                LIMIT {$limited}
            ";
        }
        # Nə meyar, nə də limit təyinatı varsa...
        else {

            # SQL cümləsi...
            $statement = "
                SELECT * FROM `{$childVars->table}`
                ORDER BY `{$childVars->primaryKey}`
                {$cending}
            ";
        }

        # İcraat...
        return (new Database)->query($statement)->fetchAll();
    }

    /**
     *  Təyin olunan tablodan sadəcə sətir məlumatlarını qaytaracaq.
     * 
     *  @param array $where
     *  @return object
     */
    public function get(array $where) : object {

        # Dəyişənlər...
        $childVars = self::getCalledVars();

        # SQL cümləsi...
        $statement = "
            SELECT * FROM `{$childVars->table}`
            WHERE " . arrayToStatement($where);

        # İcraat...
        return (new Database)->query($statement)->fetchRow();
    }

    /**
     *  Təyin olunan tablodan `primaryKey` sütununa görə sətir siləcək.
     * 
     *  @param int $primaryKeyValue
     *  @return object
     */
    public static function delete(int $primaryKeyValue) : object {

        # Dəyişənlər...
        $childVars = self::getCalledVars();

        # Gələn dəyəri stringə çevirsin!
        $primaryKeyValue = (string) $primaryKeyValue;

        # SQL cümləsi...
        $statement = "
            DELETE FROM `{$childVars->table}`
            WHERE `{$childVars->primaryKey}` = '{$primaryKeyValue}'
        ";

        # İcraat...
        return (new Database)->query($statement);
    }

    /**
     *  Təyin olunan tablo üzərində aparılan sorğudan
     *  alınan sətir satıyını qaytaracaq.
     * 
     *  @param array $where
     *  @return int
     */
    public static function count(array $where) : int {
        
        # Dəyişənlər...
        $childVars = self::getCalledVars();

        # İcra olunacaq SQL cümləsi...
        $statement = null;

        # Meyar təyinatı varsa...
        if(count($where) > 0) {

            # SQL cümləsi...
            $statement = "
                SELECT * FROM `{$childVars->table}`
                WHERE " . arrayToStatement($where);
        }
        # Meyar təyinato yoxdursa...
        else {

            # SQL cümləsi...
            $statement = "SELECT * FROM `{$childVars->table}`";
        }

        # İcraat...
        return (new Database)->query($statement)->rowCount();
    }

    /**
     *  Törəyən və mövcud sinifdən protected
     *  olmayan objektləri massivə çevirəcək.
     */
    public function buildMap() : void {

        # Törəyən və mövcud sinfi obyektlərinə görə dövrə salmaq.
        foreach($this as $obj => $value) {

            /**
             *  Obyektin dəyəri string və ya say olan,
             *  obyektin adı isə primaryKey, table və
             *  fields olmayan obyektləri tableFields
             *  massivinə alsın.
             */
            if(
                (is_numeric($value) or is_string($value))
                and $obj !== 'primaryKey'
                and $obj !== 'table'
                and $obj !== 'fields'
            ) $this->tableFields[$obj] = $value;
        }
    }

    /**
     *  Təyin olunan tablo üzərində INSERT əmrini yerinə yetirəcək.
     *  
     *  @return object
     */
    public function add() : object {

        # Sonradan gələn obyektləri gətirsin..
        $this->buildMap();
        
        # SQL cümləsi.
        $statement = "
            INSERT INTO `{$this->table}`
            (". arrayToFieldStatement($this->tableFields) .")
            VALUES (". arrayToValueStatement($this->tableFields) .")
        ";

        # İcraat.
        return $this->db->query($statement);
    }

    /**
     *  Təyin olunan tablo üzərində UPDATE əmrini yerinə yetirəcək.
     *  
     *  @param int $primaryKeyValue
     *  @return object
     */
    public function save(int $primaryKeyValue) : object {

        # Sonradan gələn obyektləri gətirsin..
        $this->buildMap();

        # Gələn dəyəri stringə çevirsin!
        $primaryKeyValue = (string) $primaryKeyValue;

        # SQL cümləsi.
        $statement = "
            UPDATE `{$this->table}`
            SET ". arrayToMatchStatement($this->tableFields) ."
            WHERE `{$this->primaryKey}` = '{$primaryKeyValue}'
        ";

        # İcraat.
        return $this->db->query($statement);
    }

    /**
     *  Təyin olunan tablo üzərində (istifadəçi)
     *  təsdiqləmə əmrini yetirə gətirdikdən sonra
     *  sessiya başladacaq.
     * 
     *  @param string $sessionKey
     *  @return void
     */
    public function auth(string $sessionKey = null) : void {

        # Sonradan gələn obyektləri gətirsin..
        $this->buildMap();
        
        # Sessiya təyinatı varsa...
        if(!is_null($sessionKey)) {

            # İcraat...
            $query = $this->db->query("
                SELECT * FROM `{$this->table}`
                WHERE " . arrayToStatement($this->tableFields)
            );

            # Tablonun əsas sütunu...
            $primaryKey = $this->primaryKey;

            if($query->rowCount() > 0) {

                # Sorğudan alınan sətir məlumatları...
                $row = $query->fetchRow();

                # Təsdiqləmə...
                $this->auth = true;

                /**
                 *  İlkin sessiya təyinatı, açar söz və
                 *  tablonun əsas sütunu (primary key) olacaq.
                 */
                $this->session->set([
                    $sessionKey => true,
                    $primaryKey => $row->$primaryKey
                ]);
                
                # Törəyən sinifdə müdaxilə olunacaq tablo sütunları təyin olunubsa.
                if(count($this->fields) > 0) {

                    foreach($this->fields as $field) {

                        $this->session->set([
                            $field => $row->$field
                        ]);
                    }
                }
            }
        }else {

            # Xəta!
            warning([
                'title' => 'İdentifikasiya xətası!',
                'hint' => 'Sessiya açarsözü təyin olunmayıb.'
            ]);
        }
    }
}