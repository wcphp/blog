<?php
// +----------------------------------------------------------------------
// | 阿里云OSS操作
// +----------------------------------------------------------------------
// | Author: cc <iweika@aliyun.com>
// +----------------------------------------------------------------------
// | Date: 2020/03/26
// +----------------------------------------------------------------------
// | Time: 12:20
// +----------------------------------------------------------------------
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 *
 * @method static mixed upload_file_oss(string $uri, mixed $fileCnt,string $ext='',bool $isHttps=false) 上传图片到公共空间
 * @method static mixed upload_file_oss_private(string $uri, mixed $fileCnt,string $ext='') 上传图片到私有空间
 * @method static string getOssUrl(string $uri,bool $isPrivate=false,bool $isHttps=false) 通过uri获取访问链接
 * @see  \App\Utils\AliYunOss
 */

class AliYunOss extends Facade
{
    protected static function getFacadeAccessor()
    {
        return '\App\Utils\AliYunOss';
    }
}
