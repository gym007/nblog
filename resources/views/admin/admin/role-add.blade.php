@extends('admin.layouts.admin')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
@section('content')
	<div class="weadmin-body">
			<form action="" method="post" class="layui-form layui-form-pane">
				<div class="layui-form-item">
					<label for="name" class="layui-form-label">
                        <span class="we-red">*</span>角色名
                    </label>
					<div class="layui-input-inline">
						<input type="text" id="name" value="{{ $role['name'] }}" name="name" required="" lay-verify="required" autocomplete="off" class="layui-input">
						<input type="hidden" value="{{ $role['id'] }}" name="id" required="" lay-verify="required" autocomplete="off" class="layui-input">
					</div>
				</div>

				<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
					<legend>请选择权限</legend>
				</fieldset>
				<div id="test3" class="demo-transfer">123</div>

				<div class="layui-form-item">
					<button class="layui-btn" lay-submit="" lay-filter="add">提交</button>
				</div>
			</form>
		</div>
@endsection
@section('script')
<script type="text/javascript" src="{{asset(_ADMIN_ . '/lib/layui/layui.js')}}" charset="utf-8"></script>
@endsection
@section('js')
<script type="text/javascript">
			layui.extend({
				// admin: '{/}../../static/js/admin'
			});
			layui.use(['transfer', 'form', 'layer', 'util'], function() {
				var form = layui.form,
					transfer = layui.transfer,
					util = layui.util,
					// admin = layui.admin,
					$ = layui.jquery,
					layer = layui.layer;

				//初始左侧数据
				var data2 = @json($menuTree);
				var value = @json($has_ids);
				ll(value);
				// data2 = [[${data2}]];
				// data2 = StringEscapeUtils.unescapeHtml(data2);
				// data2 = JSON.parse(data2);
				transfer.render({
					title: ['未拥有权限', '已拥有权限'],
					elem: '#test3',
					data: data2,
					width: 400,
					// value: ["3", "5", "7", "9", "11"], // 初始右侧数据
					value: value, // 初始右侧数据
					onchange: function(obj, index){
						var arr = ['未拥有权限', '已拥有权限'];
						// layer.alert('来自 <strong>'+ arr[index] + '</strong> 的数据：'+ JSON.stringify(obj)); //获得被穿梭时的数据
						transfer.reload('tree123', {
							title: ['未拥有权限', '已拥有权限']
						});
					},
					id: 'tree123'

				})

				//监听提交
				form.on('submit(add)', function(data) {
					var getData = transfer.getData('tree123');
					ll('获取到右侧的数据');
					ll(getData);
					if (getData.length < 1) {
						layer.msg('请至少选择一个权限~！', function () {});
						return false;
					}
					var arr = new Array();
					$.each(getData, function (i, v) {
						arr.push(v.id);
					});
					data.field.menu_ids = arr;

					var edit = {{ $role['edit'] }};
					var uri,type;
					if (edit) {
						var id = {{ $role['id'] }};
						uri = '/admin/role/' + id;
						type = 'PUT';
						data.field._method = 'PUT';
					} else {
						uri = '/admin/role';
						type = 'POST';
					}


					$.ajax({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						url: uri,
						data: data.field,
						dataType: 'JSON',
						type: type,
						success: function (res) {
							if (res.code === 200) {
								layer.alert("OK", {
									icon: 6
								}, function() {
									// 获得frame索引
									var index = parent.layer.getFrameIndex(window.name);
									//关闭当前frame
									parent.layer.close(index);
								});
							} else {
								layer.msg(res.text, function () {});
								return false;
							}

						},
						error: function (res) {
							ll('error');
							ll(res);
						}
					});

					// console.log(data);
					// //发异步，把数据提交给php
					// layer.alert("增加成功", {
					// 	icon: 6
					// }, function() {
					// 	// 获得frame索引
					// 	var index = parent.layer.getFrameIndex(window.name);
					// 	//关闭当前frame
					// 	parent.layer.close(index);
					// });
					return false;
				});

			});
		</script>
@endsection