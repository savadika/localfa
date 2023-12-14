<?php

namespace app\admin\controller\blog;

use app\common\controller\Backend;
use fast\Tree;
use think\Db;
use Exception;
use think\exception\PDOException;
use think\exception\ValidateException;


/**
 * 博客管理
 *
 * @icon fa fa-circle-o
 */
class Content extends Backend
{

    /**
     * Content模型对象
     * @var \app\admin\model\blog\Content
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\blog\Content;
        $this->getCateTree();
        $this->getLabels();
    }



    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    /**
     * 添加
     *
     * @return string
     * @throws \think\Exception
     */
    public function add()
    {
        if (false === $this->request->isPost()) {
            return $this->view->fetch();
        }
        $params = $this->request->post('row/a');
        if (empty($params)) {
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $params = $this->preExcludeFields($params);
        if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
            $params[$this->dataLimitField] = $this->auth->id;
        }
        $result = false;
        Db::startTrans();
        try {
            //是否采用模型验证
            if ($this->modelValidate) {
                $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                $this->model->validateFailException()->validate($validate);
            }
            $result = $this->model->allowField(true)->save($params);
            Db::commit();
        } catch (ValidateException|PDOException|Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }
        if ($result === false) {
            $this->error(__('No rows were inserted'));
        }
        $this->success();
    }


    public  function getCateTree(){
        //1  多对多表的情况   显示另外一张表的数据
        $category_model = new \app\admin\model\blog\Category;
        $list = collection($category_model->select())->toArray();
        //3  初始化树
        $tree = Tree::instance()->init($list);
        //4  获取节点为0的数据
        $treeArray = $tree->getTreeArray(0);
        //5  树状数组转化为二维数组
        $ret = $tree->getTreeList($treeArray);
        //6  进一步转化为前端需要的二维数组
        $groupdata = [];
        foreach ($ret as $key => $v){
            $groupdata[$v['id']]=$v['name'];
        }
        $this->view->assign('groupdata',$groupdata);
    }



    /**
     * 获取标签列表
     * @return json
     */

    public  function getLabels(){
        $lableModel = new \app\admin\model\blog\Lable;
        $test = collection($lableModel->select());
        $list = collection($lableModel->select())->toArray();
        $labeldata = [];
        foreach ($list as $key => $v){
            $labeldata[$v['id']] = $v['name'];
        }
        $this->view->assign('labeldata',$labeldata);
    }


    // 返回测试的iframe页面,fetch是返回模版输出
    public function test(){
        return $this->fetch('test');
    }

    public function testadd(){
        // 参数校验
        $params = $this->request->post('row/a');
        if (empty($params)) {
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $params = $this->preExcludeFields($params);
        $result = false;
        Db::startTrans();
        try{
            $result = Db::table('blog_content')->insert($params);
            Db::commit();
        }catch (ValidateException|PDOException|Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }
        if ($result === false) {
            $this->error(__('No rows were inserted'));
        }
        $this->success('插入成功！','',[
            'code'=>200,
            'status'=>'success',
        ]);
    }





}
