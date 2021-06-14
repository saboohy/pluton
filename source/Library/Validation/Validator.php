<?php

namespace Pluton\Library\Validation;

class Validator {

    /**
     *  Qəbul olunan və ya təsdiqlənən dəyərləri tutacaq.
     * 
     *  @var array
     */
    private $fields = [];

    /**
     *  $_REQUEST, $_POST və $_GET ilə qəbul olunan məlumatları
     *  və şərtləri tutacaq. 
     * 
     *  @var array
     */
    private $rpgRules = [];

    /**
     *  $_FILES ilə qəbul olunan məlumatları və şərtləri tutacaq.
     * 
     *  @var array
     */
    private $fileRules = [];

    /**
     *  Xətaları tutacaq.
     * 
     *  @var array
     */
    private $errors = [];

    /**
     *  $_REQUEST qlobalı ilə alına biləcək məlumatları
     *  və şərtləri alacaq.
     * 
     *  @param string $data
     *  @param array $rules
     *  @return void
     */
    public function request(string $data, array $rules) : void {

        $this->rpgRules[$data][$_REQUEST[$data]] = $rules;
    }

    /**
     *  $_POST qlobalı ilə alına biləcək məlumatları
     *  və şərtləri alacaq.
     * 
     *  @param string $data
     *  @param array $rules
     *  @return void
     */
    public function post(string $data, array $rules) : void {

        $this->rpgRules[$data][$_POST[$data]] = $rules;
    }

    /**
     *  $_GET qlobalı ilə alına biləcək məlumatları
     *  və şərtləri alacaq.
     * 
     *  @param string $data
     *  @param array $rules
     *  @return void
     */
    public function get(string $data, $rules) : void {

        $this->rpgRules[$data][$_GET[$data]] = $rules;
    }

    /**
     *  $_FILES qlobalı ilə alına biləcək məlumatları
     *  və şərtləri alacaq.
     * 
     *  @param string $data
     *  @param array $rules
     *  @return void
     */
    public function file(string $data, $rules) : void {

        $this->fileRules[$data][$_FILES[$data]['name']] = $rules;
    }

