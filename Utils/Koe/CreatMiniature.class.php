<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/9/10
 * Time: 21:10
 */
namespace Utils\Koe;
/**
 * ������CreatMiniature
 * ���ܣ����ɶ������͵�����ͼ
 * ����������$srcFile,$echoType
 * �����õ��Ĳ�����
 * $toFile,���ɵ��ļ� * $toW,���ɵĿ�  $toH,���ɵĸ�*
 * $bk1,������ɫ���� ��255Ϊ��� * $bk2,������ɫ���� * $bk3,������ɫ����
 *
 * ���ӣ�
 * include('thumb.php');
 * $cm=new CreatMiniature();
 * $cm->SetVar('1.jpg','file');
 * $cm->Distortion('dis_bei.jpg',150,200);

 * $cm->Prorate('pro_bei.jpg',150,200);//�����и�
 * $cm->Cut('cut_bei.jpg',150,200);
 * $cm->BackFill('fill_bei.jpg',150,200);
 */
class CreatMiniature {
    // ��������
    var $srcFile = '';	//ԭͼ
    var $echoType;		//���ͼƬ���ͣ�link--������Ϊ�ļ���file--����Ϊ�ļ�
    /**
     * @var resource
     */
    var $im = '';		//��ʱ����
    var $srcW = '';		//ԭͼ��
    var $srcH = '';		//ԭͼ��

    /**
     * ���ñ�������ʼ��
     * @param $srcFile
     * @param $echoType
     */
    function SetVar($srcFile, $echoType){
        $this->srcFile = $srcFile;
        $this->echoType = $echoType;

        $info = '';
        $data = GetImageSize($this->srcFile, $info);
        switch ($data[2]) {
            case 1:
                if (!function_exists('imagecreatefromgif')) {
                    exit();
                }
                $this->im = ImageCreateFromGIF($this->srcFile);
                break;
            case 2:
                if (!function_exists('imagecreatefromjpeg')) {
                    exit();
                }
                $this->im = ImageCreateFromJpeg($this->srcFile);
                break;
            case 3:
                $this->im = ImageCreateFromPNG($this->srcFile);
                break;
        }
        $this->srcW = ImageSX($this->im);
        $this->srcH = ImageSY($this->im);
    }

    /**
     * ����Ť������ͼ
     * @param $toFile
     * @param $toW
     * @param $toH
     * @return bool
     */
    function Distortion($toFile, $toW, $toH){
        $cImg = $this->CreatImage($this->im, $toW, $toH, 0, 0, 0, 0, $this->srcW, $this->srcH);
        $rst = $this->EchoImage($cImg, $toFile);
        ImageDestroy($cImg);
        return $rst;
    }

    /**
     * ���ɰ��������ŵ���ͼ
     * @param $toFile
     * @param $toW
     * @param $toH
     * @return bool
     */
    function Prorate($toFile, $toW, $toH){
        $toWH = $toW / $toH;
        $srcWH = $this->srcW / $this->srcH;
        if ($toWH<=$srcWH) {
            $ftoW = $toW;
            $ftoH = $ftoW * ($this->srcH / $this->srcW);
        } else {
            $ftoH = $toH;
            $ftoW = $ftoH * ($this->srcW / $this->srcH);
        }
        if ($this->srcW > $toW || $this->srcH > $toH) {
            $cImg = $this->CreatImage($this->im, $ftoW, $ftoH, 0, 0, 0, 0, $this->srcW, $this->srcH);
            $rst = $this->EchoImage($cImg, $toFile);
            ImageDestroy($cImg);
            return $rst;
        } else {
            $cImg = $this->CreatImage($this->im, $this->srcW, $this->srcH, 0, 0, 0, 0, $this->srcW, $this->srcH);
            $rst = $this->EchoImage($cImg, $toFile);
            ImageDestroy($cImg);
            return $rst;
        }
    }

    /**
     * ������С�ü������ͼ
     * @param $toFile
     * @param $toW
     * @param $toH
     * @return bool
     */
    function Cut($toFile, $toW, $toH){
        $toWH = $toW / $toH;
        $srcWH = $this->srcW / $this->srcH;
        if ($toWH<=$srcWH) {
            $ctoH = $toH;
            $ctoW = $ctoH * ($this->srcW / $this->srcH);
        } else {
            $ctoW = $toW;
            $ctoH = $ctoW * ($this->srcH / $this->srcW);
        }
        $allImg = $this->CreatImage($this->im, $ctoW, $ctoH, 0, 0, 0, 0, $this->srcW, $this->srcH);
        $cImg = $this->CreatImage($allImg, $toW, $toH, 0, 0, ($ctoW - $toW) / 2, ($ctoH - $toH) / 2, $toW, $toH);
        $rst = $this->EchoImage($cImg, $toFile);
        ImageDestroy($cImg);
        ImageDestroy($allImg);
        return $rst;
    }

