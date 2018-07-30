<?php
namespace app\wxxs\controller;
use app\common\model\User;
use think\Controller;

class Novel extends Controller
{
    private $o_String=NULL;

    public function __construct(){
        $this->o_String=new Str();
    }
    //显示首页
    public function novel_list()
    {

        $keyword = $_REQUEST['name'];
//        $keyword = '大主宰';
        $data = $this->getItem($keyword); //获取搜索内容
        $array = [];

        if($data['page']>1){
            for($p=1; $data['page']>=$p; $p++){
               $translate =  $this->getItem($keyword,$p);
               $list['translate']   =  array_merge($translate['translate'],$array);
               $array = $list['translate'];
            }
            $data = isset($list)?$list:[];
        }
        foreach ($data['translate'] as $k=>&$v){
            $v['sort'] = levenshtein(urldecode($keyword),$v['novel_name']);//相似度匹配

        }

        array_multisort(array_column($data['translate'],'sort'),SORT_ASC,$data['translate']);//根据指定索引的值排序
        echo  json_encode(array('code'=>1,'data'=>$data, 'count'=>count($data['translate'])));
        exit;
    }
    //请求CURL
    public function curl($url,$mode=''){
        if(!empty($mode)){
            header("Content-Type:text/html;charset=GBK");
        }else{
            header("Content-Type:text/html;charset=UTF-8");
        }
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//这个是重点。
        $content = curl_exec ($ch);
        if ($content == FALSE) {
            echo "error:" . curl_error ( $ch );
        }
        curl_close ( $ch );
        return $content;
    }
    //搜索小说
    public function getItem($word,$p=1){
//        本地测试连接:$url = "https://www.xxbiquge.com/search.php?keyword=".urlencode($word).'&page='.$p;
        $url = "https://www.xxbiquge.com/search.php?keyword=".$word.'&page='.$p;
        $content = $this->curl($url);
        /***
         * 小说目录链接($link_array)
         *$link_array[$j]['whole']:小说完整目录
         *$link_array[$j]['newest']:小说最新章节链接
         *$link_array[$k]['chapter_name']:小说最新章节名称
         * $link_array[$k]['cover']:小说封面
         * $link_array[$k]['brief']:小说简介
         * $link_array[$name_j]['author_name']:小说名称
         * $link_array[$name_j]['novel_name']:作者名称
         * $link_array[$type_j]['update_time']:小说更新时间
         * $t:$link_array[$type_j]['type']:小说类型
         */
        list($link_array) = array([]);
        preg_match_all("/href=\"(.*)\" /", $content, $link);
        $i = 0;
        $j = 0;
        foreach ($link[0] as $k=>$v) {
            preg_match('/http.*class/', $v, $l);
            $link_str = substr($l[0], 0, -7);
            if (!preg_match('/[^\x00-\x80]/', $link_str) && !empty($link_str)) {
                $i % 2 != 0? $link_array[$j]['newest'] = $link_str:$link_array[$j]['whole'] = $link_str;
                if ($i % 2 != 0)$j++;
                $i++;
            }
        }
        //读取小说最新章节名称
        preg_match_all('#<a[^>]*?class=\"result-game-item-info-tag-item\"[^>]*>(.*?)</a>#is',$content,$chapter_name);
        foreach ($chapter_name[1] as $k=>$v){
            $link_array[$k]['chapter_name'] = $v;
        }
        //读取小说封面图片
        preg_match_all('/<img.*?src="(.*?)".*?>/is', $content, $cover);
        foreach ($cover[1] as $k=>$v){
            $link_array[$k]['cover'] = $v;
        }
        //读取小说简介
        preg_match_all("/<p class=\"result-game-item-desc\".*?>.*?<\/p>/ism",$content,$brief);
        foreach ($brief[0] as $k=>$v){
            preg_match('/>.*</', $v, $b);
            $link_array[$k]['brief'] = substr($b[0],1,strlen($b[0])-2);
        }
        //小说名称和作者名称
        preg_match_all("/<span>.*?<\/span>/ism",$content,$name);
        $name_i = 0;
        $name_j = 0;
        foreach ($name[0] as $k=>$v){
            $n = substr($v,6,-7);
            $name_i % 2 != 0? $link_array[$name_j]['author_name'] = $n:$link_array[$name_j]['novel_name'] = $n;
            if ($name_i % 2 != 0)$name_j++;
            $name_i++;
        }
        //小说类型以及更新时间
        preg_match_all("/<span class=\"result-game-item-info-tag-title\".*?>.*?<\/span>/ism",$content,$type);
        $type_i = 0;
        $type_j = 0;
        foreach ($type[0] as $k=>$v){
            preg_match('/>.*</', $v, $t);
            $t =  substr($t[0],1,strlen($t[0])-2);
            $type_i % 2 != 0? $link_array[$type_j]['update_time'] = $t:$link_array[$type_j]['type'] = $t;
            if ($type_i % 2 != 0)$type_j++;
            $type_i++;
        }
        //小说页数
        preg_match_all("/<div class=\"search-result-page-main\".*?>.*?<\/div>/ism",$content,$page);
        $page = substr(preg_replace('/\D/s', '', $page[0][0]),-1);
        $data['translate'] = $link_array;//小说资料
        $data['page'] = empty($page)?$p:$page;
        return $data;
    }

