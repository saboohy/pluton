<?php

namespace Pluton\Http;

class Router {

    /**
     *  Rota təyinatlarını tutacaq.
     * 
     *  @var array
     */
    private static $routes = [];

    /**
     *  Rota təyinatının mövcudluq dəyərini tutacaq
     * 
     *  @var bool
     */
    private static $found = false;

    /**
     *  "Middleware" təyinatlarını tutacaq.
     * 
     *  @var array
     */
    private static $middlewares = [];

    /**
     *  İstifadə oluna biləcək HTTP metodları.
     * 
     *  @var array
     */
    private static $httpMethods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'];

    /**
     *  Rota təyinatında HTTP metod dəyərini tutacaq.
     * 
     *  @var string
     */
    private static $httpMethod;

    /**
     *  Rota təyinatında URI istiqamət(lər)ini tutacaq.
     * 
     *  @var string
     */
    private static $path;

    /**
     *  controller() metodu ilə alınan kontroller
     *  dəyər(lər)ini tutacaq.
     * 
     *  @var string
     */
    private static $controller;

    /**
     *  controller() metodu ilə alınan əsas lokasiya
     *  dəyər(lər)ini tutacaq.
     * 
     *  @var string
     */
    private static $location;

    /**
     *  Rota təyinatlarını alacaq.
     * 
     *  @param string $method
     *  @param string $path
     *  @param string $callback
     *  @return object
     */
    public static function set(string $method = 'GET', string $path, string $callback) : object {

        # Dəyəri böyük hərflərə çevirmə.
        self::$httpMethod = strtoupper($method);

        # Mövcud URI istiqaməti.
        self::$path = $path;

        /**
         *  Təyin olunan "callback" dəyərinin "." simvoluna görə
         *  massiv halına çevrilməsi.
         * 
         *  @var array
         */
        $callback = explode('.', $callback);

        # Təyin olunan kontroller.
        $callbackController = $callback[0] . 'Controller';

        # Təyin olunan metod.
        $callbackMethod = $callback[1];

        # Təyin olunan HTTP metodunun icazə kontrolu.
        if(in_array(self::$httpMethod, self::$httpMethods)) {

            self::$routes[self::$httpMethod][self::$path] = [$callbackController, $callbackMethod];
        } else { 

            die("<b>Xəta!</b> {". self::$httpMethod ."} adlı HTTP metodu mövcud deyil.");
        };
        return new static();
    }

    /**
     *  Kontroller və (varsa) URI lokasiyasını alacaq.
     * 
     *  @param string $controllerName
     *  @param string $location
     *  @return object
     */
    public static function controller(string $controllerName, string $location = null) : object {

        # Təyin olunan kontroller.
        self::$controller = $controllerName . 'Controller';

        # Əsas lokasiya təyinatı.
        !is_null($location) ? self::$location = $location : self::$location = null;
        return new static();
    }

    /**
     *  controller() metodunun ardınca rota təyinatlarını alacaq.
     * 
     *  @param array $routing
     *  @return object
     */
    public function group(array $routing) : object {

        /**
         *  Təyin olunan massiv dəyəri http metoduna və
         *  metod üzərindən reallaşacaq rota təyinatlarına
         *  görə dövrə salınması.
         * 
         *  METHOD => Routing
         */
        foreach($routing as $httpMethodFromRouting => $routesFromRouting) {

            /**
             *  Http metodu üzərindən reallaşacaq olan rota
             *  təyinatlarını URI istiqaməti və kontroller
             *  metoduna görə dövrə salınması.
             * 
             *  /path => method
             */
            foreach($routesFromRouting as $pathFromRouting => $methodFromRouting) {

                /**
                 *  controller() metodu ilə alınan əsas lokasiya
                 *  mövcud olduğu təqdirdə URI istiqamətinə birləşəcək,
                 *  əksi təqdirdə dəyərin özü istiqamət sayılacaq.
                 * 
                 *  /path = /location/path
                 */
                $pathFromRouting =  !is_null(self::$location) 
                                    ? self::$location . $pathFromRouting 
                                    : $pathFromRouting;

                # Mövcud URI istiqaməti.
                self::$path = $pathFromRouting;

                # Mövcud HTTP metod dəyəri.
                self::$httpMethod = $httpMethodFromRouting;

                # Təyin olunan HTTP metodunun icazə kontrolu.
                if(in_array(self::$httpMethod, self::$httpMethods)) {

                    self::$routes[self::$httpMethod][self::$path] = [self::$controller, $methodFromRouting];
                }else {

                    die("<b>Xəta!</b> {". self::$httpMethod ."} adlı HTTP metodu mövcud deyil.");
                }
            };
        };
        return new static();
    }

    /**
     *  "Middleware" təyinatlarını alacaq.
     * 
     *  @param string $key
     *  @return void
     */
    public function middleware(string $middlewareKey) : void {
        
        self::$middlewares[$middlewareKey][self::$path] = self::$httpMethod;
    }

