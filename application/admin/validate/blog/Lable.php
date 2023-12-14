<?php

namespace app\admin\validate\blog;

use think\Validate;

class Lable extends Validate
{
    /**
     * 验证规则,增加后端校验
     */
    protected $rule = [
        'name' => 'min:3',
    ];
    /**
     * 提示消息，直接以字段.规则来写
     */
    protected $message = [
        'name.min'=>'标签长度不能小于3',
    ];
    /**
     * 验证场景
     */
    protected $scene = [
    ];
    
}
