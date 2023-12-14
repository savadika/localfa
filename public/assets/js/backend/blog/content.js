define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                // 定义表格的扩展url
                extend: {
                    index_url: 'blog/content/index' + location.search,
                    add_url: 'blog/content/add',
                    edit_url: 'blog/content/edit',
                    del_url: 'blog/content/del',
                    multi_url: 'blog/content/multi',
                    import_url: 'blog/content/import',
                    table: 'blog_content',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        // {field: 'id', title: __('Id'),operate:false},  开启了operate:false以后，就可以控制搜索条件的显示了
                        {field: 'id', title: __('Id')},
                        {field: 'title', title: __('Title'), operate: 'LIKE'},
                        {field: 'blogimage', title: __('Blogimage'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'views', title: __('Views')},
                        {field: 'category_id', title: __('Category_id')},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        recyclebin: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    'dragsort_url': ''
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: 'blog/content/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'title', title: __('Title'), align: 'left'},
                        {
                            field: 'deletetime',
                            title: __('Deletetime'),
                            operate: 'RANGE',
                            addclass: 'datetimerange',
                            formatter: Table.api.formatter.datetime
                        },
                        {
                            field: 'operate',
                            width: '140px',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'Restore',
                                    text: __('Restore'),
                                    classname: 'btn btn-xs btn-info btn-ajax btn-restoreit',
                                    icon: 'fa fa-rotate-left',
                                    url: 'blog/content/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'blog/content/destroy',
                                    refresh: true
                                }
                            ],
                            formatter: Table.api.formatter.operate
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },

        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },

        //test页面事件
        test:function (){
            Controller.api.bindevent();
            // 点击确定的方法
            $('#testconfim').click(function (){
                // 使用这个api的话，后面处理就必须要用$this->success来进行返回
                Fast.api.ajax({url:'blog/content/testadd', data:$('#add-form').serialize()
                }, function(data, ret){
                    //成功的回调,data是直接获取数据，ret是加上了状态等其他信息的数据，可以直接使用data
                    if(data.status == 'success'){
                        // 关闭当前层
                        Fast.api.close(data);
                        // 刷新父列表数据
                        parent.$('.btn-refresh').trigger('click');
                    }
                    // 关闭窗体并更新父列表数据
                    Fast.api.close(data);
                }, function(data, ret){
                    console.log('haha');
                    return false;
                });
            });
        },

        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };

    //操作栏添加事件按钮
    $(document).on('click','.spec_add_btn', function (event) {
        // 这里都是定义变量，包括url,msg,以及其他的options等
        // 这个url是用来打开自定义的ifram页面
        var url = $(this).attr('data-url');
        if(!url) return false;
        var msg = $(this).attr('data-title');
        var width = $(this).attr('data-width');
        var height = $(this).attr('data-height');
        var area = [$(window).width() > 800 ? (width?width:'800px') : '95%', $(window).height() > 600 ? (height?height:'600px') : '95%'];
        var options = {
            shadeClose: false,
            shade: [0.3, '#393D49'],
            area: area,
            callback:function(value){
                //do something
            }
        };
        //调用fast的api来打开窗口
        Fast.api.open(url,msg,options);
    });

    return Controller;
});
