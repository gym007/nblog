function del(obj, nodeId) {
	var $ = layui.jquery;
	layer.confirm('确定要删除吗？', function (index) {
		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			url: '/admin/category/' + nodeId,
			data: nodeId,
			type: 'DELETE',
			dataType: 'JSON',
			success: function (res) {
				// res = JSON.parse(res);
				if (res.code === 200) {
					// $(obj).parents('tr').remove();
					location.reload();
					layer.close(index);
				} else {
					layer.msg(res.text, {icon: 5, time: 1000});
				}
			},
			error: function (res) {
			}
		});
	});
}
/*分类-停用*/
function member_stop(obj, id) {
	var confirmTip;
	var status;
	$ = layui.jquery;
	if($(obj).attr('title') == '启用') {
		confirmTip = '确认要启用吗？';
		status = 1;
	} else {
		confirmTip = '确认要停用吗？';
		status = 0;
	}

	var data = {'id': id, 'status': status, 'platform': 'front'};
	console.log(data);

	layer.confirm(confirmTip, function(index) {
		$.ajax({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			url: '/admin/category/status',
			type: 'post',
			data: {'id': id, 'status': status},
			dataType: 'JSON',
			success: function (res) {
				if (res.code != 200) {
					layer.alert(res.text, {
						closeBtn: 1,
						anim: 4,
						btn: ['确定'],
						icon: 2
					});
					return false;
				}
				if ($(obj).attr('title') == '启用') {
					console.log('1->0');
					$(obj).attr('title', '停用')
					$(obj).find('i').html('&#xe601;');
					// $(obj).parents("tr").find(".td-status").find('span').addClass('layui-btn-disabled').html('已停用');
					$(obj).parents("tr").find(".td-status").find('span').removeClass('layui-btn-disabled').html('已启用');
					layer.msg('已停用!', {
						icon: 5,
						time: 1000
					});
				} else {
					console.log('0->1');
					$(obj).attr('title', '启用')
					$(obj).find('i').html('&#xe62f;');

					// $(obj).parents("tr").find(".td-status").find('span').removeClass('layui-btn-disabled').html('已启用');
					$(obj).parents("tr").find(".td-status").find('span').addClass('layui-btn-disabled').html('已停用');
					layer.msg('已启用!', {
						icon: 6,
						time: 1000
					});
				}
			},
			error: function (res) {

			}
		});

	});
}
//自定义的render渲染输出多列表格
var layout = [{
		name: '菜单名称',
		treeNodes: true,
		headerClass: 'value_col',
		colClass: 'value_col',
		style: 'width: 60%'
	},
	{
		name: '状态',
		headerClass: 'td-status',
		colClass: 'td-status',
		style: 'width: 10%',
		render: function(row) {
			if (row.status < 1) {
				return '<span class="layui-btn layui-btn-normal layui-btn-xs layui-btn-disabled">已停用</span>';
			} else {
				return '<span class="layui-btn layui-btn-normal layui-btn-xs">已启用</span>';
			}
		}
	},
	{
		name: '操作',
		headerClass: 'td-manage',
		colClass: 'td-manage',
		style: 'width: 20%',
		render: function(row) {
			var id = row.id;
			var text;
			if (row.status < 1) {
				text = '启用';
				iconText = '&#xe62f';
			} else {
				text = '停用';
				iconText = '&#xe601';
			}
			// console.log('status' + text);
			return '<a onclick="member_stop(this,' + row.id + ')" href="javascript:;" title="' + text + '"><i class="layui-icon">' + iconText + ';</i></a>' +
				'<a title="添加子类" onclick="WeAdminShow(\'添加\',\'./category/create\')" href="javascript:;"><i class="layui-icon">&#xe654;</i></a>' +
				'<a title="编辑" onclick="WeAdminShow(\'编辑\',\'./category/' + id + '/edit\')" href="javascript:;"><i class="layui-icon">&#xe642;</i></a>' +
				'<a title="删除" onclick="del(this,' + row.id + ')" href="javascript:;">\<i class="layui-icon">&#xe640;</i></a>';
			//return '<a class="layui-btn layui-btn-danger layui-btn-mini" onclick="del(' + row.id + ')"><i class="layui-icon">&#xe640;</i> 删除</a>'; //列渲染
		}
	},
];
//加载扩展模块 treeGird
//		layui.config({
//			  base: './static/js/'
//			  ,version: '101100'
//			}).use('admin');

// 以下放在页面加载

// layui.extend({
// 	admin: '{/}../../static/js/admin',
// 	// treeGird: '{/}../../lib/layui/lay/treeGird' // {/}的意思即代表采用自有路径，即不跟随 base 路径
// 	treeGird: '{/}/static/admin/lib/layui/lay/treeGird' // {/}的意思即代表采用自有路径，即不跟随 base 路径
// });
// layui.use(['treeGird', 'jquery', 'admin', 'layer'], function() {
// 	var layer = layui.layer,
// 		$ = layui.jquery,
// 		admin = layui.admin,
// 		treeGird = layui.treeGird;
//
// 	var tree1 = layui.treeGird({
// 		elem: '#demo', //传入元素选择器
// 		spreadable: true, //设置是否全展开，默认不展开
// 		nodes: [{
// 				"id": "1",
// 				"name": "父节点1",
// 				"children": [{
// 						"id": "11",
// 						"name": "子节点11"
// 					},
// 					{
// 						"id": "12",
// 						"name": "子节点12"
// 					}
// 				]
// 			},
// 			{
// 				"id": "2",
// 				"name": "父节点2",
// 				"children": [{
// 					"id": "21",
// 					"name": "子节点21",
// 					"children": [{
// 						"id": "211",
// 						"name": "子节点211"
// 					}]
// 				}]
// 			}
// 		],
// 		layout: layout
// 	});
// 	$('#collapse').on('click', function() {
// 		layui.collapse(tree1);
// 	});
//
// 	$('#expand').on('click', function() {
// 		layui.expand(tree1);
// 	});
// });
