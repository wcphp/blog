<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | Author: cc <iweika@aliyun.com>
// +----------------------------------------------------------------------
// | Date: 2020/03/28
// +----------------------------------------------------------------------
// | Time: 22:14
// +----------------------------------------------------------------------

namespace App\Http\Models;


use Illuminate\Database\Eloquent\SoftDeletes;

class SystemConf extends BaseModel
{
    use SoftDeletes;
    protected $table = 'system_conf';
    protected $primaryKey = 'sconf_id';
}
