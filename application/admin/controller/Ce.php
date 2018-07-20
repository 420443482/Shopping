<?php
namespace app\admin\controller;
use app\common\model\User;
use think\Controller;

class Ce extends Controller
{
    private $o_String=NULL;
    public function __construct(){
        $this->o_String=new Str();
    }
    public function getItem($word){
        header("Content-Type:text/html;charset=UTF-8");
        $url = "https://www.xxbiquge.com/search.php?keyword=".urlencode($word);
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//这个是重点。
        $content = curl_exec ($ch);
//        print_r($content);
//        exit;
        if ($content == FALSE) {
            echo "error:" . curl_error ( $ch );
        }
        curl_close ( $ch );

        preg_match_all("/href=\"(.*)\" /", $content, $a);
        preg_match_all("/src=\"(.*)\" /", $content, $s);

        print_r($s);
        exit;
        $this->o_String->string=$content;
        $s_begin='<div class="result-list"><div class="result-item result-game-item"><div class="result-game-item-pic">';
        $s_end='</div></div></div>';
        $content=$this->o_String->getPart($s_begin,$s_end);
        print_r($content);
        exit;
        return $content;
    }
    public function __destruct(){
        unset($this->o_String);
    }
    //显示首页
    public function index()
    {
        $q = '斗破苍穹';
        $str = $this->getItem($q); //获取搜索内容

        preg_match("/href=\"(.*)\" /", $str, $a);


        //print_r($m[4]); 链接文字

    }



}

