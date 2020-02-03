<?php
/**
 * Created by PhpStorm.
 * User: zhangcs
 * Date: 2020/1/31
 * Time: 6:29 PM
 */
namespace app\common;

class Redis {

    public static $pre = "sms_";

    public static $userPre = "user_";


    public static function smsKey($mobile) {
        return self::$pre . $mobile;
    }

    public static function userKey($mobile) {
        return self::$userPre . $mobile;
    }
}