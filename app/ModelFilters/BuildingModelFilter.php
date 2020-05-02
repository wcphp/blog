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

class BuildingModelFilter extends ModelFilter
{
    public function landlord($value)
    {
        if(!empty($value)){
            return $this->where('landlord_id',$value);
        }
        return $this;
    }

}
