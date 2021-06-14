<?php

class Console {

    /**
     *  Proqramın ana istiqamətini tutacaq.
     * 
     *  @var string
     */
    private $root;

    /**
     *  Proqramın lazım olan istiqamətlərini tutacaq.
     * 
     *  @var array
     */
    private $dir = [];

    /**
     *  Qəbul olunan əmrləri tutacaq.
     * 
     *  @var array
     */
    private $commands = [];

    /**
     *  app.php (config) faylından qayıdan tənzim
     *  məlumatlarını tutacaq.
     * 
     *  @var array
     */
    private $app = [];

    /**
     *  İstifadə oluna biləcək əmrlər.
     * 
     *  @var array
     */
    private $commandsList = ['-controller', '-middleware', '-model', '--version', '--help'];

    /**
     *  1. Konsoldan gələn əmrləri alacaq
     *  2. Ana istiqamət təyin olunacaq
     *  3. Proqram istiqamətləri təyin olunacaq
     *  4. app tənzimləri təyin olunacq
     * 
     *  @param array $commands
     *  @return void
     */
    public function __construct(array $commands) {

        $this->commands = $commands;
        $this->root = realpath('.') . '/';
        $this->dir = [
            'config'        => $this->root . 'config/',
            'controller'    => $this->root . 'http/Controllers/',
            'middleware'    => $this->root . 'http/Middlewares/',
            'model'         => $this->root . 'visualize/Models/',
            'view'          => $this->root . 'visualize/Views/'
        ];
        $this->app = include $this->dir['config'] . 'app.php';
    }

    /**
     *  Gələn əmrlərin ilk (istəyi müəyyən edən) 
     *  dəyərini qaytaracaq.
     */
    private function request() : string {

        return $this->commands[0];
    }

    /**
     *  Controller fayl/sinif yaradacaq
     *  
     *  @param array $getCommands
     *  @return void
     */
    private function makeController(array $getCommands) : void {

        # Sinif/fayl adı yoxdursa...
        if(count($getCommands) === 1) {echo "Xəta! Yaranacaq sinfin və ya faylın adı təyin olunmayıb.\n";}
        
        # Əlavə əmr varsa...
        else if(count($getCommands) > 2) {echo "Xəta! Uyğun olmayan əmr(lər) var.\n";}

        # Xəta yoxdursa...
        else {
            
            $nameSpace  = 'App\Controllers;';
            $className  = $getCommands[1] . 'Controller';
            $fileName   = $getCommands[1] . 'Controller.php';
            $controllerFiles = scandir($this->dir['controller']);
            $controllerFiles = array_diff($controllerFiles, ['.', '..']);
            
            # Faylın mövcudluq kontrolu...
            if(in_array($fileName, $controllerFiles)) {echo "{$fileName} adlı fayl mövcuddur.\n";}

            # Fayl yarana bilər...
            else {

                $makeFile = fopen($this->dir['controller'] . $fileName, 'w');
                $fileContent = "<?php\n\nnamespace {$nameSpace}\n\n";
                $fileContent .= "use \Pluton\Http\Controller;\n\n";
                $fileContent .= "class {$className} extends Controller {\n\n}";
                fwrite($makeFile, $fileContent);
                fclose($makeFile);
                echo "{$fileName} faylı(controller) uğurla yarandı.\n";
            }
        }
    }

    /**
     *  Middleware fayl/sinif yaradacaq.
     * 
     *  @param array $getCommands
     *  @return void
     */
    private function makeMiddleware(array $getCommands) : void {

        # Sinif/fayl adı yoxdursa...
        if(count($getCommands) === 1) {echo "Xəta! Yaranacaq sinfin və ya faylın adı təyin olunmayıb.\n";}
        
        # Əlavə əmr varsa...
        else if(count($getCommands) > 2) {echo "Xəta! Uyğun olmayan əmr(lər) var.\n";}

        # Xəta yoxdursa...
        else {
            
            $nameSpace  = 'App\Middlewares;';
            $className  = $getCommands[1];
            $fileName   = $getCommands[1] . '.php';
            $middlewareFiles = scandir($this->dir['middleware']);
            $middlewareFiles = array_diff($middlewareFiles, ['.', '..']);
            
            # Faylın mövcudluq kontrolu...
            if(in_array($fileName, $middlewareFiles)) {echo "{$fileName} adlı fayl mövcuddur.\n";}

            # Fayl yarana bilər...
            else {

                $makeFile = fopen($this->dir['middleware'] . $fileName, 'w');
                $fileContent = "<?php\n\nnamespace {$nameSpace}\n\n";
                $fileContent .= "class {$className} {\n\n";
                $fileContent .= "\tpublic function handle() {\n\n\t\t// code\n\t}\n}";
                fwrite($makeFile, $fileContent);
                fclose($makeFile);
                echo "{$fileName} faylı(middleware) uğurla yarandı.\n";
            }
        }
    }

