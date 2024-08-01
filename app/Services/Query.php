<?php

namespace App\Services;

class Query
{
    /* 查询两个时间段内数据 $date: 2000-01-01,2002-02-02 */
    public function whereBetween($query, $field, $date)
    {
        return $query->whereBetween($field, [
            strtok($date, ','),
            str_replace(',', '', strstr($date, ',')),
        ]);
    }
}
