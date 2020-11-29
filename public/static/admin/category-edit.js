layui.extend({
    admin: '{/}/static/admin/static/js/admin',
    treeSelect: '{/}/static/admin/lib/layui/lay/modules/treeSelect'
});
layui.use(['admin', 'jquery', 'element', 'upload', 'form', 'layer', 'layedit', 'treeSelect'], function () {

    var admin = layui.admin,
        $ = layui.jquery,
        element = layui.element,
        upload = layui.upload,
        form = layui.form,
        layer = layui.layer,
        treeSelect = layui.treeSelect,
        layedit = layui.layedit;

    // console.log(treeSelect);
    // console.log(form);
    //图片上传
    //上传缩略图，设定文件大小限制
    upload.render({
        elem: '#topicImg',
        url: '/upload/',
        size: 500 //限制文件大小，单位 KB
        ,
        done: function (res) {
            console.log(res)
        }
    });
    //选择文件，栏目模板
    upload.render({
        elem: '#topicModelBtn',
        url: '/upload/',
        auto: false,
        accept: 'file'
        //,multiple: true
        ,
        bindAction: '#editTopic',
        choose: function (res) {
            //var files = res.pushFile();
            //预读本地文件，如果是多文件，则会遍历。(不支持ie8/9)
            res.preview(function (index, file, result) {
                //console.log(index); //得到文件索引
                //console.log(file); //得到文件对象
                //console.log(result); //得到文件base64编码，比如图片
                $('input[name=topicModel]').val(file.name);
                //console.log($('input[name=topicModel]').val())

            });
        }
    });

    layedit.build('topicBody'); //建立编辑器

    //监听提交
    form.on('submit(add)', function (data) {
        console.log(data.field);
        //发异步，把数据提交给php
        layer.alert("增加成功", {
            icon: 6
        }, function () {
            // 获得frame索引
            var index = parent.layer.getFrameIndex(window.name);
            //关闭当前frame
            parent.layer.close(index);
        });
        return false;
    });

    //遍历select option

    // $(document).ready(function() {
    // 	$("#pid-select option").each(function(text) {
    //
    // 		var level = $(this).attr('data-level');
    // 		var text = $(this).text();
    // 		//console.log(text);
    // 		if(level > 0) {
    // 			text = "├　" + text;
    // 			for(var i = 0; i < level; i++) {
    // 				text = "　　" + text;　 //js中连续显示多个空格，需要使用全角的空格
    // 				//console.log(i+"text:"+text);
    // 			}
    // 		}
    // 		$(this).text(text);
    //
    // 	});
    //
    // 	form.render('select'); //刷新select选择框渲染
    // });

    console.log(form);
    console.log(treeSelect);

    var data = [
        {
            "id": 1,
            "name": "zzz",
            "open": true,
            "children": [
                {
                    "id": 2,
                    "name": "1",
                    "open": false,
                    "checked": true
                },
                {
                    "id": 3,
                    "name": "2",
                    "open": false,
                    "checked": true

                },
                {
                    "id": 17,
                    "name": "3z",
                    "open": false,
                    "checked": true
                }
            ],
            "checked": true
        },
        {
            "id": 4,
            "name": "评论",
            "open": false,
            "children": [
                {
                    "id": 5,
                    "name": "留言列表",
                    "open": false,
                    "checked": false
                },
                {
                    "id": 6,
                    "name": "发表留言",
                    "open": false,
                    "checked": false
                },
                {
                    "id": 333,
                    "name": "233333",
                    "open": false,
                    "checked": false
                }
            ],
            "checked": false
        },
        {
            "id": 10,
            "name": "权限管理",
            "open": false,
            "children": [
                {
                    "id": 8,
                    "name": "用户列表",
                    "open": false,
                    "children": [
                        {
                            "id": 40,
                            "name": "添加用户",
                            "open": false,

                            "url": null,
                            "title": "40",
                            "checked": false,
                            "level": 2,
                            "check_Child_State": 0,
                            "check_Focus": false,
                            "checkedOld": false,
                            "dropInner": false,
                            "drag": false,
                            "parent": false
                        },
                        {
                            "id": 41,
                            "name": "编辑用户",
                            "open": false,
                            "checked": false
                        },
                        {
                            "id": 42,
                            "name": "删除用户",
                            "open": false,
                            "checked": false
                        }
                    ],
                    "checked": false
                },
                {
                    "id": 11,
                    "name": "角色列表",
                    "open": false,
                    "checked": false
                },
                {
                    "id": 13,
                    "name": "所有权限",
                    "open": false,
                    "children": [
                        {
                            "id": 34,
                            "name": "添加权限",
                            "open": false,
                            "checked": false
                        },
                        {
                            "id": 37,
                            "name": "编辑权限",
                            "open": false,
                            "checked": false
                        },
                        {
                            "id": 38,
                            "name": "删除权限",
                            "open": false,
                            "checked": false
                        }
                    ],
                    "checked": false
                },
                {
                    "id": 15,
                    "name": "操作日志",
                    "open": false,
                    "checked": false
                }
            ],
            "checked": false
        }
    ];

    // treeSelect组件
    treeSelect.render({
        // 选择器
        elem: '#tree',
        // 数据
        // data: 'data/data3.json',
        data: data,
        // 异步加载方式：get/post，默认get
        type: 'get',
        // 占位符
        placeholder: '占位符',
        // 是否开启搜索功能：true/false，默认false
        search: true,
        // 点击回调
        click: function (d) {
            console.log(d);
        },
        // 加载完成后的回调函数
        success: function (d) {
            console.log(d);
//                选中节点，根据id筛选
//                treeSelect.checkNode('tree', 3);

//                获取zTree对象，可以调用zTree方法
//                var treeObj = treeSelect.zTree('tree');
//                console.log(treeObj);

//                刷新树结构
//                treeSelect.refresh();
        }
    });


});