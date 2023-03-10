<?php
/**
 * Created by Logan22
 * Github -> https://github.com/Cannabytes/TrashWeb
 * Date: 31.08.2022 / 16:56:36
 */

namespace Ofey\Logan22\controller\statistic;

use Ofey\Logan22\component\chronicle\race_class;
use Ofey\Logan22\component\lang\lang;
use Ofey\Logan22\component\redirect;
use Ofey\Logan22\controller\page\error;
use Ofey\Logan22\controller\user\auth\auth;
use Ofey\Logan22\model\db\sql;
use Ofey\Logan22\model\statistic\statistic as statistic_model;
use Ofey\Logan22\template\tpl;

class statistic {

    static public function pvp_ajax() {
        echo json_encode(statistic_model::get_pvp());
    }

    static public function pk_ajax() {
        echo json_encode(statistic_model::get_pk());
    }

    static public function other_ajax() {
        echo json_encode(statistic_model::top_counter());
    }

    static public function clan_ajax() {
        echo json_encode(statistic_model::get_clan());
    }

    static public function player_ajax() {
        echo json_encode(statistic_model::get_players_online_time());
    }

    static public function block_ajax() {
        echo json_encode(statistic_model::get_players_block());
    }

    static public function heroes_ajax() {
        echo json_encode(statistic_model::get_heroes());
    }

    static public function castle_ajax() {
        echo json_encode(statistic_model::get_castle());
    }

    static public function block(): void {
        tpl::display("statistic/block.html");
    }

    static public function heroes(): void {
        tpl::display("statistic/heroes.html");
    }

    static public function pvp(): void {
        tpl::display("statistic/pvp.html");
    }

    static public function pk(): void {
        tpl::display("statistic/pk.html");
    }

    static public function online_time(): void {
        tpl::display("statistic/online_time.html");
    }

    static public function clan(): void {
        tpl::display("statistic/clan.html");
    }

    static public function clan_info($clan_name): void {
        $clan = statistic_model::get_clan_all_info($clan_name);
        tpl::addVar("clan_info", $clan['clan_info']);
        tpl::addVar("clan_players", $clan['clan_players']);
        tpl::addVar("clan_skills", $clan['clan_skills']);
        tpl::display("/statistic/clan_data.html");
    }

    static public function castle(): void {
        tpl::display("statistic/castle.html");
    }

    static public function char_info($char_name = null): void {
        $get_player_info = statistic_model::get_player_info($char_name);
        if(!$get_player_info) {
            //todo: ???????? ?????? ??????????????????, ?????????????? ???????????????? ????????????, ?????? ?????????????????????? ?????????? ????????????????
            redirect::location("/main");
        }
        $player_account = sql::run("SELECT `email`, `show_characters_info` FROM player_accounts WHERE player_accounts.login = ?", [$get_player_info['account_name'] ])->fetch();
        if($player_account){
            if(!$player_account['show_characters_info'] AND
                \Ofey\Logan22\model\user\auth\auth::get_email()!=$player_account['email'] AND
                \Ofey\Logan22\model\user\auth\auth::get_access_level()!="admin"
            ) {
                tpl::addVar("player", $get_player_info);
                tpl::display("statistic/char_denied_access.html");
            }
        }

        $inventory = statistic_model::get_player_inventory_info($char_name, $get_player_info['player_id']);
        $sub_class = statistic_model::get_player_info_sub_class($char_name, $get_player_info['player_id']);
        tpl::addVar("player", $get_player_info);
        tpl::addVar("inventory", $inventory);
        tpl::addVar("sub_class", $sub_class);
        tpl::display("statistic/char.html");
    }

    /**
     * TODO: ???????????????????? ???????????????? ???????????????? ???????????? ???? ????????????????.
     * ?? ???????????? ???????????? ?????????? ?????????????????? ?????????? ???? "???????? ????????????" ?? ?????????? ??????????????.
     */
    static public function class($class_name): void {
        $class_id = race_class::get_id_class($class_name);
        if($class_id == null)
            error::error404("?????????????? ?????????? ???? ????????????");
        $class_name = race_class::get_class($class_id);
        tpl::addVar("class_name", $class_name);
        tpl::addVar("player_classes", statistic_model::statistic_class($class_name, [$class_id]));
        tpl::display("statistic/class.html");
    }
}