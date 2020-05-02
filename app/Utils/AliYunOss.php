<?php
// +----------------------------------------------------------------------
// | 阿里云OSS
// +----------------------------------------------------------------------
// | Author: weika <iweika@aliyun.com>
// +----------------------------------------------------------------------
// | Date: 2020/03/26
// +----------------------------------------------------------------------
// | Time: 17:18
// +----------------------------------------------------------------------

namespace App\Utils;


use OSS\Core\OssException;
use OSS\OssClient;

class AliYunOss
{
    private $ossConf = [];
    private $ossClient;
    private $priTTL = 60;
    public function __construct()
    {
        $this->ossConf = config('aliyunoss');
        $accessKeyId = $this->ossConf['access_key_id']; //读取配置文件
        $accessKeySecret = $this->ossConf['access_key_secret']; //读取配置文件
        $endpoint = $this->ossConf['endpoint']; //读取配置文件
        $this->ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);
        $this->ossClient->setUseSSL(false);
    }

    /**
     * 上传到公共空间
     * @param $uri string
     * @param $fileCnt mixed
     * @param $ext string
     * @param $isHttps bool
     * @return mixed|string
     */
    public function upload_file_oss($uri, $fileCnt,$ext='',$isHttps=false)
    {
        // 存储空间名称
        $bucket= $this->ossConf['public_bucket']; //读取配置文件
        // 文件名称<yourObjectName>上传文件到OSS时需要指定包含文件后缀在内的完整路径，例如abc/efg/123.jpg
        try {
            $this->ossClient->putObject($bucket, $uri, $fileCnt,$this->getOption($ext));
            $fileUrl = ($isHttps ? 'https://' : 'http://') . $this->ossConf['public_domain'].'/'.$uri;
            return [
                'url'=>$fileUrl,
                'uri'=>$uri,
            ];
        } catch (OssException $e) {
            return $e->getMessage();
        }
    }


    /**
     * 上传文件到私有云空间
     * @param $uri string
     * @param $fileCnt mixed
     *@param $ext string
     *
     * @return mixed|string
     */
    public function upload_file_oss_private($uri, $fileCnt,$ext='')
    {
        // 存储空间名称 privateDomain
        $bucket= $this->ossConf['private_bucket'];; //读取配置文件
        // 文件名称<yourObjectName>上传文件到OSS时需要指定包含文件后缀在内的完整路径，例如abc/efg/123.jpg
        try {
            $this->ossClient->putObject($bucket, $uri, $fileCnt,$this->getOption($ext));
            $fileUrl = $this->ossClient->signUrl($bucket, $uri, $this->priTTL);
            return [
                'url'=>$fileUrl,
                'uri'=>$uri,
            ];
        } catch (OssException $e) {
            return $e->getMessage();
        }
    }

    //通过后缀获上传配置
    private function getOption($ext){
        $option = [];
        switch ($ext){
            case 'png':
            case 'PNG':
            case 'jpeg':
            case 'JPEG':
            case 'JPG':
            case 'jpg':
                $option['Content-Type'] = 'image/jpg';
                break;
        }
        return $option;
    }

    /**
     * 获取访问链接
     * @param $uri string
     * @param bool $isPrivate
     * @param bool $isHttps
     * @param int $priTTL
     * @return string
     *
     * @throws OssException
     * @return string
     */
    public function getOssUrl($uri,$isPrivate=false,$isHttps=false,$priTTL=60)
    {
        return $isPrivate == 1 ? ($this->ossClient->signUrl($this->ossConf['private_bucket'], $uri, empty($priTTL) ? $this->priTTL : $priTTL)) :  $fileUrl = ($isHttps ? 'https://' : 'http://') . $this->ossConf['public_domain'].'/'.$uri;;
    }


}
