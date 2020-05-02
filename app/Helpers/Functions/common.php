<?php
// +----------------------------------------------------------------------
// | 公共函数
// +----------------------------------------------------------------------
// | Author: cc <iweika@aliyun.com>
// +----------------------------------------------------------------------
// | Date: 2020/03/25
// +----------------------------------------------------------------------
// | Time: 14:44
// +----------------------------------------------------------------------

/**
 * 获取缓配置
 * @param string $cacheType     缓存类型
 * @param mixed $key       缓存标记
 * @param boolean $force       true-当获取到的键位空强制生成一个  false-当获取到的键位空返回空,抛出错误异常
 * @return array
 */
function get_cache_conf($key,$cacheType='',$force=false){
    $cacheConf = empty($cacheType) ? [] : config('cache_conf.'.$cacheType,'' );
    $key = is_string($key) ?  $key : md5(json_encode($key));
    if(empty($cacheConf)){
        $cacheConf = [
            'key'=>empty($key) && $force ? md5(time()) : $key,
            'ttl'=>60
        ];
    }else{
        $cacheConf['key'] = empty($cacheConf['key']) && $force ? md5(time()) : ($cacheConf['key'].$key) ;
        $cacheConf['ttl'] = empty(intval($cacheConf['ttl'])) ? 60 : intval($cacheConf['ttl']);
    }

   if(empty($cacheConf['key'])){
        throw new \Exception('缓存KEY为空');
   }
   return $cacheConf;
}

/**
 * 分页数据过滤
 * @param \Illuminate\Contracts\Pagination\LengthAwarePaginator|object $object     要过滤数据
 * @param array $append   自定义获取器字段
 *
 * @return array
 */
function page_data_filter($object,$append=[]){
    $filterData = [
        "last_page" => $object->lastPage(),
        "current_page" => $object->currentPage(),
        "total" => $object->total(),
    ];

    if(empty($append)){
        $filterData['data'] = ($object->toArray())['data'];
    }else{
        $data = [];
        $items = $object->items();
        foreach ($items as $item){
            $data[] =   $item->append($append)->toArray();
        }
        $filterData['data'] = $data;
    }
    return $filterData;
}

/**
 * 数组元素中的null 强制转换为空string类型
 * @param $arr
 */
function all_to_string(&$arr)
{
    if (is_array($arr) && !empty($arr)) {
        array_walk_recursive($arr, function (&$item) {
            if (is_array($item)) {
                all_to_string($item);
            }
            if (is_null($item)) {
                $item = (string)$item;
            }
            if (is_numeric($item)) {
                $item = (string)$item;
            }

        });
    }
}

/**
 * 生成随机字符串
 * @param number $num 字符串长度
 * @return string
 */
function random_str($num = 8, $pStr = '')
{
    $i = 1;
    $str = '';
    do {
        $str .= md5(microtime(true) . '-' . $pStr);
        $i++;
    } while ($i < ceil($num / 32));
    return substr($str, 0, $num);

}

/**
 * 清空目录下所有文件
 * @param string $dirPath 目录地址
 * @return   bool     成功true, 失败false
 */
function empty_dir($dirPath)
{
    if (is_dir($dirPath)) {
        $dn = @opendir($dirPath);
        if ($dn !== false) {
            while (false !== ($file = readdir($dn))) {
                if ($file != '.' && $file != '..') {
                    if (!unlink($dirPath . $file)) {
                        return false;
                    }
                }
            }
        } else {
            return false;
        }
    }
    return true;
}

/**
 * 获取管理员密码
 * @param string $pwd 密码
 * @param string $salt 密码串
 * @return string
 */
function get_admin_pwd($pwd, $salt = '')
{
    return md5(config('app.admin_pwd_key','') . $pwd . $salt);
}

/***
 * @param $object
 * @param array $append
 * @return array
 */
function db_data_filter($object){
    $filterData = [
        "last_page" => $object->lastPage(),
        "current_page" => $object->currentPage(),
        "total" => $object->total(),
    ];
    $filterData['data'] = [];
    if (!empty($object->items())) {
        foreach ($object->items() as $item) {
            $filterData['data'][] = (array) $item;
        }
    }
    return $filterData;
}
