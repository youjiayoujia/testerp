@extends('common.form')
@section('formAction') {{ route('catalog.store') }} @stop
@section('formBody')
    <div class="form-group">
        <label for="c_name">分类中文名称</label>
        <input class="form-control" id="c_name" placeholder="中文名称" name='c_name' value="{{old('c_name')}}">
    </div>
    <div class="form-group">
        <label for="name">分类英文名称</label>
        <input class="form-control" id="name" placeholder="English Name" name='name' value="{{old('name')}}">
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-lg-6">
                <label for="name">前缀</label>
                <input class="form-control" id="code" placeholder="前缀" name='code' value="{{old('code')}}">
            </div>
            <div class="col-lg-6">
                <label for="name">分类</label>
                <select class="form-control" name="catalog_category_id">
                    @foreach($catalogCategory as $item)
                        <option value="{{$item->id}}">{{$item->cn_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>


    </div>
    <div class="panel panel-info">
        <div class="panel-heading">Set属性(影响产品图片的属性 例如:产品颜色)</div>
        <div class="panel-body setfirst">

        </div>
        <div class="panel-footer">
            <div class="create" id="setadd"><i class="glyphicon glyphicon-plus"></i></div>
        </div>
    </div>
    <div class="panel panel-info">
        <div class="panel-heading">Variation属性(不影响产品图片但影响销售的属性 例如:产品尺寸)</div>
        <div class="panel-body variationfirst">

        </div>
        <div class="panel-footer">
            <div class="create" id="attradd"><i class="glyphicon glyphicon-plus"></i></div>
        </div>
    </div>
    <div class="panel panel-info">
        <div class="panel-heading">Feature属性(产品附加的属性 例如:是否能水洗,是否有弹性等)</div>
        <div class="panel-body featurefirst">

        </div>
        <div class="panel-footer">
            <div class="create" id="featureadd"><i class="glyphicon glyphicon-plus"></i></div>
        </div>
    </div>
    <input type='hidden' value='0' id='setnum' name="setnum">
    <input type='hidden' value='0' id='attrnum' name="attrnum">
    <input type='hidden' value='0' id='featurenum' name="featurenum">
    <div class="panel panel-warning">
        <div class="panel-heading">渠道费率</div>
        <div class="panel-body">
            <div class="form-group">
                @foreach($CatalogRatesModel as $channel)
                    <div class="row">
                        <div class="col-md-4">
                            <label for="size">渠道</label>
                            <input class="form-control" disabled="disabled" placeholder="渠道" value="{{$channel->name}}">
                        </div>
                        <div class="col-md-4">
                            <label for="size">固定费</label>
                            <input class="form-control" placeholder="固定费" name='channel[flat][{{$channel->id}}]' value="">
                        </div>
                        <div class="col-md-4">
                            <label for="size">费率</label>
                            <input class="form-control" placeholder="费率" name='channel[name][{{$channel->id}}]' value="">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@stop

@section('pageJs')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.5.3/js/bootstrapValidator.js"></script>
    <script type="text/javascript">
        {{-- 删除属性列  --}}
        $(document).on('click', '.delete-row', function () {
            var o = $(this).parent();
            o.remove();
        });

        {{-- 删除属性行  --}}
        $(document).on('click', '.delete-column', function () {
            var o = $(this).parent();
            o.remove();
        });

        {{-- feature类型选择  --}}
        $(document).on('change', '.featype', function () {
            var num = $(this).attr('name');
            num = num.substr(9, 1);
            var type = $(this).val();
            if (type == 1) {
                $(".fhide_" + num).css("display", "none");
                $(".dhide_" + num).css("display", "none");
                $(".fhides_" + num).remove();
            } else {
                $(".fhide_" + num).css("display", "inline");
            }
        });

        {{-- 添加set属性值  --}}
        $(document).on('click', '.setsvalues', function () {
            var aa = $(this).prev().prev().find('input').attr('name');
            var num = aa.substr(5, 1);
            num = parseInt(num);
            $(this).next().css("display", "inline");
            $(this).prev().after("<div class='form-group ajaxinput'><input type='text' class='form-control'  placeholder='属性值' name='sets[" + num + "][value][name][][name]'><button type='button' class='btn btn-outline btn-danger delete-column ajaxinput'><i class='glyphicon glyphicon-remove'></i></div>");
        });

        {{-- 添加set属性行  --}}
        $(document).on('click', '#setadd', function () {
            var aa = $("input[name^='sets[']:last").attr('name');
            if (aa == undefined) {
                $(".setfirst").html("<div class='form-group form-inline sets' id='setkey_0'> 属性名：<div class='form-group'><input class='form-control'  placeholder='属性名'  name='sets[0][name]' ></div> 属性值：<div class='form-group' title='cannotremove'><input type='text' class='form-control'  placeholder='属性值' name='sets[0][value][name][][name]' ></div><button type='button' class='btn btn-primary setsvalues ajaxinput'>添加</button><button type='button' class='btn btn-outline btn-danger delete-row' style='float:right'><i class='glyphicon glyphicon-trash '></i></button></div></div>");
                return;
            }
            var num = aa.substr(5, 1);
            num = parseInt(num);
            num = num + 1;
            $("#setnum").val(num);
            $(".sets").last().after("<div class='form-group form-inline sets '>属性名：<div class='form-group'><input class='form-control'  placeholder='属性名'  name='sets[" + num + "][name]' ></div> 属性值：<div class='form-group'><input type='text' class='form-control'  placeholder='属性值' name='sets[" + num + "][value][name][][name]'></div><button type='button' class='btn btn-primary setsvalues ajaxinput'>添加</button><button type='button' class='btn btn-outline btn-danger delete-row' style='float:right'><i class='glyphicon glyphicon-trash '></i></button></div>");
        });

        {{-- 添加variation属性值  --}}
        $(document).on('click', '.attrvalues', function () {
            var aa = $(this).prev().prev().find('input').attr('name');
            var num = aa.substr(11, 1);
            num = parseInt(num);
            $(this).next().css("display", "inline");
            $(this).prev().after("<div class='form-group ajaxinput'><input type='text' class='form-control'  placeholder='属性值' name='variations[" + num + "][value][name][][name]'><button type='button' class='btn btn-outline btn-danger delete-column ajaxinput'><i class='glyphicon glyphicon-remove'></i></div>");
        });

        {{-- 添加variation属性行  --}}
        $(document).on('click', '#attradd', function () {
            var aa = $("input[name^='variations[']:last").attr('name');
            if (aa == undefined) {
                $(".variationfirst").html("<div class='form-group form-inline attrs' id='attrkey_0'>属性名：<div class='form-group'><input class='form-control'  placeholder='属性名'  name='variations[0][name]' ></div> 属性值：<div class='form-group' title='cannotremove'><input type='text' class='form-control'  placeholder='属性值'' name='variations[0][value][name][][name]' ></div><button type='button' class='btn btn-primary attrvalues ajaxinput' >添加</button><button type='button' class='btn btn-outline btn-danger delete-row' style='float:right'><i class='glyphicon glyphicon-trash '></i></button></div>");
                return;
            }
            var num = aa.substr(11, 1);
            num = parseInt(num);
            num = num + 1;
            $("#attrnum").val(num);
            $(".attrs").last().after("<div class='form-group form-inline attrs'>属性名：<div class='form-group'><input class='form-control'  placeholder='属性名'  name='variations[" + num + "][name]' ></div> 属性值：<div class='form-group'><input type='text' class='form-control'  placeholder='属性值' name='variations[" + num + "][value][name][][name]'></div><button type='button' class='btn btn-primary attrvalues ajaxinput'>添加</button><button type='button' class='btn btn-outline btn-danger delete-row' style='float:right'><i class='glyphicon glyphicon-trash '></i></button></div>");
        });

        {{-- 添加feature属性列  --}}
        $(document).on('click', '.featurevalues', function () {
            var aa = $(this).prev().find('input').attr('name');
            var num = aa.substr(9, 1);
            num = parseInt(num);
            $(this).next().css("display", "inline");
            $(this).prev().after("<div class='form-group fhide_" + num + " fhides_" + num + " ajaxinput'><input type='text' class='form-control'  placeholder='属性值' name='features[" + num + "][value][name][][name]'><button type='button' class='btn btn-outline btn-danger delete-column ajaxinput'><i class='glyphicon glyphicon-remove'></i></div>");
        });

        {{-- 添加feature属性行  --}}
        $(document).on('click', '#featureadd', function () {
            var aa = $("input[name^='features[']:last").attr('name');
            if (aa == undefined) {
                $(".featurefirst").html("<div class='form-group form-inline features' id='featurekey_0'>类型：<select name='features[0][type]' class='form-control featype'><option value='1'>文本</option><option value='2'>单选</option><option value='3'>多选</option></select>属性名：<div class='form-group'><input class='form-control'  placeholder='属性名''  name='features[0][name]' ></div><div class='form-group fhide_0' title='cannotremove' style='display:none'>  属性值：<input type='text' class='form-control'  placeholder='属性值' name='features[0][value][name][][name]' ></div><button type='button' class='btn btn-primary featurevalues ajaxinput fhide_0' style='display:none'>添加</button><button type='button' class='btn btn-outline btn-danger delete-row' style='float:right'><i class='glyphicon glyphicon-trash '></i></button></div>");
                return;
            }
            var num = aa.substr(9, 1);
            num = parseInt(num);
            num = num + 1;
            $("#featurenum").val(num);
            $(".features").last().after("<div class='form-group form-inline features'>类型：<select name='features[" + num + "][type]' class='form-control featype'><option value='1'>文本</option><option value='2'>单选</option><option value='3'>多选</option></select>属性名：<div class='form-group '><input class='form-control'  placeholder='属性名'  name='features[" + num + "][name]' ></div><div class='form-group fhide_" + num + " ' style='display:none' title='cannotremove'> 属性值：<input type='text' class='form-control'  placeholder='属性值' name='features[" + num + "][value][name][][name]'></div><button type='button' class='btn btn-primary featurevalues ajaxinput fhide_" + num + "' style='display:none'>添加</button><button type='button' class='btn btn-outline btn-danger delete-row' style='float:right'><i class='glyphicon glyphicon-trash '></i></button></div></div>");
        });


        $(document).ready(function () {
            $('form').bootstrapValidator({
                message: 'This value is not valid',
                feedbackIcons: {
                    valid: 'glyphicon glyphicon-ok',
                    invalid: 'glyphicon glyphicon-remove',
                    validating: 'glyphicon glyphicon-refresh'
                },
                fields: {
                    name: {
                        message: 'The name is not valid',
                        validators: {
                            notEmpty: {
                                message: '品类英文名不能为空'
                            },
                        }
                    },

                    c_name: {
                        message: 'The c_name is not valid',
                        validators: {
                            notEmpty: {
                                message: '品类中文名不能为空'
                            },
                        }
                    },

                    email: {
                        validators: {
                            notEmpty: {
                                message: 'The email is required and cannot be empty'
                            },
                            emailAddress: {
                                message: 'The input is not a valid email address'
                            }
                        }
                    }

                }
            });

            $("#name").blur(function () {
                var catalog_name = $(this).val();
                var url = "{{route('checkName')}}";
                $.ajax({
                    url: url,
                    data: {catalog_name: catalog_name},
                    dataType: 'json',
                    type: 'get',
                    success: function (result) {
                        if (result)alert("该分类名已经存在");
                    }
                })
            })
        });

    </script>
@stop