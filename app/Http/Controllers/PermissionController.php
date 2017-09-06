<?php

namespace App\Http\Controllers;

use App\Models\PermissionModel;

class PermissionController extends Controller
{
    public function __construct(PermissionModel $permission)
    {
        $this->model = $permission;
        $this->mainIndex = route('permission.index');
        $this->mainTitle = '权限';
        $this->viewPath = 'permission.';   
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        request()->flash();
        
        $data = request()->all();
        $data['route'] = 'App\\Http\\Controllers\\'.$data['controller'].'Controller@'.$data['action'];
        //$data['controller_name'] = $data['controller'];
        PermissionModel::create($data);
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '添加成功.'));
    }
}
