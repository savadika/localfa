<?php

namespace app\admin\model\blog;

use think\Model;
use traits\model\SoftDelete;

class Content extends Model
{

    use SoftDelete;

    

    // 表名
    protected $table = 'blog_content';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [

    ];
    

    







}
