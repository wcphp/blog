<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | Author: weika <iweika@aliyun.com>
// +----------------------------------------------------------------------
// | Date: 2020/03/26
// +----------------------------------------------------------------------
// | Time: 14:39
// +----------------------------------------------------------------------

namespace App\ModelFilters;


use EloquentFilter\ModelFilter;

class GoodsModelFilter extends ModelFilter
{
    public function category($value)
    {
        if(!empty($value)){
            return $this->where('category_id',$value);
        }
        return $this;
    }
    public function level($value)
    {
        if(!empty($value)){
            return $this->where('level_id',$value);
        }
        return $this;
    }

    public function status($value)
    {
        if(!empty($value)){
            return $this->where('status',$value);
        }
        return $this;
    }

}
