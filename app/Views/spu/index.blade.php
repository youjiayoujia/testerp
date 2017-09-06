@extends('common.table')
@section('tableToolButtons')
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="glyphicon glyphicon-filter"></i> @if($chose_status){{$chose_status}}（{{$chose_num}}）@else查询当前状态@endif
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            @foreach(config('spu.status') as $key=>$value)
                <li><a href="{{ DataList::filtersEncode(['status','=',$key]) }}">{{$value}}（{{$num_arr[$key]}}）</a></li>
            @endforeach
        </ul>
    </div>
@stop{{-- 工具按钮 --}}
@section('tableHeader')
    <th width=20><input type="checkbox" isCheck="true" id="checkall" onclick="quanxuan()"> </th>
    <th class="sort" data-field="id">ID</th>
    <th>名称</th>
    <th>采购员</th>
    <th>编辑</th>
    <th>美工</th>
    <th>开发</th>
    <th>当前进度</th>
    <th>备注</th>
    <th class="sort" data-field="updated_at">用户操作时间</th>
    <th>操作</th>
@stop

@section('tableBody')

    @foreach($data as $spu)
        <tr>
            <td><input type="checkbox" name="tribute_id" value="{{$spu->id}}"></td>
            <td>{{ $spu->id }}</td>
            <td>{{ $spu->spu }}</td>
            <td>{{ $spu->Purchase?$spu->Purchase->name:'' }}</td>
            <td>{{ $spu->editUser?$spu->editUser->name:'' }}</td>
            <td>{{ $spu->imageEdit?$spu->imageEdit->name:'' }}</td>
            <td>{{ $spu->Developer?$spu->Developer->name:'' }}</td>
            <td>{{ $spu->status?config('spu.status')[$spu->status]:'' }}</td>
            <td>{{ $spu->remark }}</td>
            <td>{{ $spu->updated_at }}</td>
            <td>
                <a href="{{ route('createSpuImage', ['spu_id'=>$spu->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-picture"></span> 编辑图片
                </a>
                <a href="{{ route('spu.MultiEdit', ['id'=>$spu->id]) }}" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 英文资料
                </a>
                <a data-toggle="modal" data-target="#switch_purchase_{{$spu->id}}" title="备注" class="btn btn-warning btn-xs" id="find_shipment">
                    <span class="glyphicon glyphicon-envelope"></span>备注
                </a>
                <button class="btn btn-primary btn-xs dialog"
                        data-toggle="modal"
                        data-target="#dialog" data-table="{{ $spu->table }}" data-id="{{$spu->id}}" title='日志'>
                <span class="glyphicon glyphicon-road"></span>
                <!-- <a href="" class="btn btn-info btn-xs">
                    <span class="glyphicon glyphicon-road"></span> 日志
                </a> -->
            </td>
        </tr>

        <!-- 模态框（Modal） -->
        <form action="{{route('saveRemark')}}" method="get">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="modal fade" id="switch_purchase_{{$spu->id}}"  role="dialog" 
               aria-labelledby="myModalLabel" aria-hidden="true">
               <div class="modal-dialog">
                  <div class="modal-content">
                     <div class="modal-header">
                        <button type="button" class="close" 
                           data-dismiss="modal" aria-hidden="true">
                              &times;
                        </button>
                        <h4 class="modal-title" id="myModalLabel">
                           添加备注
                        </h4>
                     </div> 
                     <div class="modal-body">
                        <textarea rows="3" cols="80" name='remark'></textarea>
                         
                     </div> 
                                    
                     <div class="modal-footer">
                        <button type="button" class="btn btn-default" 
                           data-dismiss="modal">关闭
                        </button>
                        <button type="submit" class="btn btn-primary" name='spu_id' value='{{$spu->id}}'>
                           提交
                        </button>
                     </div>
                  </div>
            </div>
            </div>
        </form>
        <!-- 模态框结束（Modal） -->

    @endforeach

    @section('doAction')
        <div class="row">
            <div class="col-lg-12">
                <button class="doAction" value="edit">批量已建SKU维护资料</button>
                <button class="doAction" value="image_edit">批量已编辑</button>
                <button class="doAction" value="image_examine">批量已制图</button>
                <button class="doAction" value="quality">批量已审图</button>
                <button class="doAction" value="final_examine">批量质检</button>
                <button class="doAction" value="pass">批量 终审</button>
                <?php $condition = request()->input('filters')?explode('.',request()->input('filters'))[2]:''; ?>
                <?php if($condition=='image_edit'||$condition=='edit'||$condition=='image_examine'||$condition=='final_examine'){ ?>
                <button class="actionBack" value="{{$condition}}">批量退回</button>
                <?php } ?>               
                <button type="button" class="dispatch"  value="purchase">批量转采购</button>
                <select>
                    <option>==采购==</option>
                    @foreach($users as $user)
                        
                        <option value='{{$user->id}}'>{{$user->name}}</option>
                    @endforeach
                </select>

                <button type="button" class="dispatch"  value="image_edit">批量转美工</button>
                <select class="select">
                    <option>==美工==</option>
                    @foreach($users as $user)
                        <option value='{{$user->id}}'>{{$user->name}}</option>
                    @endforeach
                </select>

                <button class='dispatch' value="edit_user">批量转编辑</button>
                <select>
                    <option>==编辑==</option>
                    @foreach($users as $user)
                    
                        <option value='{{$user->id}}'>{{$user->name}}</option>
                    @endforeach
                </select>

                <button class='dispatch' value="developer">批量转开发</button>
                <select>
                    <option>==开发==</option>
                    @foreach($users as $user)
                        <option value='{{$user->id}}'>{{$user->name}}</option>
                    @endforeach
                </select>

            </div>
        </div>
    @stop
    <br>
