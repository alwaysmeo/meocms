<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Permissions;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PermissionsController extends Controller
{
	protected function buildTree(array $elements, $parentId = null): array
	{
		$branch = array();
		foreach ($elements as $element) {
			$index = 0;
			if ($element['parent_id'] === $parentId) {
				$children = $this->buildTree($elements, $element['id']);
				if ($children) $element['children'] = $children;
				$branch[] = $element;
			}
		}
		return $branch;
	}

	public function list(Request $request): Response
	{
		$list = Permissions::query()->get();
		return $this->success($this->buildTree($list->toArray()));
	}
}
