<?php
namespace Models;

class UserBlock extends Model
{
    const INTERVAL_MINUTE = 60 * 60;
    const INTERVAL_HOUR = 60 * 60;
    const INTERVAL_DAY = 60 * 60 * 24;
    const INTERVAL_WEEK = 60 * 60 * 24 * 7;
    const INTERVAL_MONTH = 60 * 60 * 24 * 30;
    const INTERVAL_CENTURY = 60 * 60 * 24 * 365 * 100;

    protected static $db_table = 'mesigo.user_blocks';

    public $id;      // id блокировки
    public $user_id; // id пользователя
    public $expire;  // дата разблокировки
    public $reason;  // id сервиса
    public $created; // дата создания
    public $updated; // дата обновления
}
