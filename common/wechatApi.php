<?php

include 'httpClass.php';
//wx501ed0a46939e261
// d8e6fe879f9d9dd8c04b655a526d0e2e
include 'fileClass.php';
class WeChat
{
    private $appId = 'wxce0ff9cae015e463';
    private $appSecret = 'f844311b916df87d55263498dcb7239a';

    public function __construct()
    {
        $this->fileObj = new FileSystem();
    }

    public function getAccessToken()
    {
        if (is_file($this->fileObj->filename)) {
            $json_obj = json_decode($this->fileObj->readFile());
            $access_token = $json_obj->access_token;
            $expires_time = $json_obj->expires_time;

            if (!($expires_time > time())) {
                return $this->getNewAccessToken();
            }

            return $access_token;
        } else {
            return $this->getNewAccessToken();
        }
    }

    // 获取新的access_token
    public function getNewAccessToken()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appId}&secret={$this->appSecret}";
        // 获取accessToken
        $json_str = HttpGet($url);
        // $json_str='{"access_token":"'.rand().'$$$_wybGouhhHiFEhQBWh4Rrgal6FP6S65Rfvyr0ZESuPNN_4OmaGgA3R7POMqAkUDhTh2runYDlSqiI4EiqDIDkoq-YQ5VaUQkLK06B_41Q9vcdcemkb4qhqYLs7EMPTDiAGABFP","expires_in":5}';
        // 转换为对象
        $json_obj = json_decode($json_str);
        // 取出access_token;
        // $access_token=$json_obj['access_token'];
        $access_token = $json_obj->access_token;

        // 获取过期时间 expires_time
        $expires_time = $json_obj->expires_in + time() - 300;

        // 存储
        $saveObj = array('access_token' => $access_token, 'expires_time' => $expires_time);
        $saveJson = json_encode($saveObj);
        $this->fileObj->writeFile($saveJson);

        return $access_token;
    }

    // 3创建菜单
    public function createMenu()
    {
        // 获取accesstoken
        $access_token = $this->getAccessToken();
        // 配置接口
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$access_token;
        // 配置数据
        $postBody = '{
			"button":[{
				"name":"click事件",
				"sub_button":[{
					"name":"发送文字",
					"type":"click",
					"key":"sendText",
					"sub_button":[]
				},{
					"name":"发送图片",
					"type":"click",
					"key":"sendImage",
					"sub_button":[]
				},{
					"name":"发送语音",
					"type":"click",
					"key":"sendVoice",
					"sub_button":[]
				},{
					"name":"视频",
					"type":"click",
					"key":"sendVideo",
					"sub_button":[]
				}]
			},{
				"name":"相册扫码",
				"sub_button":[{
					"name":"相机",
					"type":"pic_sysphoto",
					"key":"camera",
					"sub_button":[]
				},{
					"name":"相册",
					"type":"pic_weixin",
					"key":"album",
					"sub_button":[]
				},{
					"name":"相机或相册",
					"type":"pic_photo_or_album",
					"key":"photoOrAlbum",
					"sub_button":[]
				},{
					"name":"扫码带提示",
					"type":"scancode_waitmsg",
					"key":"scancodeText",
					"sub_button":[]
				},{
					"name":"扫一扫",
					"type":"scancode_push",
					"key":"scancode",
					"sub_button":[]
				}]
			},{
				"name":"其他事件",
				"sub_button":[{
					"name":"公司详情",
					"type":"view",
					"url":"http://1.weixinstudy02.applinzi.com/weixin/innerHtml/aboutMe.html"
				},{
					"name":"发送位置",
					"type":"location_select",
					"key":"sendLocation"
				},{
					"type": "scancode_push", 
                    "name": "扫码推事件", 
                    "key": "rselfmenu_0_1",
				}]
			}]
		}';
        //发送请求
        return HttpPost($url, $postBody);
    }

    public function deleteMenu()
    {
        $access_token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token={$access_token}";

        return HttpGet($url);
    }
}
$wx = new WeChat();
// echo $wx->getAccessToken();
echo $wx->createMenu();
