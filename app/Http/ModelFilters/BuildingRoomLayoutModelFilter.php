<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | Author: cc <iweika@aliyun.com>
// +----------------------------------------------------------------------
// | Date: 2020/03/26
// +----------------------------------------------------------------------
// | Time: 14:39
// +----------------------------------------------------------------------

namespace App\Http\ModelFilters;


use EloquentFilter\ModelFilter;

class BuildingRoomLayoutModelFilter extends ModelFilter
{
    public function building($value)
    {
        if(!empty($value)){
            return $this->where('building_id',$value);
        }
        return $this;
    }

}
