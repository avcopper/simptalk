<?php

namespace Models;

use System\Db;

class Friend extends Model
{
    protected static $table = 'friends';

    public static function getAll(int $id, bool $active = true, $object = true)
    {
        $activity =
            !empty($active) ? 'AND u.active IS NOT NULL AND u.blocked IS NULL AND ug.active IS NOT NULL' : '';

        $params = [':id' => $id];
        $sql = "
            SELECT 
                u.id, u.last_name, u.name, u.second_name,
                f.status_id, 
                s.name status_name, s.created request_date, 1 request_person 
            FROM friends f 
            LEFT JOIN users u ON u.id = f.user2_id 
            LEFT JOIN statuses s ON s.id = f.status_id 
            LEFT JOIN user_groups ug ON ug.id = u.group_id 
            WHERE user1_id = :id {$activity} 
            UNION ALL 
            SELECT 
                u.id, u.last_name, u.name, u.second_name,
                f.status_id, 
                s.name status_name, s.created request_date, 2 request_person 
            FROM friends f 
            LEFT JOIN users u ON u.id = f.user1_id 
            LEFT JOIN statuses s ON s.id = f.status_id 
            LEFT JOIN user_groups ug ON ug.id = u.group_id 
            WHERE user2_id = :id {$activity}";

        $db = new Db();
        $data = $db->query($sql, $params, $object ? static::class : null);
        return $data ?? false;
    }
}
