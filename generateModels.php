<?php
//echo "you are trying to create a file and not a folder, for this, if you want to generate a model use the generateModel command";
if(isset($argc)){
    if(sizeof($argv) > 1){
        if($argv[1] == 'createFolder'){
            $path = getcwd()."/".$argv[2];          
            if(!file_exists($path)){
                if(mkdir($argv[2],0777,true)){
                    echo "\e[32mGreen The folder has been created successfully \n \e[32mGreen ";
                }else{
                    echo "\033[01;31mThe Models folder already exists in your root directory \n \033[0m";
                }                
            }else{
              echo "\033[01;31mThe Models folder already exists in your root directory \n \033[0m";  
            }
        }

        if($argv[1] == 'generateModel'){
            $path = getcwd()."/Models/".$argv[2].".php";
            if(!file_exists($path)){
                $class = $argv[2];
                $file = fopen($path,"w");
                fwrite($file,"<?php namespace Models;");
                fwrite($file,"\n\n");
                fwrite($file,"require_once('Orm/Orm.php'); \n\n");
                fwrite($file,"class ".$class."{");
                fwrite($file,"\n\n");
                fwrite($file,"  protected static \$table = '".$argv[2]."';");
                fwrite($file,"\n");
                fwrite($file,"  private \$data = [];");
                fwrite($file,"\n");
                fwrite($file,"  private \$Orm;");
                fwrite($file,"\n\n");
                fwrite($file,"  public function __construct(){");
                fwrite($file,"\n");
                fwrite($file,"      \$this->data = null;");
                fwrite($file,"\n");
                fwrite($file,"      \$this->Orm = new ORM();");
                fwrite($file,"\n");
                fwrite($file,"  }");
                fwrite($file,"\n\n");
                fwrite($file,"}");
                fwrite($file,"\n\n");
                fwrite($file,"?>");
                fclose($file);
                echo "\e[32mGreen The Model Class  has been created successfully \n \e[32mGreen ";              
            }else{
              echo "\033[01;31mThe Model Class already exists in your Models directory \n \033[0m";  
            }
        }
    }
}

?>