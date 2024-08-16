<?php

namespace App\Helpers\Validator\Rules;

/**
 * Проверка уникальности записи в БД
 */
class UniqueRule
{
    protected static array $message = [
        'empty_rule' => 'Rule for :attribute cannot be empty',
        'empty_table' => 'Rule for :attribute does not contain a field table',
        'empty_column' => 'Rule for :attribute does not contain a field column',
    ];

    public static function validate($value, $rule, $data = [])
    {
        // if (!$rule) {
        //     return self::$message['empty_rule'];
        // }

        // [$table, $column, $columnIgnore, $idIgnore] = explode(',', $rule);

        // if (!$table) {
        //     return self::$message['empty_table'];
        // }
        // if (!$column) {
        //     return self::$message['empty_column'];
        // }

        // if (!$value) {
        //     return true;
        // }

        // $where = [
        //     $column => $value
        // ];

        // if (!$idIgnore && $data[$columnIgnore]) {
        //     $idIgnore = $data[$columnIgnore];
        // }

        // if ($columnIgnore && $idIgnore) {
        //     $where['!' . $columnIgnore] = $idIgnore;
        // }

        // return !(bool) db()->selectRow($table, [$column], $where);
    }
}