    /**
     * ���ɱ���������ͼ,Ĭ���ð�ɫ���ʣ��ռ䣬����$isAlphaΪ��ʱ��͸��ɫ���
     * @param $toFile
     * @param $toW
     * @param $toH
     * @param bool|false $isAlpha
     * @param int $red
     * @param int $green
     * @param int $blue
     * @return bool
     */
    function BackFill($toFile, $toW, $toH,$isAlpha=false,$red=255, $green=255, $blue=255){
        $toWH = $toW / $toH;
        $srcWH = $this->srcW / $this->srcH;
        if ($toWH<=$srcWH) {
            $ftoW = $toW;
            $ftoH = $ftoW * ($this->srcH / $this->srcW);
        } else {
            $ftoH = $toH;
            $ftoW = $ftoH * ($this->srcW / $this->srcH);
        }
        if (function_exists('imagecreatetruecolor')) {
            @$cImg = ImageCreateTrueColor($toW, $toH);
            if (!$cImg) {
                $cImg = ImageCreate($toW, $toH);
            }
        } else {
            $cImg = ImageCreate($toW, $toH);
        }


        $fromTop = ($toH - $ftoH)/2;//�����м����
        $backcolor = imagecolorallocate($cImg,$red,$green, $blue); //���ı�����ɫ
        if ($isAlpha){//���͸��ɫ
            $backcolor=ImageColorTransparent($cImg,$backcolor);
            $fromTop = $toH - $ftoH;//�ӵײ����
        }

        ImageFilledRectangle($cImg, 0, 0, $toW, $toH, $backcolor);
        if ($this->srcW > $toW || $this->srcH > $toH) {
            $proImg = $this->CreatImage($this->im, $ftoW, $ftoH, 0, 0, 0, 0, $this->srcW, $this->srcH);
            if ($ftoW < $toW) {
                ImageCopy($cImg, $proImg, ($toW - $ftoW) / 2, 0, 0, 0, $ftoW, $ftoH);
            } else if ($ftoH < $toH) {
                ImageCopy($cImg, $proImg, 0, $fromTop, 0, 0, $ftoW, $ftoH);
            } else {
                ImageCopy($cImg, $proImg, 0, 0, 0, 0, $ftoW, $ftoH);
            }
        } else {
            ImageCopyMerge($cImg, $this->im, ($toW - $ftoW) / 2,$fromTop, 0, 0, $ftoW, $ftoH, 100);
        }
        $rst = $this->EchoImage($cImg, $toFile);
        ImageDestroy($cImg);
        return $rst;
    }

    /**
     * @param $img
     * @param $creatW
     * @param $creatH
     * @param $dstX
     * @param $dstY
     * @param $srcX
     * @param $srcY
     * @param $srcImgW
     * @param $srcImgH
     * @return resource
     */
    function CreatImage($img, $creatW, $creatH, $dstX, $dstY, $srcX, $srcY, $srcImgW, $srcImgH){
        if (function_exists('imagecreatetruecolor')) {
            @$creatImg = ImageCreateTrueColor($creatW, $creatH);
            if ($creatImg)
                ImageCopyResampled($creatImg, $img, $dstX, $dstY, $srcX, $srcY, $creatW, $creatH, $srcImgW, $srcImgH);
            else {
                $creatImg = ImageCreate($creatW, $creatH);
                ImageCopyResized($creatImg, $img, $dstX, $dstY, $srcX, $srcY, $creatW, $creatH, $srcImgW, $srcImgH);
            }
        } else {
            $creatImg = ImageCreate($creatW, $creatH);
            ImageCopyResized($creatImg, $img, $dstX, $dstY, $srcX, $srcY, $creatW, $creatH, $srcImgW, $srcImgH);
        }
        return $creatImg;
    }

    /**
     * ���ͼƬ��link---ֻ������������ļ���file--����Ϊ�ļ�
     * @param $img
     * @param $to_File
     * @return bool
     */
    function EchoImage($img, $to_File){
        switch ($this->echoType) {
            case 'link':return ImagePNG($img);break;
            case 'file':return ImagePNG($img, $to_File);break;
            //return ImageJpeg($img, $to_File);
        }
        return false;
    }
}