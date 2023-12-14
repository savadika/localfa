<?php

namespace app\admin\controller\blog;

use app\common\controller\Backend;
use think\Db;
use think\exception\PDOException;
use think\exception\ValidateException;

/**
 * 标签管理
 *
 * @icon fa fa-circle-o
 */
class Lable extends Backend
{

    /**
     * Lable模型对象
     * @var \app\admin\model\blog\Lable
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\blog\Lable;

    }

    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi/destroy/restore/recyclebin八个方法
     * 因此在当前控制器中可不用编写增删改查的代码,如果需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */


}
