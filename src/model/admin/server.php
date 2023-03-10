<?php
/**
 * Created by Logan22
 * Github -> https://github.com/Cannabytes/TrashWeb
 * Date: 17.08.2022 / 19:21:01
 */

namespace Ofey\Logan22\model\admin;

use Ofey\Logan22\component\alert\board;
use Ofey\Logan22\component\lang\lang;
use Ofey\Logan22\model\db\sql;

class server {

    static private $data_list_server = null;

    static public function save_server() {
        $name_server = $_POST['name'];

        $version_client = $_POST['version_client'];

        $rate_exp = $_POST['rate_exp'];
        $rate_sp = $_POST['rate_sp'];
        $rate_adena = $_POST['rate_adena'];
        $rate_drop = $_POST['rate_drop'];
        $rate_spoil = $_POST['rate_spoil'];

        $date_start = $_POST['date_start'];
        $time_start = $_POST['time_start'];

        //Данные БД для логина
        $db_login_host = $_POST['db_login_host'];
        $db_login_user = $_POST['db_login_user'];
        $db_login_password = $_POST['db_login_password'];
        $db_login_name = $_POST['db_login_name'];
        //Данные БД для гейма
        $db_game_host = $_POST['db_game_host'];
        $db_game_user = $_POST['db_game_user'];
        $db_game_password = $_POST['db_game_password'];
        $db_game_name = $_POST['db_game_name'];

        if(!isset($_POST['sql_base_source'])) {
            board::notice(false, "Need select sql database server collection");
        }
        $sql_base_source = $_POST['sql_base_source'];

        $check_server_online = isset($_POST['check_server_online']) ?: 0;

        $check_loginserver_online_host = $_POST['check_loginserver_online_host'];
        $check_loginserver_online_port = $_POST['check_loginserver_online_port'];
        $check_gameserver_online_host = $_POST['check_gameserver_online_host'];
        $check_gameserver_online_port = $_POST['check_gameserver_online_port'];

        $ws_chat_enable = isset($_POST['chat_game_enabled']) ?: 0;

        $ws_ip_host = $_POST['chat_websocket_host'];
        $ws_admin_password = $_POST['chat_admin_password'];

        //TODO: Проверка на соединение с БД

        $sql = "INSERT INTO `server_list` (`name`, `rate_exp`, `rate_sp`, `rate_adena`, `rate_drop_item`, `rate_spoil`, `date_start_server`, `chronicle`, `login_host`, `login_user`, `login_password`, `login_name`, `game_host`, `game_user`, `game_password`, `game_name`, `collection_sql_base_name`, `check_server_online`,  `check_loginserver_online_host`, `check_loginserver_online_port`, `check_gameserver_online_host`, `check_gameserver_online_port`, `chat_game_enabled`, `chat_websocket_host`, `chat_admin_password`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $ok = sql::run($sql, [
            $name_server,
            $rate_exp,
            $rate_sp,
            $rate_adena,
            $rate_drop,
            $rate_spoil,
            "{$date_start} {$time_start}:00",
            $version_client,
            $db_login_host,
            $db_login_user,
            $db_login_password,
            $db_login_name,
            $db_game_host,
            $db_game_user,
            $db_game_password,
            $db_game_name,
            $sql_base_source,

            $check_server_online,
            $check_loginserver_online_host,
            $check_loginserver_online_port,
            $check_gameserver_online_host,
            $check_gameserver_online_port,

            $ws_chat_enable,
            $ws_ip_host,
            $ws_admin_password
        ], true);
        if(!$ok) {
            board::notice(false, 'Ошибка');
        }
        board::notice(true, 'Добавлено');
    }

    static public function update_server() {
        $server_id = $_POST['server_id'];

        $name_server = $_POST['name'];

        $version_client = $_POST['version_client'];

        $rate_exp = $_POST['rate_exp'];
        $rate_sp = $_POST['rate_sp'];
        $rate_adena = $_POST['rate_adena'];
        $rate_drop = $_POST['rate_drop'];
        $rate_spoil = $_POST['rate_spoil'];

        $date_start = $_POST['date_start'];
        $time_start = $_POST['time_start'];

        //Данные БД для логина
        $db_login_host = $_POST['db_login_host'];
        $db_login_user = $_POST['db_login_user'];
        $db_login_password = $_POST['db_login_password'];
        $db_login_name = $_POST['db_login_name'];
        //Данные БД для гейма
        $db_game_host = $_POST['db_game_host'];
        $db_game_user = $_POST['db_game_user'];
        $db_game_password = $_POST['db_game_password'];
        $db_game_name = $_POST['db_game_name'];

        $sql_base_source = $_POST['sql_base_source'];

        $check_server_online = isset($_POST['check_server_online']) ?: 0;
        $check_loginserver_online_host = $_POST['check_loginserver_online_host'];
        $check_loginserver_online_port = $_POST['check_loginserver_online_port'] ?: null;
        $check_gameserver_online_host = $_POST['check_gameserver_online_host'];
        $check_gameserver_online_port = $_POST['check_gameserver_online_port'] ?: null;

        $ws_chat_enable = isset($_POST['chat_game_enabled']) ?: 0;
        $ws_ip_host = $_POST['chat_websocket_host'];
        $ws_admin_password = $_POST['chat_admin_password'];
        //TODO: Проверка на соединение с БД

        $sql = "UPDATE `server_list` SET
                         `name` = ?,
                        `rate_exp` = ?,
                        `rate_sp` = ?,
                        `rate_adena` = ?,
                        `rate_drop_item` = ?,
                        `rate_spoil` = ?,
                        `date_start_server` = ?,
                        `chronicle` = ?,
                        `login_host` = ?,
                        `login_user` = ?,
                        `login_password` = ?,
                        `login_name` = ?,
                        `game_host` = ?,
                        `game_user` = ?,
                        `game_password` = ?,
                        `game_name` = ?,
                        `collection_sql_base_name` = ?,
                        `check_server_online` = ?,
                        `check_loginserver_online_host` = ?,
                        `check_loginserver_online_port` = ?,
                        `check_gameserver_online_host` = ?,
                        `check_gameserver_online_port` = ?,
                        `chat_game_enabled` = ?,
                        `chat_websocket_host` = ?,
                        `chat_admin_password` = ?
                         WHERE
                            `id` = ?";
        $ok = sql::run($sql, [
            $name_server,
            $rate_exp,
            $rate_sp,
            $rate_adena,
            $rate_drop,
            $rate_spoil,
            "{$date_start} {$time_start}:00",
            $version_client,
            $db_login_host,
            $db_login_user,
            $db_login_password,
            $db_login_name,
            $db_game_host,
            $db_game_user,
            $db_game_password,
            $db_game_name,
            $sql_base_source,

            $check_server_online,
            $check_loginserver_online_host,
            $check_loginserver_online_port,
            $check_gameserver_online_host,
            $check_gameserver_online_port,

            $ws_chat_enable,
            $ws_ip_host,
            $ws_admin_password,

            $server_id,
        ]);
        if(!$ok) {
            board::notice(false, 'Ошибка');
        }
        board::notice(true, 'Добавлено');
    }

    //Добавление описания
    static public function add_description() {
        $server_id = (int)$_POST['id'];
        $lang = ($_POST['lang']);
        $page_id = (int)($_POST['page_id']);
        sql::run("DELETE FROM `server_description` WHERE `server_id` = ? AND `lang` = ? ", [
            $server_id,
            $lang,
        ]);
        sql::run("INSERT INTO `server_description` (`server_id`, `lang`, `page_id`) VALUES (?, ?, ?)", [
            $server_id,
            $lang,
            $page_id,
        ]);
        board::notice(true, 'Добавлено');
    }

    public static function description_default() {
        $server_id = $_POST['server_id'];
        $page_id = $_POST['page_id'];
        $lang = $_POST['lang'];
        sql::run("UPDATE `server_description` SET `default` = 0 WHERE `server_id` = ?", [
            $server_id,
        ]);
        sql::run("UPDATE `server_description` SET `default` = 1 WHERE `server_id` = ? AND `lang` = ? AND `page_id` = ?", [
            $server_id,
            $lang,
            $page_id,
        ]);
    }

    public static function server_info($id) {
        return sql::run("SELECT * FROM `server_list` WHERE id=?;", [$id])->fetch();
    }

    public static function db_info_id($id) {
        return sql::run("SELECT id, login_host, login_name, login_password, login_user, game_host, game_name, game_password, game_user, collection_sql_base_name, password_encrypt FROM server_connect_db WHERE id = ?", [$id])->fetch();
    }

    static function get_server_description($server_id) {
        $sqlDescInfo = "SELECT
	                        server_id, 
	                        lang,
	                        page_id,
	                        default,
	                        date_create, 
	                        date_update
                        FROM
                            server_description
                        WHERE
                            server_description.server_id = ?";
        return sql::run($sqlDescInfo, [$server_id])->fetch();
    }

    public static function remove_server() {
        $server_id = $_POST['server_id'];
        $get_server_info = \Ofey\Logan22\model\server\server::get_server_info($server_id);
        sql::run("DELETE FROM `server_list` WHERE `id` = ?", [$server_id]);
        sql::run("DELETE FROM `server_connect_db` WHERE `id` = ?", [$get_server_info['db_id']]);
        sql::run("DELETE FROM `server_description` WHERE `server_id` = ?", [$server_id]);
        board::notice(true, lang::get_phrase(146));
    }
}