<?php

/**
 *  Aldığı massiv dəyərin açar sözləri və 
 *  dəyərlərindən SQL cümləsi qaytaracaq.
 * 
 *  @param array $array
 *  @return string
 */
if(!function_exists('arrayToStatement')) {

    function arrayToStatement(array $array) : string {

        /**
         *  Arqumentlə gələn massivdə açarsöz sayı 1 olarsa
         *  cümlə `filed` = 'data' şəklində olacaq.
         */
        if(count($array) === 1) {

            # Cümləni tutacaq...
            $statement = null;

            # Gələn massivi açarsöz və dəyərə görə dövrdə açmaq.
            foreach($array as $field => $data) {

                # Cümlə hazır...
                $statement = "`{$field}` = '{$data}'";
            }

            return (string) $statement;
        }
        /**
         *  Arqumentlə gələ massivdə açarsöz sayı 1-dən çox
         *  olarsa cümləyə `AND` operatoru əlavə olunacaq.
         */
        else {

            # Cümlələri tutacaq...
            $statement = [];

            # Gələn massivi açarsöz və dəyərə görə dövrdə açmaq.
            foreach($array as $field => $data) {

                # Cümlələr hazır...
                $statement[] = "`{$field}` = '{$data}'";
            }

            # Massiv dəyərlərinin arasına `AND` əlavə edərək tam cümləni qaytarsın.
            return (string) implode($statement, ' AND ');
        }
    }
}

/**
 *  Aldığı massivin açar sözlərilə SQL cümləsi
 *  üçün sütun adlarını string tipində hazırlayacaq.
 * 
 *  @param array $tableData
 *  @return string
 */
if(!function_exists('arrayToFieldStatement')) {

    function arrayToFieldStatement(array $tableData) : string {

        # Sütunları tutacaq.
        $fields = [];

        # Massivi açar sözlərinə görə dövrə salmaq.
        foreach(array_keys($tableData) as $key) {

            # Sütunları toplasın.
            $fields[] = "`{$key}`";
        }
        
        # Massiv üzvlərini string halında döndürsün!
        return (string) implode(',', $fields);
    }
}

/**
 *  Aldığı massivin dəyərlərilə SQL cümləsi üçün
 *  dəyərləri string tipində döndürəcək.
 * 
 *  @param array $tableData
 *  @return string
 */
if(!function_exists('arrayToValueStatement')) {

    function arrayToValueStatement(array $tableData) : string {

        # Dəyərləri tutacaq
        $values = [];

        # Massivi dəyərlərinə görə dövrdə açmaq.
        foreach (array_values($tableData) as $value) {
            
            # Uyğun olmayan simvolları zərərsizləşdirsin!
            $value = htmlentities($value, ENT_QUOTES, "UTF-8");

            # Dəyərləri toplasın!
            $values[] = "'{$value}'";
        }

        # Massiv üzvlərini string halında döndürsün!
        return (string) implode(',', $values);
    }
}

/**
 *  Aldığı massivi açar söz və dəyərinə görə 
 *  bərabərlik operatoru ilə cümləni quracaq
 *  və qurulan cümlələr aralığında sonradan
 *  əlavə olunacaq operatorla tam SQL cümləsini
 *  string tipində döndürəcək.
 * 
 *  @param array $tableData
 *  @param string $opr
 *  @return string
 */
if(!function_exists('arrayToMatchStatement')) {

    function arrayToMatchStatement(array $tableData) : string {

        # Uyğunlaşdırılmış dəyərləri tutacaq.
        $matches = [];

        # Massivi açar söz və dəyərə görə dövrdə açmaq.
        foreach($tableData as $key => $value) {

            # Uyğun olmayan simvolları zərərsizləşdirsin!
            $value = htmlentities($value, ENT_QUOTES, "UTF-8");
            
            # Uyğunlaşan dəyərlər toplanır.
            $matches[] = "`$key` = '{$value}'";
        }

        # Massiv üzvlərini string halında döndürsün!
        return (string) implode(',', $matches);
    }
}