@stop

@section('childJs')
    <script type="text/javascript">
        //全选
        function quanxuan() {
            var collid = document.getElementById("checkall");
            var coll = document.getElementsByName("tribute_id");
            if (collid.checked) {
                for (var i = 0; i < coll.length; i++)
                    coll[i].checked = true;
            } else {
                for (var i = 0; i < coll.length; i++)
                    coll[i].checked = false;
            }
        }

        $('.dispatch').click(function () {
            //console.log($(this).next().find("option:selected").val());
            var user_id = $(this).next().find("option:selected").val();
            var action = $(this).val();
            var url = "{{route('dispatchUser')}}";

            var checkbox = document.getElementsByName("tribute_id");
            var spu_ids = "";
            for (var i = 0; i < checkbox.length; i++) {
                if (!checkbox[i].checked)continue;
                spu_ids += checkbox[i].value + ",";
            }
            spu_ids = spu_ids.substr(0, (spu_ids.length) - 1);
            $.ajax({
                url: url,
                data: {user_id: user_id, action: action,spu_ids:spu_ids},
                dataType: 'json',
                type: 'get',
                success: function (result) {
                    window.location.reload();
                }
            })
        });

        $('.doAction').click(function () {
            //console.log($(this).next().find("option:selected").val());
            //var user_id = $(this).next().find("option:selected").val();
            var action = $(this).val();
            var url = "{{route('doAction')}}";

            var checkbox = document.getElementsByName("tribute_id");
            var spu_ids = "";
            for (var i = 0; i < checkbox.length; i++) {
                if (!checkbox[i].checked)continue;
                spu_ids += checkbox[i].value + ",";
            }
            spu_ids = spu_ids.substr(0, (spu_ids.length) - 1);
            $.ajax({
                url: url,
                data: {action: action,spu_ids:spu_ids},
                dataType: 'json',
                type: 'get',
                success: function (result) {
                    window.location.reload();
                }
            })
        });

        $('.actionBack').click(function () {
            var action = $(this).val();
            var url = "{{route('actionBack')}}";

            var checkbox = document.getElementsByName("tribute_id");
            var spu_ids = "";
            for (var i = 0; i < checkbox.length; i++) {
                if (!checkbox[i].checked)continue;
                spu_ids += checkbox[i].value + ",";
            }
            spu_ids = spu_ids.substr(0, (spu_ids.length) - 1);
            $.ajax({
                url: url,
                data: {action: action,spu_ids:spu_ids},
                dataType: 'json',
                type: 'get',
                success: function (result) {
                    window.location.reload();
                }
            })
        });
    </script>
@stop
