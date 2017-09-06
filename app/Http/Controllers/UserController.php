<?php
/**
 * Created by PhpStorm.
 * User: Vincent
 * Date: 16/1/27
 * Time: 上午9:19
 */

namespace App\Http\Controllers;

use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\PermissionModel;
use Gate;
use App\Models\WarehouseModel;

class UserController extends Controller
{
    public function __construct(UserModel $user,RoleModel $role,PermissionModel $permission)
    {
        $this->model = $user;
        $this->role = $role;
        $this->permission = $permission;
        $this->mainIndex = route('user.index');
        $this->mainTitle = '用户';
        $this->viewPath = 'user.';
    }

    public function create()
    {
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'roles' => RoleModel::all(),
            'warehouses' => WarehouseModel::where(['type' => 'local', 'is_available' => '1'])->get(),
        ];

        return view($this->viewPath . 'create', $response);
    }

    /**
     * 存储
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store()
    {
        request()->flash();
        $this->validate(request(), $this->model->rules('create'));
        $data = request()->all();
        $data['password'] = bcrypt($data['password']);

        $userModel = $this->model->create($data);
        //多对多插入
        if(array_key_exists('user_role', $data)){
            $userModel->role()->attach($data['user_role']);
        }
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '添加成功.'));
    }

    /**
     * 编辑
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }

        $select_role = [];
        foreach($model->role as $role){
            $select_role[] = $role->pivot->role_id;
        }
        $response = [
            'metas' => $this->metas(__FUNCTION__),
            'model' => $model,
            'roles' => RoleModel::all(),
            'select_role' => $select_role,
            'warehouses' => WarehouseModel::where(['type' => 'local', 'is_available' => '1'])->get(),
        ];
        return view($this->viewPath . 'edit', $response);
    }

    /**
     * 更新
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id)
    {
        $model = $this->model->find($id);
        if (!$model) {
            return redirect($this->mainIndex)->with('alert', $this->alert('danger', $this->mainTitle . '不存在.'));
        }
        request()->flash();
        $this->validate(request(), $this->model->rules('update', $id));
        $data = request()->all();
        
        if(array_key_exists('user_role', $data)){
            $model->role()->sync($data['user_role']);
        }
        
        if(strlen($data['password'])>=30){
            $data['password'] = $data['password'];
        }else{
            $data['password'] = bcrypt($data['password']);
        }
        $model->update($data);
        return redirect($this->mainIndex)->with('alert', $this->alert('success', '更新成功.'));
    }

    /**
     * 获取供应商信息
     */
    public function ajaxUser()
    {
        if(request()->ajax()) {
            $user = trim(request()->input('user'));
            $buf = UserModel::where('name', 'like', '%'.$user.'%')->get();
            $total = $buf->count();
            $arr = [];
            foreach($buf as $key => $value) {
                $arr[$key]['id'] = $value->id;
                $arr[$key]['text'] = $value->name;
            }
            if($total)
                return json_encode(['results' => $arr, 'total' => $total]);
            else
                return json_encode(false);
        }

        return json_encode(false);
    }

/*    public function per()
    {
        $role = $this->role->find(1);
        $permission = $this->permission->find(1);
        $user = $this->model->find(14);
        echo '<pre>';
        print_r($user->role->toArray());exit;
        print_r($permission->role->toArray());exit;

        print_r($role->permission->toArray());exit;
        
    }*/

    

}