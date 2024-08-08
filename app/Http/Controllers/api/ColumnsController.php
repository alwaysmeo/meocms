<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Columns;
use App\Services\Common;
use App\Services\Mapping;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ColumnsController extends Controller
{
    private Common $common;

    private array $code;

    public function __construct()
    {
        $this->common = new Common;
        $this->code = Mapping::$code;
    }

    /**
     * 获取栏目列表
     *
     * @group 栏目 - Columns
     */
    public function list(Request $request): Response
    {
        $req = $request->only(['organize_id']);
        $validator = Validator::make($req, ['organize_id' => 'required|integer']);
        if (! $validator->passes()) {
            return $this->fail(null, $validator->errors()->first(), 5000);
        }
        $list = Columns::query()
            ->select('id', 'organize_id', 'parent_id', 'parent_id', 'name', 'description', 'path', 'cover', 'external_link', 'level', 'show', 'order')
            ->whereNull('deleted_at')
            ->where('organize_id', $req['organize_id'])
            ->orderByRaw('ISNULL(`ORDER`), `ORDER` ASC')
			->orderBy('id')
            ->get();

        return $this->success($this->common->buildTree($list->toArray()));
    }
}
