@extends('admin.layouts.admin')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
<style type="text/css">
    /* 新增删除图标的样式 */

    .layui-form-label {
        width: 100%;
        text-align: left;
    }

    .layui-form-label,
    .layui-form-item .layui-input-inline {
        margin-top: 10px;
    }

    .yf-input-del .layui-input-inline .layui-input {
        padding-right: 20px;
    }

    .yf-input-del .layui-input-inline i.layui-icon {
        position: absolute;
        right: 2px;
        top: 10px;
        color: #999999;
    }
</style>
@section('content')
<div class="weadmin-nav">
			<span class="layui-breadcrumb">
				<a href="javascript:;">首页</a>
				<a href="javascript:;">文章管理</a>
				<a href="javascript:;"><cite>标签管理</cite></a>
			</span>
    <a class="layui-btn layui-btn-sm" style="margin-top:3px;float:right"
       href="javascript:location.replace(location.href);"
       title="刷新">
        <i class="layui-icon layui-icon-refresh"></i>
    </a>
</div>
<div class="weadmin-body">
    <div class="layui-row">

        <div class="weadmin-block">
            <button class="layui-btn" onclick="insertInput()"><i class="layui-icon layui-icon-add-circle"></i>新增1条标签
            </button>
            <span class="fr" style="line-height:40px">共有数据：<i class="numText">88</i> 条</span>
        </div>

        <form class="layui-form layui-col-md12 we-search">
            <div class="layui-form-item yf-input-del" id="edu_bg">
                <label class="layui-form-label">教育背景:(编辑已有标签时请勿编辑左边的数字和‘-’符号)</label>

                @foreach ($tags as $tag)
                    <div class="layui-input-inline" id="insertInput11">
                        <input type="text" name="edu_bg[{{$tag['id']}}]" value="{{$tag['id'] . '-' . $tag['name']}}"
                               autocomplete="off" placeholder="请输入" lay-verify="required" class="layui-input">
                        <i class="layui-icon layui-icon-close deleteEduBg"></i>
                    </div>
                @endforeach
            </div>

{{--            <div class="layui-form-item">--}}
                <div class="weadmin-block">
                    <button class="layui-btn" id="editTopic" lay-submit="" lay-filter="add">立即提交</button>
                </div>
{{--            </div>--}}

        </form>
    </div>

</div>
@endsection
@section('script')
<script type="text/javascript" src="{{asset(_ADMIN_ . '/lib/layui/layui.js')}}" charset="utf-8"></script>
@endsection
@section('js')
<script>
    layui.use(['element', 'jquery', 'form'], function () {
        var element = layui.element,
            form = layui.form,
            $ = layui.jquery;

        var key1 = $("#edu_bg").children(".layui-input-inline").length;
        var FieldCount1 = 0;
        $(function () {
            $(".numText").html(key1);
            console.log("Hello,input输入框随意增加删除，新增时生成的name自动判断是否存在，存在则跳过，避免随意删除新增后提交因为name重复输入值被覆盖。");
        });

        window.insertInput = function () {
            //检索已有的input包含的name值
            var arr1 = [];
            FieldCount1++;
            $("#edu_bg input[type='text']").each(function () {
                arr1.push($(this).attr('name'));

            });
            // ll(123);
            // console.log(arr1);
            //alert(arr1);
            //所有name加入数组alert
            var div1 = $("<div></div>").addClass("layui-input-inline");
            // console.log(edu_bg[FieldCount1]);
            var newCount1 = "edu_bg[" + FieldCount1 + "]";
            //判断新生成的name值是否在已存在的数组中
            // ll('---------------------------------------------------');
            // ll(FieldCount1);
            // ll(newCount1);
            // ll(arr1);
            // ll('---------------------------------------------------');
            if ($.inArray(newCount1, arr1) === -1) {
                FieldCount1 = FieldCount1;
                var input1 = "<input type='text' name='edu_bg[" + FieldCount1 +
                    "]' value='' autocomplete='off' placeholder='请输入' lay-verify='required' class='layui-input'>"
                var icon1 = "<i class='layui-icon deleteEduBg'>&#x1006;</i>";
                div1.append(input1, icon1);
                $("#edu_bg").append(div1); // 追加新元素
                key1++;
            } else {
                FieldCount1++;
                insertInput();
            }
            $(".numText").html(key1);

        }
        $("body").on("click", ".deleteEduBg", function (e) { //user click on remove text
            if (key1 > 1) {
                $(this).parent('div').remove(); //remove text box
                key1--; //decrement textbox
            } else {
                alert("请至少填写1条教育背景信息！");
                return false;
            }
            $(".numText").html(key1);
        })

        form.on('submit(add)', function (data) {
            ll(data.field);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/admin/tag',
                data: data.field,
                type: 'post',
                dataType: 'JSON',
                success: function (res) {
                    if (res.code !== 200) {
                        layer.alert(res.text);
                        return false;
                    } else {
                        layer.msg("操作成功!", {time: 1000}, function () {
                            //重新加载父页面
                            window.location.reload();
                        });

                        // 获得frame索引
                        // var index = parent.layer.getFrameIndex(window.name);
                        //关闭当前frame
                        // parent.layer.close(index);
                    }
                },
                error: function () {
                    ll('error');
                }

            });
            return false;
            // setTimeout('window.location.reload()',1000);
            // layer.msg('OK', {icon:1,time:1000},function(){
            //     setTimeout('window.location.reload()',1000);
            // });
        });

    });
</script>
@endsection
