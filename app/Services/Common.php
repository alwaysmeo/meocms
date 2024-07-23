<?php

namespace App\Services;

class Common
{
	/* 获取ipv4地址 */
	public function ipv4($request): string
	{
		return $request->server('REMOTE_ADDR') ?? $request->ip() ?? '0.0.0.0';
	}

	/* 获取ipv6地址 */
	public function ipv6($request): string|null
	{
		return null;
	}

	/* 构建树形结构 */
	public function buildTree(array $elements, $parentId = null): array
	{
		$branch = array();
		foreach ($elements as $element) {
			if ($element['parent_id'] === $parentId) {
				$children = $this->buildTree($elements, $element['id']);
				if ($children) $element['children'] = $children;
				$branch[] = $element;
			}
		}
		return $branch;
	}
}
