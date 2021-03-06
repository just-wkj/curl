<?php
/**
 * @author: justwkj
 * @date: 2022/5/20 10:27
 * @email: justwkj@gmail.com
 * @desc: CURL 工具类 get post
 */

namespace Justwkj\Curl;

class Curl {
    /**
     *  get请求
     * @param string $url 请求地址
     * @param array $params 请求参数
     * @param array $headers 请求头
     * @param int $timeOut 超时时间
     * @param bool $isFollow 302跳转跟随
     * @return bool|mixed
     * @author:wkj
     * @date  2017/6/1 14:48
     */
    public static function get($url, $params = [], $headers = [], $timeOut = 10, $isFollow = false) {
        $url = self::buildUrl($url, $params);
        $ch = curl_init();
        if (stripos($url, "https://") !== false) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSLVERSION, 1);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeOut);
        if ($isFollow) {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        }
        if ($headers) {
            $_header = [];
            foreach ($headers as $key => $vo) {
                $_header[] = $key . ':' . $vo;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $_header);
        }
        $sContent = curl_exec($ch);
        $aStatus = curl_getinfo($ch);
        curl_close($ch);

        if (intval($aStatus["http_code"]) == 200) {
            return $sContent;
        } else {
            return false;
        }

    }

    /**
     *  post请求
     * @param string $url 请求地址
     * @param array $post post数据
     * @param array $headers 请求头
     * @param int $timeOut 超时时间
     * @return mixed
     * @author:wkj
     * @date  2017/6/1 14:49
     */
    public static function post($url, array $post = [], $headers = [], $timeOut = 10) {
        $defaults = [
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $url,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_TIMEOUT => $timeOut,
            CURLOPT_POSTFIELDS => http_build_query($post),
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $defaults);
        if ($headers) {
            $_header = [];
            foreach ($headers as $key => $vo) {
                $_header[] = $key . ':' . $vo;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $_header);
        }
        if (stripos($url, "https://") !== false) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSLVERSION, 1);
        }
        if (!$result = curl_exec($ch)) {
            trigger_error(curl_error($ch));
        }
        curl_close($ch);

        return $result;
    }

    /**
     *  postJosn处理
     * @param string $url
     * @param array $post
     * @param array $headers
     * @param int $timeOut
     * @return mixed
     * @author:wkj
     * @date 2018/7/11 10:54
     */
    public static function postJson($url, array $post = [], $headers = [], $timeOut = 10) {
        $defaults = [
            CURLOPT_POST => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $url,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_TIMEOUT => $timeOut,
            CURLOPT_POSTFIELDS => json_encode($post),
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $defaults);
        if ($headers) {
            $_header = [];
            foreach ($headers as $key => $vo) {
                $_header[] = $key . ':' . $vo;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $_header);
        }
        if (stripos($url, "https://") !== false) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSLVERSION, 1);
        }
        if (!$result = curl_exec($ch)) {
            trigger_error(curl_error($ch));
        }
        curl_close($ch);

        return $result;
    }

    /**
     *  get请求针对接口json数据处理为数组
     * @param string $url 请求地址
     * @param array $params 请求参数
     * @param int $timeOut 超时时间
     * @param array $headers 请求头
     * @return bool|mixed
     * @author:wkj
     * @date  2017/6/1 14:49
     */
    public static function getArray($url, $params = [], $headers = [], $timeOut = 10) {
        $result = self::get($url, $params, $headers, $timeOut);
        if ($result) {
            return json_decode($result, true);
        }

        return false;
    }

    /**
     *  get请求针对接口json数据处理为数组
     * @param string $url 请求地址
     * @param array $params 请求参数数组格式
     * @return string
     * @author:wkj
     * @date  2017/6/1 14:49
     */
    public static function buildUrl($url, array $params = []) {
        $paramsStr = '';
        if (strpos($url, '?') === false) {
            $paramsStr .= '?';
        } else {
            $paramsStr .= '&';
        }
        $paramsStr .= http_build_query($params);

        return rtrim($url . $paramsStr, '&');
    }
}
