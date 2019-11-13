<?php
class CodeController{
    private $code='';
    private $length=4;
    private $seeds='0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    public function checkCode(String $code):bool{
        if(!empty($code)){
            if(empty($_SESSION['code'])){
                echo 'the verification code is expired,please refresh the code';
                return false;
            }
            if(!empty($_SESSION['codeTime'])){
                $codeTime=(int) $_SESSION['codeTime'];
                $currentTime=time();
                if($currentTime-$codeTime>180){
                    unset($_SESSION['code']);
                    unset($_SESSION['codeTime']);
                    echo 'the verification is expired,please refresh the code';
                    return false;
                }
            }
            if(strtoupper($code)!=$_SESSION['code']){
                echo 'the verification is not match';
                return false;
            }
            unset($_SESSION['code']);
            unset($_SESSION['codeTime']);
            echo 'verification code passed';
            return true;
        }
        return false;
    }
    public function generateCode(int $width=300,int $height=80){
        if($width){
            $width=300;
        }
        if($height<=0){
            $height=80;
        }
        $this->code='';
        for($i=0,$m=strlen($this->seeds)-1;$i<$this->length;$i++){
            $this->code.=$this->seeds[rand(0,$m)];
        }
        $this->saveCode();
        $this->exportImage($width,$height);
    }
    private function saveCode(){
        session_start();
        $_SESSION['code']=$this->code;
        $_SESSION['codeTime']=time();
        session_commit();
    }
    private function exportImage(int $width,int $height){
        $image=imagecreate($width,$height);
        $backColor=imagecolorallocate($image,rand(220,250),rand(220,250),rand(220,250));
        imagefill($image,0,0,$backColor);
        $maskedColor=imagecolorallocate($image,rand(180,220),rand(180,220),rand(220,250));
        for($x=10;$x<=$width;$x+=20){
            for($y=10;$y<=$height;$y+=20){
                imagefilledellipse($image,$x,$y,rand(5,20),rand(5,20),$maskedColor);
            }
        }
        $codeColor=imagecolorallocate($image,rand(150,200),rand(150,200),rand(150,200));
        putenv('GDFONTPATH='.realpath('.'));
        $font='css/font.ttf';
        $left=$width/($this->length+1);
        $top=$height/2;
        for($i=0;$i<$this->length;$i++){
            imagettftext($image,$top,rand(-30,30),rand(0,$left-10)+$left*($i+0.5),rand(0,$top)+$top,$codeColor,$font,$this->code[$i]);
        }
        header('Content-Type:image/png; charset=binary');
        imagepng($image);
        imagedestroy($image);
    }
}