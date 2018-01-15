<?php
// 代码封装成一个函数
// define("TOKEN","kikiCookie")
$echostr=$_GET["echostr"];
$wx=new WeixinApi();
$wx->valid();

// 封装成一个类
class WeixinApi{
    public function valid(){
        if($this->checkWechat()){
            echo $echostr;
        }else{
            echo "error";
        }
    }

    // 验证微信加密签名signature
    private function checkWechat(){
    // 接收微信服务器get请求过来的四个参数   
        $signature=$_GET["signature"];
        $timestamp=$_GET["timestamp"];
        $nonce=$_GET["nonce"];
        $token="kikiCookie";
        // 将toekn，timestamp，nonce三个参数进行字典排序
        // $tmpArr=array(TOKEN,$nonce,$timestamp)
        $tmpArr=array($nonce,$token,$timestamp);
        sort($tmpArr,SORT_STRING);
        // 将排序好的三个参数拼成字符串
        $str=implode($tmpArr);
        // 将拼接好的字符串进行sha1加密
        $sign=sha1($str);
        // 开发者过得加密后的字符串可与signature对比
        if($sign==$signature){
            return true;
        }else{
            return false;
        }
    
        
    }
}
// 验证消息
// checkWechat();


// 自动回复消息
//获取一下微信服务器发送过来的xml原始数据
//  $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
// $postStr=$_POST;
$postStr = file_get_contents('php://input');
 //判断$postStr是否有值
 if (empty($postStr) == false) {
     //表示有post数据传输过来
     //xml解析：xml解析成一个对象
     libxml_disable_entity_loader(true);
     $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
     //获取ToUserName :�     �众号
     $ToUserName = $postObj->ToUserName;
     //获取FromUserName：客户微信号
     $FromUserName = $postObj->FromUserName;
     //获取media_id
     $media_id = $postObj->MediaId;
     //获取消息的类型
     $MsgType = $postObj->MsgType;
     //获取音乐图片的信息
     $ThumbId = $postObj->ThumbMediaId;
     switch ($MsgType) {
            case 'text':
            //代表传过来的是文本
           echoText($postObj);
            break;
            case 'image':
            echoImage($postObj);
            //echo '这是一个图片回复';
            break;
            case 'event':
            //代表前端推送了一个事件
            //echo '这是一个图片回复';
            break;
            case 'voice':
            echoVoice($ToUserName, $FromUserName, $media_id);
            //echo '这是一个语音回复';
            break;
            case '语音':
            //echo '这是一个语音回复';
            break;
            case 'shortvideo':
            echoVideo($postObj);
            //echo '这是一个视频回复';
            break;
            case 'music':
            echoMusic($ToUserName, $FromUserName, $ThumbId);
            //echo '这是一个音乐回复';
            break;
            case 'news':
           echoNews($ToUserName, $FromUserName);
            //echo '这是一个图文回复';
            break;
            default:
            //echo '输入的格式不正确！';
        }

     //  switch ($MsgType) {
    //     case 'text':
    //         //代表传过来是一个文本，执行相应文本操作函数

    //         handleText($postObj);
    //         break;

    //     default:
    //         echo '';
    //         break;
    // }
 }


