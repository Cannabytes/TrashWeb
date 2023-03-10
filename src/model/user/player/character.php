<?php
/**
 * Created by Logan22
 * Github -> https://github.com/Cannabytes/TrashWeb
 * Date: 26.08.2022 / 20:33:18
 */

namespace Ofey\Logan22\model\user\player;

use Ofey\Logan22\component\alert\board;
use Ofey\Logan22\component\base\base;
use Ofey\Logan22\component\cache\cache;
use Ofey\Logan22\component\cache\dir;
use Ofey\Logan22\component\image\crest;
use Ofey\Logan22\component\lang\lang;
use Ofey\Logan22\component\redirect;
use Ofey\Logan22\model\server\server;

class character {

    public static function all_characters($login, $server_id) {
        static $server_info_cache = [];
        if(!isset($server_info_cache[$server_id])) {
            $server_info_cache[$server_id] = server::get_server_info($server_id);
            if(!$server_info_cache[$server_id]) {
                redirect::location("/main");
            }
        }

        $cache = cache::read(dir::characters->show_dynamic($server_id, $login), second: 60);
        if($cache)
            return $cache;

        $account_players = player_account::show_all_account_player($server_id);
        if(!in_array($login, array_column($account_players, 'login'))) {
            redirect::location('/main');
        }

        $base = base::get_sql_source($server_info_cache[$server_id]['collection_sql_base_name'], "account_players");
        $players = player_account::extracted($base, $server_info_cache[$server_id], [$login]);
        $players = $players->fetchAll();
        crest::conversion($players);

        cache::save(dir::characters->show_dynamic($server_id, $login), $players);

        return $players;
    }

    public static function get_characters($login, $server_id) {
        $info = server::get_server_info($server_id);
        if(!$info) {
            return false;
        }
        $base = base::get_sql_source($info['collection_sql_base_name'], "account_players");
        $players = player_account::extracted($base, $info, [$login]);
        $players = $players->fetchAll();
        crest::conversion($players);
        return $players;
    }

    public static function get_player($char_name, $server_id) {
        $server_info = server::get_server_info($server_id);
        if(!$server_info) {
            board::notice(false, lang::get_phrase(150));
        }
        $reQuest = server::db_info_id($server_info['db_id']);
        $my_chars = self::player($reQuest, [$char_name]);
        $user_characters = $my_chars->fetch();
        crest::conversion($user_characters);
        return $user_characters;
    }

    private static function player($info, $prepare) {
        $base = base::get_sql_source($info['collection_sql_base_name'], "is_characters_name");
        return player_account::extracted($base, $info, $prepare);
    }

    public static function get_items($login, $server_id) {
        $server_info = server::get_server_info($server_id);
        if(!$server_info) {
            board::notice(false, lang::get_phrase(150));
        }
        $reQuest = server::db_info_id($server_info['db_id']);
        $items = self::items($reQuest, [$login]);
        return $items->fetchAll();
    }

    private static function items($info, $prepare) {
        $base = base::get_sql_source($info['collection_sql_base_name'], "all_player_items");
        return player_account::extracted($base, $info, $prepare);
    }

    public static function get_subclasses($char_id, $server_id) {
        $server_info = server::get_server_info($server_id);
        if(!$server_info) {
            board::notice(false, lang::get_phrase(150));
        }
        $reQuest = server::db_info_id($server_info['db_id']);
        $subclasses = self::subclasses($reQuest, [$char_id]);
        return $subclasses->fetchAll();
    }

    private static function subclasses($info, $prepare) {
        $base = base::get_sql_source($info['collection_sql_base_name'], "player_subclasses");
        return player_account::extracted($base, $info, $prepare);
    }
}