<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Permissions;
use App\Services\Common;
use App\Services\Mapping;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class PermissionsController extends Controller
{
    private Common $common;

    private array $code;

    public function __construct()
    {
        $this->common = new Common;
        $this->code = Mapping::$code;
    }

    /**
     * 获取权限列表
     *
     * @group 权限 - Permissions
     */
    public function list(): Response
    {
        $list = Permissions::query()
            ->select('id', 'parent_id', 'code', 'name', 'description', 'icon', 'path', 'level', 'show', 'order', 'type')
            ->whereNull('deleted_at')
            ->orderByRaw('ISNULL(`ORDER`), `ORDER` ASC')
            ->get();

        return $this->success($this->common->buildTree($list->toArray()));
    }

    /**
     * 新增修改权限
     *
     * @group 权限 - Permissions
     */
    public function upsert(Request $request): Response
    {
        $req = $request->only(['id', 'parent_id', 'code', 'name', 'description', 'icon', 'path', 'level', 'type']);
        $validator = Validator::make($req, [
            'id' => 'integer',
            'parent_id' => 'integer',
            'code' => 'required|max:255',
            'name' => 'required|max:80',
            'description' => 'max:200',
            'icon' => 'max:100',
            'path' => 'max:255',
            'level' => 'required|integer',
            'type' => 'required|in:1,2',
        ]);
        if (! $validator->passes()) {
            return $this->fail(null, $validator->errors()->first(), 5000);
        }
        if (! isset($req['id'])) {
            $is_exist = Permissions::query()->where('code', $req['code'])->first();
            if ($is_exist) {
                return $this->fail(null, $this->code['5001'], 5001);
            }
        }
        Permissions::query()->updateOrCreate(['id' => $req['id'] ?? null], $req);

        return $this->success();
    }

    /**
     * 删除权限
     *
     * @group 权限 - Permissions
     */
    public function delete(Request $request): Response
    {
        $req = $request->only(['id']);
        $validator = Validator::make($req, ['id' => 'required|integer']);
        if (! $validator->passes()) {
            return $this->fail(null, $validator->errors()->first(), 5000);
        }
        /* 禁止删除【首页】权限 */
        if ($req['id'] === 1) {
            return $this->fail(null, $this->code['5002'], 5002);
        }
        Permissions::query()->where('id', $req['id'])->update([
            'deleted_at' => date('Y-m-d H:i:s'),
        ]);

        return $this->success();
    }

    /**
     * 修改权限启用状态
     *
     * @group 权限 - Permissions
     */
    public function changeShow(Request $request): Response
    {
        $req = $request->only(['id', 'show']);
        $validator = Validator::make($req, ['id' => 'required|integer', 'show' => 'required|in:0,1']);
        if (! $validator->passes()) {
            return $this->fail(null, $validator->errors()->first(), 5000);
        }
        Permissions::query()->where('id', $req['id'])->update([
            'show' => $req['show'],
        ]);

        return $this->success();
    }
}
