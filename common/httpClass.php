<?php
    function HttpGet($url)
    {
        // 创建一个新cURL资源
        $curl = curl_init();
        // 设置URL和相应的选项
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // 抓取URL并把它传递给浏览器
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    function HttpPost($url, $postBody)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true); //fields
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postBody);
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    //file_get_contents()
