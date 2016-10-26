<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件


function i_sau_validate($username, $password) {

    $login_url  = 'http://cas.sau.edu.cn:8080/cas/login';
    $ch = curl_init($login_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $content = curl_exec($ch);
    $csrf_token_matches = [];
    $csrf_token = '';
    preg_match('/<input type="hidden" name="lt" value="(.*?)" \/>/i' ,$content, $csrf_token_matches);
    $csrf_token = $csrf_token_matches[1];
    $login_url  = 'http://cas.sau.edu.cn:8080/cas/login';
    $password = md5($password);
    $post_fields = "serviceName=0&loginErrCnt=0&username=$username&password=$password&lt=$csrf_token";
    $ch = curl_init($login_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    $content = curl_exec($ch);
    curl_close($ch);

    if(!$content) {
        return "timeout";
    } else if(preg_match('/mistake_notice/i', $content)){
        return 'denied';
    } else if(preg_match('/window\.location="cas\.sau\.edu\.cn:8080\/cas"/i', $content)) {
        return "ok";
    } else {
        return "unknow";
    }
}


function hash_crypt($password) {
    $__SALT__ = 'Author:netcon';
    return md5($password . $__SALT__);
}