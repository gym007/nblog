@extends('admin.layouts.admin')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
@section('content')
<div class="weadmin-nav">
			<span class="layui-breadcrumb">
		        <a href="">首页</a>
		        <a href="">文章管理</a>
		        <a><cite>分类管理</cite></a>
		    </span>
	<a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right" href="javascript:location.replace(location.href);" title="刷新">
		<i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="weadmin-body">
	<div class="weadmin-block">
		<button class="layui-btn" id="expand">全部展开</button>
		<button class="layui-btn" id="collapse">全部收起</button>
		<button class="layui-btn" onclick="WeAdminShow('添加分类','/admin/category/create')"><i class="layui-icon"></i>添加</button>
		<span class="fr" style="line-height:40px">共有数据：66 条</span>
	</div>

	<div id="demo"></div>
</div>
@endsection
@section('script')
<script type="text/javascript" src="{{asset(_ADMIN_ . '/lib/layui/layui.js')}}" charset="utf-8"></script>
<script src="{{asset(_ADMIN_ . '/category.js')}}" type="text/javascript" charset="utf-8"></script>
@endsection
@section('js')
<script>
	layui.extend({
		admin: '{/}../../static/js/admin',
		// treeGird: '{/}../../lib/layui/lay/treeGird' // {/}的意思即代表采用自有路径，即不跟随 base 路径
		treeGird: '{/}/static/admin/lib/layui/lay/treeGird' // {/}的意思即代表采用自有路径，即不跟随 base 路径
	});
	layui.use(['treeGird', 'jquery', 'admin', 'layer'], function() {
		var layer = layui.layer,
				$ = layui.jquery,
				admin = layui.admin,
				treeGird = layui.treeGird;

		var tree1 = layui.treeGird({
			elem: '#demo', //传入元素选择器
			spreadable: true, //设置是否全展开，默认不展开

			// nodes: [{
			// 		"id": "1",
			// 		"name": "父节点1",
			// 		"children": [{
			// 				"id": "11",
			// 				"name": "子节点11"
			// 			},
			// 			{
			// 				"id": "12",
			// 				"name": "子节点12"
			// 			}
			// 		]
			// 	},
			// 	{
			// 		"id": "2",
			// 		"name": "父节点2",
			// 		"children": [{
			// 			"id": "21",
			// 			"name": "子节点21",
			// 			"children": [{
			// 				"id": "211",
			// 				"name": "子节点211"
			// 			}]
			// 		}]
			// 	}
			// ],
			nodes: @json($categories),
			layout: layout
		});
		$('#collapse').on('click', function() {
			layui.collapse(tree1);
		});

		$('#expand').on('click', function() {
			layui.expand(tree1);
		});
	});
</script>
@endsection