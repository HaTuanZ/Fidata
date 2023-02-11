<?php
if (!function_exists('curl_get')) {
    function curl_get($url, $proxy = "") {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if($proxy) curl_setopt($ch, CURLOPT_PROXY, $proxy);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-type: application/x-www-form-urlencoded"
        ));
        $ip = $_SERVER['SERVER_ADDR'];
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array("REMOTE_ADDR: $ip", "HTTP_X_FORWARDED_FOR: $ip"));
        $result = curl_exec($ch);
        if(curl_errno($ch) !== 0) {
            die('cURL error when connecting to ' . $url . ': ' . curl_error($ch));
        }
        curl_close($ch);
        $data = json_decode($result);
        return $data;
    }
}

if (!function_exists('random_string')) {
    function random_string($length = 6, $mode = 2){
        $result = "";
        if($mode == 1):
            $characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        elseif($mode == 2):
            $characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        elseif($mode == 3):
            $characters = "abcdefghijklmnopqrstuvwxyz0123456789";
        elseif($mode == 4):
            $characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        elseif($mode == 5):
            $characters = "abcdefghijklmnopqrstuvwxyz";
        elseif($mode == 6):
            $characters = "0123456789";
        endif;

        $charArray = str_split($characters);
        for($i = 0; $i < $length; $i++) {
            $randItem = array_rand($charArray);
            $result .="".$charArray[$randItem];
        }
        if($mode == 6) {
            $result = intval($result);
            if(strlen($result) < $length) {
                random_string($length, $mode);
            }
        }
        return $result;
    }
}

if (!function_exists('format_datetime')) {
    function format_datetime($datetime, $short = false) {
        if($datetime) {
            if(is_numeric($datetime)) {
                $strtotime = $datetime;
            } else {
                $strtotime = strtotime($datetime);
            }
            if($short) return date("m/d/Y", $strtotime);
            return date("m/d/Y H:i A", $strtotime);
        }
        return $datetime;
    }
}