    /**
     *  Model fayl/sinif yaradacaq.
     * 
     *  @param array $getCommands
     *  @return void
     */
    private function makeModel(array $getCommands) : void {

        # Sinif/fayl adı yoxdursa...
        if(count($getCommands) === 1) {echo "Xəta! Yaranacaq sinfin və ya faylın adı təyin olunmayıb.\n";}
        
        # Əlavə əmr varsa...
        else if(count($getCommands) > 2) {echo "Xəta! Uyğun olmayan əmr(lər) var.\n";}

        # Xəta yoxdursa...
        else {
            
            $nameSpace  = 'App\Models;';
            $className  = $getCommands[1];
            $fileName   = $getCommands[1] . '.php';
            $modelFiles = scandir($this->dir['model']);
            $modelFiles = array_diff($modelFiles, ['.', '..']);
            
            # Faylın mövcudluq kontrolu...
            if(in_array($fileName, $modelFiles)) {echo "{$fileName} adlı fayl mövcuddur.\n";}

            # Fayl yarana bilər...
            else {

                $makeFile = fopen($this->dir['model'] . $fileName, 'w');
                $fileContent = "<?php\n\nnamespace {$nameSpace}\n\n";
                $fileContent .= "use \Pluton\Data\Model;\n\n";
                $fileContent .= "class {$className} extends Model {\n\n";
                $fileContent .= "\tprotected \$primaryKey = '';\n\n";
                $fileContent .= "\tprotected \$table = '';\n\n";
                $fileContent .= "\tprotected \$fields = [];\n}";
                fwrite($makeFile, $fileContent);
                fclose($makeFile);
                echo "{$fileName} faylı(model) uğurla yarandı.\n";
            }
        }
    }

    /**
     *  Plutonun mövcud versiyasını yazdıracaq.
     *  
     *  @param array $getCommands
     *  @return void
     */
    private function showVersion(array $getCommands) : void {

        # Əlavə əmr varsa...
        if(count($getCommands) > 1) {echo "Xəta! Uyğun olmayan əmr(lər) var.\n";}

        # Xəta yoxdur!
        else {

            echo "Mövcud Pluton versiyanız - {$this->app['version']}\nXoş kodlamalar...\n";
        }
    }

    /**
     *  Plutonda istifadə oluna biləcək
     *  əmrləri yazdıracaq.
     * 
     *  @param array $getCommands
     *  @return void
     */
    private function helpMe(array $getCommands) : void {

        # Əlavə əmr varsa...
        if(count($getCommands) > 1) {echo "Xəta! Uyğun olmayan əmr(lər) var.\n";}

        # Xəta yoxdur!
        else {

            $message = "\rİstifadə oluna biləcək əmrlər.\n\n";
            $message .= "[--version]    - 'Mövcud Pluton versiyasını yazdırır.\n";
            $message .= "[--help]       - 'Plutonun istifadə oluna biləcək əmrlərini yazdırır.\n\n";
            $message .= "[-controller]  - 'Controller' faylı/sinfi yaradır.\n";
            $message .= "[-middleware]  - 'Middleware' faylı/sinfi yaradır.\n";
            $message .= "[-model]       - 'Model' faylı/sinfi yaradır.\n\n";
            $message .= "Pluton istifadə etdiyin üçün təşəkkür... Xoş kodlamalar...\n";
            echo $message;
        }
    }

    /**
     *  Gələn əmrləri icra edəcək.
     * 
     *  @return void
     */
    public function __destruct() {

        # Gələn əmr istifadə oluna bilərsə...
        if(in_array($this->request(), $this->commandsList)) {

            # Controller tələbi...
            if($this->request() === '-controller') {$this->makeController($this->commands);}

            # Middleware tələbi...
            else if($this->request() === '-middleware') {$this->makeMiddleware($this->commands);}

            # Model tələbi...
            else if($this->request() === '-model') {$this->makeModel($this->commands);}

            # Versiya tələbi...
            else if($this->request() === '--version') {$this->showVersion($this->commands);}

            # Kömək tələbi...
            else if($this->request() === '--help') {$this->helpMe($this->commands);}
        }else {

            # Əmr istifadə oluna bilmir!
            echo "Xəta! {$this->command()} əmri mövcud deyil.\nİstifadə oluna biləcək əmrlər haqqında məlumat üçün '--help' əmrini istifadə edin.\n";
        }
    }
}