<?php
/**
 * Created by PhpStorm.
 * User: Lin
 * Date: 2015/9/10
 * Time: 21:07
 */
namespace Utils\Koe;

/**
 * ��ʷ��¼������
 * ������߹���һ�����顣����:
 * array(
 * 	'history_num'=>20,	//���нڵ��ܹ�����
 * 	'first'=>0,			//��ʼλ��,��0��ʼ����������ֵ
 * 	'last'=>0,			//�յ�λ�ã���0��ʼ��
 * 	'back'=>0,			//��firstλ�õ����˶��ٲ�����ֵ��
 * 	'history'=>array(	//���飬��Ų������С�
 * 		array('path'=>'D:/'),
 * 		array('path'=>'D:/www/'),
 * 		array('path'=>'E:/'),
 * 		array('path'=>'/home/')
 * 		����
 * 	)
 * )
 */
class History{
    var $history_num;
    var $first;
    var $last;
    var $back;
    var $history=array();

    function __construct($array=array(),$num=20){
        if (!$array) {//����Ϊ��.����һ��ѭ�����С�
            $history=array();
            for ($i=0; $i < $num; $i++) {
                array_push($history,array('path'=>''));
            }
            $array=array(
                'history_num'=>$num,
                'first'=>0,//��ʼλ��
                'last'=>0,//�յ�λ��
                'back'=>0,
                'history'=>$history
            );
        }
        $this->history_num=$array['history_num'];
        $this->first=$array['first'];
        $this->last=$array['last'];
        $this->back=$array['back'];
        $this->history=$array['history'];
    }

    /**
     * @param $i
     * @param int $n
     * @return mixed
     */
    function nextNum($i,$n=1){//��·��nһ��ֵ����ʱ�ӻ�·���ơ�
        return ($i+$n)<$this->history_num ? ($i+$n):($i+$n-$this->history_num);
    }

    /**
     * @param $i
     * @param int $n
     * @return mixed
     */
    function prevNum($i,$n=1){//��·��һ��ֵi������N��λ�á�
        return ($i-$n)>=0 ? ($i-$n) : ($i-$n+$this->history_num);
    }

    /**
     * @param $i
     * @param $j
     * @return mixed
     */
    function minus($i,$j){//˳ʱ������ֻ��,i-j
        return ($i > $j) ? ($i - $j):($i-$j+$this->history_num);
    }

    /**
     * @return array
     */
    function getHistory(){//��������,���ڱ���������л�������
        return array(
            'history_num'=> $this->history_num,
            'first'		 => $this->first,
            'last'		 => $this->last,
            'back'		 => $this->back,
            'history'	 => $this->history
        );
    }

    /**
     * @param $path
     * @return void
     */
    function add($path){
        if ($path==$this->history[$this->first]['path']) {//�������ͬ���򲻼�¼
            return;
        }
        if ($this->back!=0) {//�к��˲�����¼������£����в��롣
            $this->goedit($path);
            return;
        }
        if ($this->history[0]['path']=='') {//�չ��죬���ü�һ.��λ��ǰ��
            $this->history[$this->first]['path']=$path;
            return;
        }else{
            $this->first=$this->nextNum($this->first);//��λǰ��
            $this->history[$this->first]['path']=$path;
        }
        if ($this->first==$this->last) {//��ʼλ������ֹλ������
            $this->last=$this->nextNum($this->last);//ĩβλ��ǰ�ơ�
        }
    }

    /**
     * @return mixed
     */
    function goback(){//���ش�first����N���ĵ�ַ��
        $this->back+=1;
        //�����˲���Ϊ��㵽�յ�֮��(˳ʱ��֮��)
        $mins=$this->minus($this->first,$this->last);
        if ($this->back >= $mins) {//�˵�����
            $this->back=$mins;
        }

        $pos=$this->prevNum($this->first,$this->back);
        return $this->history[$pos]['path'];
    }

    /**
     * @return mixed
     */
    function gonext(){//��first����N���ĵط�ǰ��һ����
        $this->back-=1;
        if ($this->back<0) {//�˵�����
            $this->back=0;
        }
        return $this->history[$this->prevNum($this->first,$this->back)]['path'];
    }

    /**
     * @param $path
     */
    function goedit($path){//���˵�ĳ���㣬û��ǰ�������޸ġ���firsֵΪ����ֵ��
        $pos=$this->minus($this->first,$this->back);
        $pos=$this->nextNum($pos);//��һ��
        $this->history[$pos]['path']=$path;
        $this->first=$pos;
        $this->back=0;
    }

    /**
     * �Ƿ���Ժ���
     * @return int
     */
    function isback(){
        if ($this->back==0 && $this->first==0 && $this->last==0) {
            return 0;
        }
        if ($this->back < $this->minus($this->first,$this->last)) {
            return 1;
        }
        return 0;
    }

    /**
     * �Ƿ����ǰ��
     * @return int
     */
    function isnext(){
        if ($this->back>0) {
            return 1;
        }
        return 0;
    }

    /**
     * ȡ�����¼�¼
     * @return mixed
     */
    function getFirst(){
        return $this->history[$this->first]['path'];
    }

}
//include 'common.function.php';
//$hi=new history(array(),6);//��������飬���ʼ�����鹹�졣
//for ($i=0; $i <8; $i++) {
//	$hi->add('s'.$i);
//}
//pr($hi->goback());
//pr($hi->gonext());
//$hi->add('asdfasdf2');
//pr($hi->getHistory());


//$ss=new history($hi->getHistory());//ֱ�������鹹�졣
//$ss->add('asdfasdf');
//$ss->goback();
//pr($ss->getHistory());