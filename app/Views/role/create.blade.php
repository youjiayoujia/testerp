@extends('common.form')
@section('formAction') {{ route('role.store') }} @stop
@section('formBody')
    <div class="row">
        <div class="form-group col-lg-6">
            <label for="role_name" class='control-label'>角色</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="role_name" placeholder="角色" name='role_name' value="{{ old('role_name') }}">
        </div>
        <div class="form-group col-lg-6">
            <label for="role" class='control-label'>role</label>
            <small class="text-danger glyphicon glyphicon-asterisk"></small>
            <input type='text' class="form-control" id="role" placeholder="role" name='role' value="{{ old('role') }}">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-lg-12">
            <label for="permission_name" class='control-label'>选择角色对应的权限</label>
        </div>
    </div>
    
    <div class="row">
        <div class="form-group col-lg-12">
            <!-- @foreach($permissions as $permission)
                <label class="checkbox-inline">
                    <input type="checkbox" id="permission" value="{{$permission->id}}" name="role_permission[]"> {{$permission->action_name}}
                </label>
            @endforeach -->

            @foreach(config('permission.parent.name') as $key=>$name)
                <ul>
                    <li>
                        <label class="checkbox-inline">
                            <input type="checkbox" id="permission" value="" name=""> {{$name}}
                        </label>
                        <ul>
                            @foreach($permissions->where('parent_id',$key) as $child_name)
                            <li>
                                <label class="checkbox-inline">
                                    <input type="checkbox" id="permission" value="{{$child_name->id}}" name="role_permission[]"> {{$child_name->action_name}}
                                </label>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
            @endforeach
        </div>
    </div>
@stop
