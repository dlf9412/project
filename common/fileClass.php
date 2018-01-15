<?php   
Class FileSystem{

    function __construct($filename="AccessToken.txt"){
        $this->filename=$filename;
        $this->file_is_exist=false;
    }

    // function createFile(){

    // }

    // 写入文件
    function writeFile($content='11'){
        // ($TxtRes=fopen ($TxtFileName,"w+")
        $fs=fopen($this->filename,"w+");
        chmod($this->filename,0777);
        if(!fwrite($fs,$content)){
            echo ("尝试向文件".$this->filename."写入".$content."文件失败！");
            fclose($fs);
            exit();
        }
        fclose($fs);//关闭指针
        $this->file_is_exist=true;
    }
    // 读取文件
    function readFile(){
        if(is_file($this->filename)){
            $content=file_get_contents($this->filename);
            return $content;
        }else{
            $this->writeFile();
        }
    }
}

// $file_obj=new FileSystem();
// $file_obj->readFile();
?>