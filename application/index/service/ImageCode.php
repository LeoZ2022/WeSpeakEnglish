<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/10/10
 * Time: 11:10
 */

namespace app\index\service;

class ImageCode
{
    private $Jiashu = 0; //加数或者减数
    private $JianShu = 0; //被加数或者被减数
    private $YunSuan = ''; //运算符
    private $DeShu = 0; //得数
    private $String = ''; //字符串样式
    private $Img; //图片对象
    private $Width = 100; //图片宽度
    private $Height = 50; //图片高度
    private $Ttf = 'public/static/1.ttf';//字体文件
    private $Session = 'verify_code'; //Session变量

    private function JiaShu()
    {
        header('Content-type:image/png');
        $this->Jiashu = rand(1, 5);
        $this->JianShu = rand(1, 5);
        $this->YunSuan = $this->Jiashu > $this->JianShu ? '-' : '+';
        $this->DeShu = $this->Jiashu > $this->JianShu ? $this->Jiashu - $this->JianShu : $this->Jiashu + $this->JianShu;
    }

    public function get_gs($W = 100, $H = 50)
    {
        $this->JiaShu(1);
        $this->String = $this->Jiashu . $this->YunSuan . $this->JianShu . '= ? ';
        $this->Width = $W;
        $this->Height = $H;

        $this->String = $this->Jiashu . $this->YunSuan . $this->JianShu . '= ? ';
        $this->Width = $W;
        $this->Height = $H;
        $this->Img = imagecreate($this->Width, $this->Height);
        $background_color = imagecolorallocate($this->Img, 255, 255, 255);
        imagecolortransparent($this->Img, $background_color);
        $this->Ttf = realpath($this->Ttf);
        imagettftext($this->Img, 16, 0, 1, 20, imagecolorallocate($this->Img, 0, 0, 0), $this->Ttf, $this->String);

        $ret = ['shu1' => $this->Jiashu, 'shu2' => $this->JianShu, 'YunSuan' => $this->YunSuan, 'DeShu' => $this->DeShu ];
        $img = imagepng($this->Img);
//        $ret['base64'] = $this->Img;
        return $ret;
    }

    public function Show($W = 100, $H = 50, $Code = '', $T = '')
    {
        $this->JiaShu();
        $this->String = $this->Jiashu . $this->YunSuan . $this->JianShu . '= ? ';
        $this->Width = $W;
        $this->Height = $H;
        $T ? $this->Ttf = $T : '';
        $Code ? $this->Session = $Code : '';
        session($this->Session,$this->DeShu);
        cache($this->Session,$this->DeShu);
        $this->Images();
    }

    private function Images()
    {
        $this->Img = imagecreate($this->Width, $this->Height);
        $background_color = imagecolorallocate($this->Img, 255, 255, 255);
        imagecolortransparent($this->Img, $background_color);
        $this->Ttf = realpath($this->Ttf);
        imagettftext($this->Img, 16, 0, 1, 20, imagecolorallocate($this->Img, 0, 0, 0), $this->Ttf, $this->String);
        $this->EchoImages();
    }

    private function EchoImages()
    {
        imagepng($this->Img);
        imagedestroy($this->Img);
    }

    public function check_code($code, $session = 'verify_code')
    {
        if ($code == session($session)) {
            session($session, null);
            return true;
        } else {
            return false;
        }
    }

}