//回复图文消息
 function echoNews($ToUserName, $FromUserName)
 {
     $time = time();

     $echostr = <<<EOD
<xml>
<ToUserName><![CDATA[{$FromUserName}]]></ToUserName>
<FromUserName><![CDATA[{$ToUserName}]]></FromUserName>
<CreateTime>{$time}</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<ArticleCount>3</ArticleCount>
<Articles>
<item>
<Title><![CDATA[科技大会]]></Title> 
<Description><![CDATA[科技大会在今召开]]></Description>
<PicUrl><![CDATA[https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1488348197399&di=0e8aa9264db534efbb5bd5d9da984cad&imgtype=0&src=http%3A%2F%2Fpic39.nipic.com%2F20140314%2F2531170_205123795000_2.jpg]]></PicUrl>
<Url><![CDATA[http://tech.huanqiu.com/photo/2017-02/2862855.html]]></Url>
</item>
<item>
<Title><![CDATA[科技大会]]></Title> 
<Description><![CDATA[科技大会在今召开]]></Description>
<PicUrl><![CDATA[https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1488348197399&di=0e8aa9264db534efbb5bd5d9da984cad&imgtype=0&src=http%3A%2F%2Fpic39.nipic.com%2F20140314%2F2531170_205123795000_2.jpg]]></PicUrl>
<Url><![CDATA[http://tech.huanqiu.com/photo/2017-02/2862855.html]]></Url>
</item>
<item>
<Title><![CDATA[生产大会]]></Title> 
<Description><![CDATA[科技大会在今召开]]></Description>
<PicUrl><![CDATA[https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1488348197399&di=0e8aa9264db534efbb5bd5d9da984cad&imgtype=0&src=http%3A%2F%2Fpic39.nipic.com%2F20140314%2F2531170_205123795000_2.jpg]]></PicUrl>
<Url><![CDATA[http://tech.huanqiu.com/photo/2017-02/2862855.html]]></Url>
</item>
</Articles>
</xml>	
EOD;

     echo $echostr;
 }

 //回复视频消息
function echoVideo($postObj)
{
    $time = time();
    $title = '这是回复的视频';
    $echostr = <<<EOD
<xml>
<ToUserName><![CDATA[{$postObj->FromUserName}]]></ToUserName>
<FromUserName><![CDATA[{$postObj->ToUserName}]]></FromUserName>
<CreateTime>{$time}</CreateTime>
<MsgType><![CDATA[shortvideo]]></MsgType>
<MediaId><![CDATA[{$postObj->MediaId}]]></MediaId>
<ThumbMediaId><![CDATA[{$postObj->ThumbMediaId}]]></ThumbMediaId>
<MsgId>{$postObj->MsgId}</MsgId>
</xml>
EOD;

    echo $echostr;
}
function echoImage($postObj)
{
    $time = time();
    $echostr = <<<EOD
<xml>
<ToUserName><![CDATA[{$postObj->FromUserName}]]></ToUserName>
<FromUserName><![CDATA[{$postObj->ToUserName}]]></FromUserName>
<CreateTime>{$time}</CreateTime>
<MsgType><![CDATA[image]]></MsgType>
<Image><MediaId><![CDATA[{$postObj->MediaId}]]></MediaId></Image>
</xml>
EOD;

    echo $echostr;
}

function echoVoice($ToUserName, $FromUserName, $media_id)
{
    $time = time();
    $echostr = <<<EEE
<xml><ToUserName><![CDATA[{$FromUserName}]]></ToUserName><FromUserName><![CDATA[{$ToUserName}]]></FromUserName><CreateTime>{$time}</CreateTime><MsgType><![CDATA[voice]]></MsgType><Voice><MediaId><![CDATA[{$media_id}]]></MediaId></Voice></xml>
EEE;
    echo $echostr;
}
function echoMusic($ToUserName, $FromUserName, $media_id)
{
    $time = time();
    $title = '这是你上传的音乐！';
    $desciption = '无法描述的音乐！！！';
    $echostr = <<<EEE
<xml><ToUserName><![CDATA[$FromUserName}]]></ToUserName><FromUserName><![CDATA[{$ToUserName}]]></FromUserName><CreateTime>{$time}</CreateTime><MsgType><![CDATA[music]]></MsgType><Music><Title><![CDATA[{$title}]]></Title><Description><![CDATA[{$desciption}]]></Description><MusicUrl><![CDATA[MUSIC\_Url]]></MusicUrl><HQMusicUrl><![CDATA[HQ\_MUSIC\_Url]]></HQMusicUrl><ThumbMediaId><![CDATA[$media_id]]></ThumbMediaId></Music></xml>
EEE;
    echo $echostr;
}
// include 'index.php';

// 文本回复消息以及根据文本回复图文消息
function echoText($postObj)
{
    $FromUserName = $postObj->FromUserName;
    $ToUserName = $postObj->ToUserName;
    $Content = trim($postObj->Content);
    switch ($Content) {
        case '单图文':
          $title="月亮真圆润";
          $desciption="十五的月亮别样圆";
          $time = time();          
          $picUrl="http://47.93.42.42/img/timg.jpg";
          $url="https://note.youdao.com/web/#/file/recent/note/WEB9180f9f4aecd02feed4142a55454a01d/";

            $echostr = <<<EEEEEEE
            <xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[news]]></MsgType>
            <ArticleCount>1</ArticleCount>
                <Articles>
                <item>
                <Title><![CDATA[%s]]></Title>
                <Description><![CDATA[%s]]></Description>
                <PicUrl><![CDATA[%s]]></PicUrl>
                <Url><![CDATA[%s]]></Url>
                </item>
                </Articles>
            </xml>  
EEEEEEE;
          echo sprintf($echostr,$FromUserName,$ToUserName,time(),$title,$desciption,$picUrl,$url);

            break;
            case '多图文':
            $newsArr=array(
                array(
                "Title"=>"约吗？亲",
                "Description"=>"你猜一下咯",
                "PicUrl"=>"http://47.93.42.42/img/timg.jpg",
                "Url"=>"https://note.youdao.com/web/#/file/recent/note/WEB9180f9f4aecd02feed4142a55454a01d/"
            ),
            array(
                "Title"=>"大圣归来",
                "Description"=>"你听过蔡健雅的紫这首歌吗？",
                "PicUrl"=>"http://47.93.42.42/img/1.jpg",
                "Url"=>"http://www.w3school.com.cn/js/js_switch.asp"
            )
            );
           
            foreach ($newsArr as $itemIndex) {
                # code...
                $temp="<item>
                <Title><![CDATA[%s]]></Title>
                <Description><![CDATA[%s]]></Description>
                <PicUrl><![CDATA[%s]]></PicUrl>
                <Url><![CDATA[%s]]></Url>
                </item>";
                $tempStr.=sprintf($temp,$itemIndex["Title"],$itemIndex["Description"],$itemIndex["PicUrl"],$itemIndex["Url"]);

            }
            $echostr = <<<EEEEEEE
                            <xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[news]]></MsgType>
                            <ArticleCount>%s</ArticleCount>
                                <Articles>".$tempStr."</Articles>
                            </xml>
EEEEEEE;
            echo sprintf($echostr,$FromUserName,$ToUserName,time(),count($newsArr));

            break;
        
        default:
            $sendText = 'hahha'.$Content;        
            $echostr = <<<EEEEEEE
<xml>
<ToUserName><![CDATA[{$FromUserName}]]></ToUserName>
<FromUserName><![CDATA[{$ToUserName}]]></FromUserName>
<CreateTime>{$time}</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[{$sendText}]]></Content>
</xml>
EEEEEEE;
            echo $echostr;
            break;
    }
    

}
// include "wechatApi.php";
