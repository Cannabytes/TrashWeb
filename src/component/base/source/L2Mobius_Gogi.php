<?php
/**
 * Коллекция запросов для сборки L2 Open High Five
 *
 * Все ответы запросов должны иметь одинаковое название стобцов
 * account/account_name -> login
 * ID персонажа -> player_id
 * Ник персонажа -> player_name
 * pvpkills -> pvp
 * pkkills -> pk
 * clanid -> clan_id
 * crest (клана) -> clan_crest
 * crest (альянса) -> alliance_crest
 * classId/class_id -> player_class_id
 * online_time AS time_in_game
 *
 *
 */

$options = [
    /**
     * Под какие хроники ориентирован сервер
     */
    'chronicle' => 'highFive',

    /**
     * Алгоритм хэширования пароля
     * В L2j используется два sha1 или whirlpool
     */
    'hash_encrypt_password' => 'sha1',

    /**
     * Нужно ли требовать выйти из игры для выдачи предмета персонажу
     * Если нет таблицы items_delayed для выдачи предметов игрокам, которые в онлайне
     * то рекомендую ВКЛЮЧИТЬ данный параметр
     */
    'need_logout_player_for_item_add' => true,

];

$collection = [
    'account_is_exist' => [
        'sql'  => 'SELECT `login`, `password` FROM `accounts` WHERE login=?;',
        'call' => 'login',
    ],

    'account_registration' => [
        'sql'  => 'INSERT INTO `accounts` (`login`, `password`, `email` ) VALUES (?, ?, ?);',
        'call' => 'login',
    ],

    'account_change_password' => [
        'sql'  => 'UPDATE `accounts` SET `password` = ? WHERE `login` = ?;',
        'call' => 'login',
    ],

    'accounts_email' => [
        'sql'  => 'SELECT login, password FROM accounts WHERE email = ?',
        'call' => 'login',
    ],

    'account_characters' => [
        'sql'  => 'SELECT
                        characters.account_name as login,
                        characters.obj_Id as player_id,
                        characters.char_name as player_name,
                        characters.sex,
                        characters.x,
                        characters.y,
                        characters.z,
                        characters.pvpkills as pvp,
                        characters.pkkills as pk,
                        characters.clanid as clan_id,
                        characters.`online`,
                        characters.onlinetime AS time_in_game,
                        clan_data.clan_name,
                        clan_data.crest AS clan_crest,
                        character_subclasses.class_id as player_class_id,
                        character_subclasses.`level`,
                        ally_data.crest AS alliance_crest 
                    FROM
                        characters
                        LEFT JOIN clan_data ON characters.clanid = clan_data.clan_id
                        LEFT JOIN character_subclasses ON characters.obj_Id = character_subclasses.char_obj_id
                        LEFT JOIN ally_data ON clan_data.ally_id = ally_data.ally_id 
                    WHERE
                        character_subclasses.active = 1 
                        AND characters.account_name = ?',
        'call' => 'game',
    ],

    'is_characters_name' => [
        'sql'  => 'SELECT
                     characters.account_name as login,
                     characters.obj_Id as player_id,
                     characters.char_name as player_name,
                     characters.sex,
                     characters.x,
                     characters.y,
                     characters.z,
                     characters.pvpkills as pvp,
                     characters.pkkills as pk,
                     characters.clanid as clan_id,
                     characters.`online`,
                     characters.onlinetime AS time_in_game,
                     clan_data.clan_name,
                     clan_data.crest AS clan_crest,
                     character_subclasses.class_id as player_class_id,
                     character_subclasses.`level`,
                     ally_data.crest AS alliance_crest 
                   FROM
                     characters
                     LEFT JOIN clan_data ON characters.clanid = clan_data.clan_id
                     LEFT JOIN character_subclasses ON characters.obj_Id = character_subclasses.char_obj_id
                     LEFT JOIN ally_data ON clan_data.ally_id = ally_data.ally_id 
                   WHERE
                       characters.char_name = ?',
        'call' => 'game',
    ],

    'statistic_top_pvp' => [
        'sql'  => 'SELECT
                        char_name as player_name,
                        pvpkills as pvp,
                        pkkills as pk,
                        clanid as clan_id,
                        onlinetime  AS time_in_game
                    FROM
                        characters 
                    ORDER BY
                        pvpkills DESC,
                        onlinetime ASC 
                        LIMIT 100',
        'call' => 'game',
    ],

    'statistic_top_pvp_TRANC' => [
        'sql'  => 'SELECT
                        characters.charId AS player_id,
                        characters.char_name AS player_name,
                        characters.sex,
                        characters.x,
                        characters.y,
                        characters.z,
                        characters.pvpkills AS pvp,
                        characters.pkkills AS pk,
                        characters.clanid AS clan_id,
                        characters.`online`,
                        characters.onlinetime AS time_in_game,
                        clan_data.clan_name,
                        characters.`level`,
                        crests.`data` AS clan_crest,
                       ( SELECT `data` FROM crests WHERE crest_id = clan_data.ally_crest_id LIMIT 1) AS alliance_crest 
                    FROM
                        characters
                        LEFT JOIN clan_data ON characters.clanid = clan_data.clan_id
                        LEFT JOIN crests   ON clan_data.crest_id = crests.crest_id
                     ORDER BY
                        pvpkills DESC 
                        LIMIT 100',
        'call' => 'game',
    ],

    'statistic_top_pk_TRANC' => [
        'sql'  => 'SELECT
                        characters.charId AS player_id,
                        characters.char_name AS player_name,
                        characters.sex,
                        characters.x,
                        characters.y,
                        characters.z,
                        characters.pvpkills AS pvp,
                        characters.pkkills AS pk,
                        characters.clanid AS clan_id,
                        characters.`online`,
                        characters.onlinetime AS time_in_game,
                        clan_data.clan_name,
                        characters.`level`,
                        crests.`data` AS clan_crest,
                       ( SELECT `data` FROM crests WHERE crest_id = clan_data.ally_crest_id LIMIT 1) AS alliance_crest 
                    FROM
                        characters
                        LEFT JOIN clan_data ON characters.clanid = clan_data.clan_id
                        LEFT JOIN crests   ON clan_data.crest_id = crests.crest_id
                     ORDER BY
                        pkkills DESC 
                        LIMIT 100',
        'call' => 'game',
    ],

    'statistic_top_clan_TRANC' => [
        'sql'  => 'SELECT
                        clan_data.clan_id, 
                        clan_data.clan_name, 
                        clan_data.clan_level, 
                        clan_data.reputation_score, 
                        clan_data.hasCastle AS castle_id, 
                        ( SELECT `data` FROM crests WHERE crest_id = clan_data.crest_id LIMIT 1 ) AS clan_crest, 
                        ( SELECT `data` FROM crests WHERE crest_id = clan_data.ally_crest_id LIMIT 1 ) AS alliance_crest, 
                        ( SELECT COUNT(*) FROM characters WHERE clanid = clan_data.clan_id ) AS clan_count_members, 
                        characters.char_name AS player_name, 
                        characters.pvpkills AS pvp, 
                        characters.pkkills AS pk, 
                        characters.`online`, 
                        characters.onlinetime AS time_in_game
                    FROM
                        clan_data
                        LEFT JOIN
                        characters
                        ON 
                            clan_data.leader_id = characters.charId
                    ORDER BY
                        clan_count_members DESC, 
                        clan_data.reputation_score DESC
                    LIMIT 100',
        'call' => 'game',
    ],

    'statistic_clan_data'    => [
        'sql'  => 'SELECT
                    clan_data.clan_id, 
                    clan_data.clan_name, 
                    clan_data.clan_level, 
                    clan_data.hasCastle AS castle_id, 
                    clan_data.hasFortress AS fortress_id, 
                    clan_data.hasHideout AS hideout_id, 
                    clan_data.ally_id, 
                    clan_data.leader_id, 
                    clan_data.crest as `clan_crest`, 
                    clan_data.reputation_score, 
                    characters.char_name AS `player_name_leader_clan`,
                    clanhall.id AS clanhall_id
                    FROM
                        clan_data
                        LEFT JOIN
                        characters
                        ON 
                            clan_data.leader_id = characters.obj_Id
                        LEFT JOIN
                        clanhall
                        ON 
                            clan_data.clan_id = clanhall.ownerId
                WHERE
                    clan_data.clan_name = ?',
        'call' => 'game',
    ],


    //Возвращаем клана скиллы и их уровни
    'statistic_clan_skills'  => [
        'sql'  => 'SELECT
                        clan_skills.skill_id, 
                        clan_skills.skill_level
                    FROM
                        clan_skills
                    WHERE
                        clan_skills.clan_id = ?',
        'call' => 'game',
    ],

    //Возвращаем список игроков клана
    'statistic_clan_players' => [
        'sql'  => 'SELECT
                        characters.char_name AS player_name, 
                        characters.pvpkills AS pvp, 
                        characters.pkkills AS pk, 
                        characters.title AS player_title, 
                        characters.`online`, 
                        characters.onlinetime AS time_in_game
                    FROM
                        characters
                    WHERE
                        characters.clanid = ?',
        'call' => 'game',
    ],

    'statistic_top_player_TRANC' => [
        'sql'  => 'SELECT characters.*, clan_data.* FROM characters LEFT JOIN clan_data ON characters.clanid = clan_data.clan_id ORDER BY onlinetime DESC LIMIT 100',
        'call' => 'game',
    ],

    'statistic_top_heroes_TRANC' => [
        'sql'  => 'SELECT
                        characters.char_name AS player_name, 
                        characters.pvpkills AS pvp, 
                        characters.pkkills AS pk, 
                        characters.`online`, 
                        characters.onlinetime AS time_in_game, 
                        ( SELECT `data` FROM crests WHERE crest_id = clan_data.crest_id LIMIT 1 ) AS clan_crest, 
                        ( SELECT `data` FROM crests WHERE crest_id = clan_data.ally_crest_id LIMIT 1 ) AS alliance_crest, 
                        clan_data.clan_name, 
                        characters.base_class AS class_id, 
                        characters.`level`
                    FROM
                        heroes
                        LEFT JOIN
                        characters
                        ON 
                            heroes.charId = characters.charId
                        INNER JOIN
                        clan_data
                        ON 
                            characters.clanid = clan_data.clan_id
                    ORDER BY
                        characters.onlinetime DESC',
        'call' => 'game',
    ],

    'statistic_top_castle_TRANC' => [
        'sql'  => 'SELECT
                        castle.id as castle_id,
                        castle.treasury,
                        cast(castle.siegeDate / 1000 as int) AS dataSiege,
                        clan_data.clan_name,
                        clan_data.clan_level,
                        clan_data.reputation_score,
                        characters.charId AS player_id,
                        characters.char_name AS player_name,
                        characters.pvpkills AS pvp,
                        characters.pkkills AS pk,
                        characters.`online`,
                        characters.onlinetime AS time_in_game,
                        ( SELECT `data` FROM crests WHERE crest_id = clan_data.crest_id LIMIT 1 ) AS clan_crest,
                        ( SELECT `data` FROM crests WHERE crest_id = clan_data.ally_crest_id LIMIT 1 ) AS alliance_crest,
                        ( SELECT COUNT(*) FROM characters WHERE clanid = clan_data.clan_id ) AS clan_count_members 
                    FROM
                        castle
                        LEFT JOIN clan_data ON castle.id = clan_data.hasCastle
                        LEFT JOIN characters ON clan_data.leader_id = characters.charId 
                    ORDER BY
                        clan_count_members DESC,
                        clan_data.reputation_score DESC 
                        LIMIT 100',
        'call' => 'game',
    ],

    'statistic_top_block_TRANC' => [
        'sql'  => 'SELECT
	characters.char_name AS player_name,
	characters.pvpkills AS pvp,
	characters.pkkills AS pk,
	characters.onlinetime AS time_in_game,
	characters.accesslevel,
	clan_data.clan_name,
	( SELECT `data` FROM crests WHERE crest_id = clan_data.crest_id LIMIT 1 ) AS clan_crest,
	( SELECT `data` FROM crests WHERE crest_id = clan_data.ally_crest_id LIMIT 1 ) AS alliance_crest 
FROM
	characters
	LEFT JOIN clan_data ON characters.clanid = clan_data.clan_id 
WHERE
	characters.accesslevel < 0;',
        'call' => 'game',
    ],

    'statistic_top_onlinetime_TRANC' => [
        'sql'  => 'SELECT
                        characters.charId AS player_id,
                        characters.char_name AS player_name,
                        characters.pvpkills AS pvp,
                        characters.pkkills AS pk,
                        characters.`online`,
                        characters.onlinetime AS time_in_game,
                        clan_data.clan_name,
                        clan_data.clan_level,
                        clan_data.hasCastle AS castle_id,
                        clan_data.reputation_score,
                        characters.`level`,
                        characters.classid AS class_id,
                        ( SELECT `data` FROM crests WHERE crest_id = clan_data.crest_id LIMIT 1 ) AS clan_crest,
                        ( SELECT `data` FROM crests WHERE crest_id = clan_data.ally_crest_id LIMIT 1 ) AS alliance_crest 
                    FROM
                        characters
                        LEFT JOIN clan_data ON characters.clanid = clan_data.clan_id 
                    ORDER BY
                        characters.onlinetime DESC 
                        LIMIT 100;',
        'call' => 'game',
    ],

    /**
     * Возращаем
     * player_id*
     * player_name*
     * pvp*
     * pk*
     * title*
     * online*
     * time_in_game*
     * clan_name*
     * class_id*
     * level*
     *
     * karma
     * createtime
     */
    'statistic_player_info' => [
        'sql'  => 'SELECT
                        characters.charId AS player_id, 
                        characters.char_name AS player_name, 
                        characters.pvpkills AS pvp, 
                        characters.pkkills AS pk, 
                        characters.title, 
                        characters.`online`, 
                        characters.onlinetime AS time_in_game, 
                        clan_data.clan_name, 
                        characters.base_class AS class_id, 
                        characters.`level`
                    FROM
                        characters
                        LEFT JOIN
                        clan_data
                        ON 
                            characters.clanid = clan_data.clan_id
                    WHERE
                        characters.char_name =?',
        'call' => 'game',
    ],

    /**
     * class_id*
     * level*
     */
    'statistic_player_info_sub_class' => [
        'sql'  => 'SELECT
                        character_subclasses.class_id, 
                        character_subclasses.`level`
                    FROM
                        character_subclasses
                    WHERE
                        character_subclasses.charId = ?',
        'call' => 'game',
    ],

    'statistic_player_inventory_info' => [
        'sql'  => 'SELECT
                        items.item_id,
                        items.count,
                        items.loc,
                        items.enchant_level 
                    FROM
                        items 
                    WHERE
                        ( items.loc = "PAPERDOLL" OR items.loc = "INVENTORY" ) 
                        AND items.owner_id = ?',
        'call' => 'game',
    ],

    'statistic_top_counter_TRANC' => [
        'sql'  => 'SELECT
                        SUM( characters.onlinetime ) AS `count_onlinetime`,
                        SUM( characters.pvpkills ) AS `count_pvpkills`,
                        SUM( characters.pkkills ) AS `count_pkkills`,
                        ( SELECT count(*) FROM `clan_data` ) AS `count_clans`,
                        ( SELECT count(*) FROM `clan_data` WHERE hasCastle != 0 ) AS `count_clan_has_castle`,
                        ( SELECT count(*) FROM `castle` ) AS `count_castle`,
                        ( SELECT count(*) FROM `characters` WHERE characters.ONLINE = 1 ) AS `player_online`,
                        ( SELECT count(*) FROM `characters` ) AS `player_all` 
                    FROM
                        characters;',
        'call' => 'game',
    ],

    'statistic_top_online_count_TRANC' => [
        'sql'  => 'SELECT * FROM characters;',
        'call' => 'game',
    ],

    'is_player' => [
        'sql'  => 'SELECT charId as player_id, online FROM characters WHERE char_name = ? LIMIT 1',
        'call' => 'game',
    ],

    'max_value_item_object' => [
        'sql'  => 'SELECT MAX(object_id) + 1 AS `max_object_id` FROM `items`',
        'call' => 'game',
    ],

    'check_item_player' => [
        'sql'  => 'SELECT count, object_id, owner_id, item_id FROM items WHERE item_id = ? AND owner_id = ? LIMIT 1',
        'call' => 'game',
    ],

    'update_item_count_player' => [
        'sql'  => 'UPDATE `items` SET `count` = ? WHERE `object_id` = ?',
        'call' => 'game',
    ],

    'add_item' => [
        'sql'  => 'INSERT INTO `items` (`owner_id`, `object_id`, `item_id`, `count`, `enchant_level`, `loc`) VALUES (?, ?, ?, ?, ?, ?)',
        'call' => 'game',
    ],

    'all_player_items' => [
        'sql'  => 'SELECT
                        items.item_id,
                        items.count,
                        items.enchant_level,
                        items.loc 
                    FROM
                        items
                        LEFT JOIN characters ON items.owner_id = characters.charId 
                    WHERE
                        characters.charId = ?',
        'call' => 'game',
    ],

    'player_subclasses' => [
        'sql'  => 'SELECT class_id, `level` FROM character_subclasses WHERE character_subclasses.charId = ?',
        'call' => 'game',
    ],

    'count_online_player' => [
        'sql'  => 'SELECT COUNT(1) AS `count_online_player` FROM characters WHERE characters.`online` = 0',
        'call' => 'game',
    ],

];