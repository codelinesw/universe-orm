<?php namespace Config;

class Route{

    private static $routes = array();

    public static function add($controller,$method = 'get',$contentType = null){
        
        $allowedAction = false;

        if(isset($controller)){
            
            $route = explode("/",$controller);            
            $route = array_filter($route);            
            $action = null;
            $parameter = null;

			if(sizeof($route) == 0){
				$controller = "index";
			}else{
                
			    $controller = array_shift($route);
                
			}

            if(sizeof($route) == 0){
                $action = "index";
            }else{
                $action = array_shift($route);                
                $parameter = implode("",$route);
            }            
            
            array_push(self::$routes,array(
                'controller' => $controller,
                'action' => $action,
                'parameter' => $parameter,
                'method' => $method,
                'contentType' => $contentType
            ));
        }
        
    }

    
    public static function load(){
        // Get current request method
        $route = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
        $route = explode("/",$route);
        $route = array_filter($route);
        $method = $_SERVER['REQUEST_METHOD'];        
        $controller = null;
        $currentController = null;
        $action = null;
        $parameter = null;

        if(empty($route[0])){
            $controller = "index";
        }else{
            $controller = array_shift($route);
        }                        

        if(sizeof($route) == 0){
            $action = "index";
        }else{
            $action = array_shift($route);
            $parameter = implode("",$route);
        }
        
        foreach(self::$routes as $item){
            $currentController = isset($item['controller']) ? $item['controller'] : "Index";
            $file = 'Controllers/'.$controller.'Controller.php';
            if(!file_exists($file)){
                header("HTTP/1.0 404 Not Found", true, 404);
                break;
            }else{            
                if($controller == $item['controller'] && (is_string($method) && strtolower($method) == strtolower($item['method'])) && ($item['action'] == $action)){
                    require_once($file);                    
                    $namespaceController = "Controllers\\".$item['controller'].'Controller';                    
                    $Object = new $namespaceController;
                    if(method_exists($Object,$action)){
                        if(isset($item['parameter']) && !empty($item['parameter']) && isset($parameter)){                                                                                  
                            if($item['contentType'] == 'application/json'){
                                header("HTTP/1.1 200 OK", true, 200);
                                header('Content-type: application/json');
                                header('Access-Control-Allow-Origin: *');                                
                                call_user_func(array($Object,$action),$parameter);                                                      
                                break;
                            }
                            if($item['contentType'] == 'application/pdf'){
                                header("HTTP/1.1 200 OK", true, 200);
                                header("Content-type:application/pdf");
                                call_user_func(array($Object,$action),$parameter);
                                break;
                            }
                            if(empty($item['contentType'])){                                
                                call_user_func(array($Object,$action),$parameter);
                                break;
                            }
                        }else{

                            if($item['contentType'] == 'application/json'){
                                header("HTTP/1.1 200 OK", true, 200);
                                header('Content-type: application/json');
                                header('Access-Control-Allow-Origin: *');                                                          
                                call_user_func(array($Object,$action));
                                break;
                            }

                            if($item['contentType'] == 'application/pdf'){
                                header("HTTP/1.1 200 OK", true, 200);
                                header("Content-type:application/pdf");
                                call_user_func(array($Object,$action));
                                break;
                            }
                            if(empty($item['contentType'])){
                                header("HTTP/1.1 200 OK", true, 200);                         
                                call_user_func(array($Object,$action));
                                break;
                            }
                            break;
                        }                    
                    }else{                        
                        header("HTTP/1.0 400 Bad Request", true, 400);
                        break;
                    }
                }else{
                    header("HTTP/1.0 405 Method Not Allowed", true, 405);                    
                }
            }
        }  
    
      }

}

?>