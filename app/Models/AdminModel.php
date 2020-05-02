<?php
// +----------------------------------------------------------------------
// | 后台账户
// +----------------------------------------------------------------------
// | Author: weika <iweika@aliyun.com>
// +----------------------------------------------------------------------
// | Date: 2020/03/28
// +----------------------------------------------------------------------
// | Time: 22:14
// +----------------------------------------------------------------------

namespace App\Models\System;


use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminModel extends BaseModel
{
    use SoftDeletes;
    protected $table = 'admin';
    protected $primaryKey = 'admin_id';
}
