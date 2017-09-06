@extends('common.table')
@section('tableHeader')
    <th><input type='checkbox' name='select_all' class='select_all'></th>
    <th class="sort" data-field="id">ID</th>
    <th>平台</th>
    <th>账号</th>   
    <th>模板名称</th>
    <th>操作</th> 
@stop
@section('tableBody')
    @foreach ($data as $item)
    <tr>
         <td><input type='checkbox' name='single[]' class='single' value="{{$item->id}}"></td>
         <td>{{$item->id}}</td>
         <td>
              @if ($item->plat == 6)
                                                 速卖通
              @endif
         </td>
         <td>{{$item->account->alias}}</td>
         <td>{{$item->name}}</td>
         <td>
            <a href="{{ route('smtAfterSale.edit', ['id'=>$item->id]) }}" class="btn btn-warning btn-xs">
                    <span class="glyphicon glyphicon-pencil"></span> 编辑
            </a>
            <a href="javascript:" class="btn btn-danger btn-xs delete_item"
               data-id="{{ $item->id }}"
               data-url="{{ route('smtAfterSale.destroy', ['id' => $item->id]) }}">
                <span class="glyphicon glyphicon-trash"></span> 删除                    
            </a>      
         </td>
    </tr>
    @endforeach
@stop