    /**
     *  Rota və "middleware" təyinatlarını icra edəcək
     * 
     *  @return void
     */
    public static function execute() {
        
        /**
         *  "Middleware" icraatı
         * 
         *  Açarsöz təyin mövcud olacağı təqdirdə işə salınacaq
         */
        if(count(self::$middlewares) > 0) {

            /**
             *  Təyinatların açarsöz və açarsözə aid rota
             *  təyinatlarına görə dövrə salınnması
             */
            foreach(self::$middlewares as $midKey => $midRoutes) {

                /**
                 *  Rota təyinatlarının HTTP istiqamətinə(URI) və
                 *  HTTP metoduna görə dövrə salınması
                 */
                foreach($midRoutes as $midPath => $midHttpMethod) {
                    
                    /**
                     *  Təyin olunan HTTP metodu ilə mövcud HTTP metodu və
                     *  istiqamət(uri, path) mövcud HTTP istiqaməti ilə
                     *  uyğun gəldiyi təqdirdə işə salınacaq.
                     */
                    if(
                        preg_match(uriParamsConverter($midPath), HTTP_PATH) === 1 and
                        $midHttpMethod === HTTP_METHOD
                    ) {
                        
                        # İstifadəçinin middleware təyinatları
                        $userMidConfig = include CONFIG . 'middlewares.php';

                        # Açar sözün mövcudluq kontrolu
                        if(array_key_exists($key, $userMidConfig)) {

                            # Sinfin tam adı
                            $middlewareClass = '\App\Middlewares\\' . $userMidConfig[$midKey];

                            # Sinfi və işləməli olan metodu işə salma.
                            (new $middlewareClass)->handle();

                            # Nəticə var. Dövrün sonu!
                            break;
                        }else {

                            # Xəta. Açar söz yoxdur.
                            warning([
                                'title' => '"Middleware" xətası',
                                'hint' => $key . ' açar sözü mövcud deyil.'
                            ]);
                        }
                    };
                };
            };
        };

        /**
         *  Rota təyinatlarının icraatı.
         * 
         *  Təyinat olduğu təqdirdə işə salınacaq.
         */
        if(count(self::$routes) > 0) {

            foreach(self::$routes[HTTP_METHOD] as $path => $routingCallback) {

                # Təyin olunan kontroller sinfinin tam adı (+ namespace).
                $httpController = '\App\Controllers\\' . $routingCallback[0];

                # Təyin olunan kontroller metodu.
                $controllerMethod = $routingCallback[1];

                # Kontroller faylının tam istiqaməti.
                $controllerFile = ROOT . classToDir(ltrim($httpController, '\\'));

                # İstiqamətdə parametr olduğu təqdirdə əvəzləmə.
                $path = uriParamsConverter($path);
                
                # Təyin olunan istiqamətlə HTTP istiqamətinin uyğunluq kontrolu.
                if(preg_match($path, HTTP_PATH, $params) === 1) {

                    # Uyğunluğun doğruluq təsdiqi.
                    self::$found = true;

                    # Qayıdan massivin ilk dəyərini silmə.
                    array_shift($params);

                    # Faylın mövcudluq kontrolu.
                    if(file_exists($controllerFile)) {

                        # Sinfin mövcudluq kontrolu.
                        if(class_exists($httpController)) {

                            # Metodun mövcudluq kontrolu.
                            if(method_exists($httpController, $routingCallback[1])) {

                                /**
                                 *  Sinfin və metodun işləməsi və metoda
                                 *  arqumentlərin ötürülməsi.
                                 */
                                call_user_func_array([new $httpController, $routingCallback[1]], array_values($params));
                                
                                break;
                            }else {

                                # Xəta. Metod yoxdur.
                                warning([
                                    'title' => '"Controller" xətası',
                                    'hint' => getClassName($routingCallback[0]) . ' sinfində ' . $routingCallback[1] . ' metodu mövcud deyil.'
                                ]);
                            }
                        }else {

                            # Xəta! Sinif yoxdur.
                            warning([
                                'title' => '"Controller" xətası',
                                'hint' => $httpController . ' sinfi mövcud deyil.'
                            ]);
                        }
                    }else {

                        # Xəta! Fayl yoxdur.
                        warning([
                            'title' => '"Controller" xətası!',
                            'hint' => getClassName($routingCallback[0]) . '.php faylı mövcud deyil.'
                        ]);
                    }
                }
            };
        };

        /**
         *  Təyin olunmayan rota istəyi və ya
         *  mövcud olmayan təyinat varsa (default)
         *  404 xəta bildirişi etsin.
         */
        if(self::$found === false) {

            http_response_code(404);
            warning([
                'title' => '404 Error!',
                'hint' => 'Page not found.'
            ]);
        }

        exit;
    }
}