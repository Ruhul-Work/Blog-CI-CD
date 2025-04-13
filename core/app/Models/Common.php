<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class Common extends Model
{
    use HasFactory;


    public static function getPossibleEnumValues($table, $field)
    {
        $sql = "SHOW COLUMNS FROM $table WHERE Field = ?";
        $type = DB::selectOne($sql, [$field])->Type;
        preg_match('/^enum\((.*)\)$/', $type, $matches);
        $enum = array();
        foreach (explode(',', $matches[1]) as $value) {
            $v = trim($value, "'");
            $enum = Arr::add($enum, $v, $v);
        }
        return $enum;
    }



}