    /**
     *  rpgRules və fileRules massivinə yığılan 
     *  məlumatları təsdiqləyəcək.
     * 
     *  @return void
     */
    public function validate() : void {

        /**
         *  $_REQUEST, $_POST və $_GET qlobalları ilə
         *  alınan məlumatlara şərtlər təyin olunduğu
         *  təqdirdə yoxlamaya başlayacaq.
         */
        if(count($this->rpgRules) > 0) {

            # Massivi açarsöz(html name) və detallara görə dövrdə açsın.
            foreach($this->rpgRules as $field => $details) {

                # Detalları dəyər və şərtlərə görə dövrdə açsın.
                foreach($details as $value => $rules) {

                    /**
                     *  İki dəyərin eynilik kontrolu
                     *  ----------------------------------------
                     *  Hissə adı təyin olunan dəyərlə öz
                     *  dəyəri uyğun olduğu təqdirdə təsdiq
                     *  olunacaq.
                     * 
                     *  equal => fieldName 
                     */
                    if(array_key_exists('equal', $rules)) {

                        $fieldName = $rules['equal'];
                        $equaledData = array_key_first($this->rpgRules[$fieldName]);
                        if($value === $equaledData) $this->fields[$field] = $value;
                        else $this->errors[] = "{$field} and {$fieldName} are not equal.";
                    }

                    /**
                     *  Mütləqlik kontrolu.
                     *  ----------------------------------------
                     *  Təyinat true olarsa dəyəri mütləq hesab
                     *  edəcək.
                     *  
                     *  required => true
                     */
                    if(array_key_exists('required', $rules)) {

                        if($rules['required'] === true) {

                            if(!empty($value)) $this->fields[$field] = $value;
                            else $this->errors[] = "{$field} is required.";
                        }else {
                            $this->fields[$field] = $value;
                        }
                    }

                    /**
                     *  Minimum limit kontrolu.
                     *  ----------------------------------------
                     *  Dəyərin simvol sayı təyinatdakı limitə
                     *  böyük bərabər olarsa məlumat istifadə
                     *  oluna bilər.
                     *  
                     *  min_len => limit
                     */
                    if(array_key_exists('min_len', $rules)) {

                        if(strlen($value) >= $rules['min_len']) $this->fields[$field] = $value; 
                        else $this->errors[] = "{$field} must be a minimum {$rules['min_len']} characters.";
                    }

                    /**
                     *  Maksimum limit kontrolu.
                     *  ----------------------------------------
                     *  Dəyərin simvol sayı təyinatdakı limitə
                     *  böyük bərabər olarsa məlumat istifadə
                     *  oluna bilər.
                     *  
                     *  max_len => limit
                     */
                    if(array_key_exists('max_len', $rules)) {

                        if(strlen($value) <= $rules['max_len']) $this->fields[$field] = $value; 
                        else $this->errors[] = "{$field} must be a maximum {$rules['max_len']} characters.";
                    }

                    /**
                     *  E-poçt kontrolu.
                     *  ----------------------------------------
                     *  Təyinat true olduğu təqdirdə dəyərin
                     *  e-poçt adresi standardına uyğunluğunu
                     *  mütləq edəcək.
                     *  
                     *  email => true
                     */
                    if(array_key_exists('email', $rules)) {

                        if($rules['email'] === true) {

                            if(filter_var($value, FILTER_VALIDATE_EMAIL)) $this->fields[$field] = $value;
                            else $this->errors[] = "{$field} is not e-mail.";
                        }
                    }

                    /**
                     *  Dəyərdə boşluq(space) kontrolu.
                     *  ----------------------------------------
                     *  Təyinat false olduğu təqdirdə dəyərdə
                     *  boşluğa(space) icazə verməyəcək. 
                     * 
                     *  space => false
                     */
                    if(array_key_exists('space', $rules)) {

                        if($rules['space'] === false) {

                            if(!preg_match('/\s/', $value)) $this->fields[$field] = $value;
                            else $this->errors[] = "There will be no space in the value {$field}.";
                        }
                    }

                    /**
                     *  Simvol uyğunluq kontrolu.
                     *  ----------------------------------------
                     *  Dəyər təyin olunan şərtə uyöun gələrsə
                     *  istifadəyə icazə verəcək.
                     *  
                     *  match => /rule/
                     */
                    if(array_key_exists('match', $rules)) {

                        if(preg_match($rules['match'], $value)) $this->fields[$field] = $value;
                        else $this->errors[] = "The value of {$field} contains inappropriate characters.";
                    }
                }
            }
        }

        /**
         *  $_FILES qlobalı ilə alınan fayl və şərt
         *  təyinatı olduğu təqdirdə təsdiqləmə
         *  başlayacaq.
         */
        if(count($this->fileRules) > 0) {

            # Massivi açarsöz(html name) və detallara görə dövrdə açsın.
            foreach($this->fileRules as $field => $details) {

                # Detalları dəyər və şərtlərə görə dövrdə açsın.
                foreach($details as $value => $rules) {

                    /**
                     *  Faylın mütləqlik kontrolu.
                     *  ----------------------------------------
                     *  Təyinat true olduğu təqdirdə dəyəri
                     *  mütləq hesab edəcək.
                     * 
                     *  required => true
                     */
                    if(array_key_exists('required', $rules)) {

                        if($rules['required'] === true) {

                            if(!empty($_FILES[$field]['name'])) $this->fields[$field] = $_FILES[$field]['name'];
                            else $this->errors[] = "{$field} is required.";
                        }
                    }

                    /**
                     *  Faylın ölçü kontrolu.
                     *  ----------------------------------------
                     *  Qəbul olunan faylın həcmi təyinatdan
                     *  az olarsa doğru hesab olunacaq.
                     *  
                     *  size => limit(int, byte base 10)
                     */
                    if(array_key_exists('size', $rules)) {

                        if($_FILES[$field]['size'] <= $rules['size']) $this->fields[$field] = $_FILES[$field]['name'];
                        else $this->errors[] = "The {$field} volume can be a maximum of {$rules['size']} byte.";
                    }

                    /**
                     *  Faylın tip kontrolu.
                     *  ----------------------------------------
                     *  Qəbul olunan faylın tipi təyin olunan
                     *  massivdə mövcud olduğu təqdirdə fayl
                     *  təsdiqlənəcək.
                     *  
                     *  type => ['image/jpeg', 'image/png', 'image/gif']
                     */
                    if(array_key_exists('type', $rules)) {

                        if(in_array($_FILES[$field]['type'], $rules['type'])) $this->fields[$field] = $_FILES[$field]['name'];
                        else $this->errors[] = "File type of {$field} is not compatible.";
                    }

                    /**
                     *  Faylın saxlanılma ünvanı.
                     *  ----------------------------------------
                     *  Qəbul olunan faylı təyin olunan ünvana
                     *  yerləşdirəcək. Ünvan təyin olunmasa və
                     *  heç bir xəta yoxdursa `assets` qovluğuna
                     *  yerləşdirəcək.
                     *  
                     *  save => 'folder/'
                     */
                    if(array_key_exists('save', $rules)) {

                        # Heç bir xəta yoxdursa...
                        if(count($this->errors) === 0) {

                            # Fayl mövcud olduğu təqdirdə yüklənəcək.
                            if(!empty($_FILES[$field]['name'])) {

                                # Ünvan təyinatı...
                                $dir = ASSET . $rules['save'];

                                if(is_dir($dir)) {

                                    # Fayl mövcud olduğu təqdirdə yüklənəcək.
                                    move_uploaded_file($_FILES[$field]['tmp_name'], $dir . $_FILES[$field]['name']);
                                    $this->fields[$field] = $_FILES[$field]['name'];
                                }else {

                                    # Xəta! Qovluq yoxdur.
                                    $this->errors[] = "The folder named {$rules['save']} does not exist.";
                                }

                                
                            }else {

                                # Xəta!
                                $this->errors[] = "{$field} was not loaded.";
                            }
                        }
                    }else {
   
                        if(count($this->errors) === 0) {

                            if(!empty($_FILES[$field]['name'])) {
      
                                move_uploaded_file($_FILES[$field]['tmp_name'], ASSET . $_FILES[$field]['name']);
                                $this->fields[$field] = $_FILES[$field]['name'];
                            }else {

                                $this->errors[] = "{$field} was not loaded.";
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     *  Bütün məlumatların təsdiq dəyərini döndürəcək.
     * 
     *  @return bool
     */
    public function isValid() : bool {

        $valid = (count($this->errors) === 0) ? true : false;
        return $valid; 
    }

    /**
     *  Xətaları massiv şəklində qaytaracaq. 
     * 
     *  @return array
     */
    public function errors() : array {

        return $this->errors;
    }

    /**
     *  Massivdən təyin olunan dəyəri döndürəcək.
     * 
     *  @param string $field
     *  @return string
     */
    public function field(string $field) : string {
        
        return $this->fields[$field];
    }
}