    //截取小说内容
        public function intercept_content(){
        $link_m = 'http://www.xxbiquge.com';
        $url = urldecode($_REQUEST['url']);
//        $url = 'http://www.xxbiquge.com/0_142/8884200.html';
        $url = str_replace("http","https",$url);
        $content = $this->curl($url);
        preg_match("/<div class=\"bookname\".*?>.*?<\/div>/ism",$content,$chapter);
        preg_match("/<h1>(.*?)<\/h1>/",$chapter[0],$chapter_title);//标题
        $data['chapter_title'] = $chapter_title[1];
        preg_match("/<div class=\"bottem1\".*?>.*?<\/div>/ism",$content,$directory);
        preg_match_all("/<a .*?href=\"(.*?)\".*?>/is", $directory[0], $directory_link);//上下章节目录链接
        $data['prevlink'] = $link_m.$directory_link[1][0];
        $data['chapterlink'] =$link_m.$directory_link[1][1];
        $data['nextlink'] =$link_m.$directory_link[1][2];
        preg_match_all("/<div id=\"content\".*?>.*?<\/div>/ism",$content,$list);
//       echo "<pre>";
//        print_r($page[0][0]);
//        exit;
        $list=str_replace(array("&nbsp;","<br />"),array(" ","\n"),$list[0][0]);//替换HTML标签
        $data['content'] = strip_tags($list);
        echo json_encode(array('code'=>1,'data'=>$data));
        exit;   
    }
    //显示小说所有章节目录
    public function chapter_directory(){
        $url = urldecode($_REQUEST['url']);
    //        $url = 'http://www.xxbiquge.com/0_142/';
        $url = str_replace("http","https",$url);
        $content = $this->curl($url);
        //小说作者信息
        preg_match_all("/<div id=\"info\".*?>.*?<\/div>/ism",$content,$user);
        preg_match_all("/<p>(.*?)<\/p>/",$user[0][0],$u);//作者名称，时间，最新章节等
        preg_match_all("/<h1>(.*?)<\/h1>/",$user[0][0],$h);//小说标题
        $data['title'] = $h[1];
        //小说简介
        preg_match_all("/<div id=\"intro\".*?>.*?<\/div>/ism",$content,$intro);
        preg_match_all("/<p>(.*?)<\/p>/",$intro[0][0],$in);
        $data['intro'] =str_replace(array("&nbsp;","<br />"),array(" ","\n"),$in[1][0]); //简介
        preg_match_all("/href=\"(.*)\" /", $u[0][3], $link);//最新章节链接
        $link_m = 'http://www.xxbiquge.com';
        $data['link'] = $link_m.$link[1][0];//最新章节链接
        //小说章节目录
        preg_match_all("/<div id=\"list\".*?>.*?<\/div>/ism",$content,$chapter);
        preg_match_all("/<dd>(.*?)<\/dd>/",$chapter[0][0],$c);
        foreach ($c[0] as $k=>$v){
            preg_match("/<a href=\"[^\"]*\"[^>]*>(.*)<\/a>/",$v,$directory_name);
            $data['chapter_directory'][$k]['directory_name'] = $directory_name[1];
            preg_match("/<a .*?href=\"(.*?)\".*?>/is", $v, $directory_link);
            $data['chapter_directory'][$k]['directory_link'] = $link_m.$directory_link[1];
        }

        $user_array = ['name','status','update_time','chapter'];
        foreach ($u[0] as $k=>$v){
            $v = strip_tags($v);
            $info = str_replace(array("&nbsp;","<br />"),array(" ","\n"),$v);//替换HTML标签
            if($k==0){
               $info = substr($info,-12);//名称
            }elseif ($k == 1){
                $info = substr($info,13,-26);//状态
            }elseif ($k == 2){
                $info = substr($info,15);//更新时间
            }elseif ($k == 3){
                $info = substr($info,15);//最新章节名称
            }
            $data[$user_array[$k]]=$info;
        }
        echo json_encode(array('code'=>1,'data'=>$data));
        exit;
    }

    //小说热搜榜
    public  function hot_search()
    {
        $count = isset($_REQUEST['count'])?$_REQUEST['count']:0;
        $url = 'https://top.baidu.com/buzz/book.html';
        $content = $this->curl($url, 'GBK');
        $data = [];
        preg_match_all('#<a[^>]*?class=\"list-title\"[^>]*>(.*?)</a>#is',$content,$chapter_name);
        $sum = $count*17+17;
        for($i=$count*17; $i<=$sum; $i++){
                if(!isset($chapter_name[1][$i])) break;
                $data[] = mb_convert_encoding($chapter_name[1][$i], "UTF-8", "GBK");
        }
        print_r($data);
        exit;
        echo json_encode(array('code'=>1,'data'=>$data));
        exit;
    }

}

