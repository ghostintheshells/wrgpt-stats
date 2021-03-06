<?php
/**
 * Created by PhpStorm.
 * User: jgrosman
 * Date: 1/2/19
 * Time: 3:53 PM
 */

namespace wrgpt\model;

use wrgpt\util\DBConn;

class HandModel
{
    public $tournamentNum;

    public $tableName;

    public $handNum;

    public $player;

    public $handBeganDate;

    public $latestRound;

    public $position;

    public $isAllIn = false;

    public $wasInShowdown = false;

    public $isWinner = false;

    public $putMoneyPreflop = false;

    public $raisedPreflop = false;

    public $cards;

    public $chips;

    public function __construct($player, $timeStamp, $tournamentNum, $tableName, $handNum, $position)
    {
        $this->tournamentNum = $tournamentNum;
        $this->player = $player;
        $this->tableName = $tableName;
        $this->handNum = $handNum;
        $this->position = $position;
        $this->handBeganDate = date('Y-m-d', strtotime($timeStamp));
    }

    public function save()
    {
        $conn = DBConn::getConnection();

        $insertSql =<<<SQL
         INSERT INTO hand_by_hand
          (tournament_id, table_name, hand_num, player, hand_began, position, latest_round, cards, put_money_preflop, raised_preflop, is_all_in, was_in_showdown, is_winner, chips) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE player = ?,
                                    hand_began = ?,
                                    position = ?, 
                                    latest_round = ?, 
                                    cards = ?, 
                                    put_money_preflop = ?, 
                                    raised_preflop = ?, 
                                    is_all_in = ?, 
                                    was_in_showdown = ?, 
                                    is_winner = ?,
                                    chips = ?
SQL;

        try {
            $conn ->execute($insertSql, [
                $this->tournamentNum,
                $this->tableName,
                $this->handNum,
                $this->player,
                $this->handBeganDate,
                $this->position,
                $this->latestRound,
                $this->cards,
                $this->putMoneyPreflop ? 1 : 0,
                $this->raisedPreflop ? 1 : 0,
                $this->isAllIn ? 1 : 0,
                $this->wasInShowdown ? 1 : 0,
                $this->isWinner ? 1 : 0,
                $this->chips,
                // update
                $this->player,
                $this->handBeganDate,
                $this->position,
                $this->latestRound,
                $this->cards,
                $this->putMoneyPreflop ? 1 : 0,
                $this->raisedPreflop ? 1 : 0,
                $this->isAllIn ? 1 : 0,
                $this->wasInShowdown ? 1 : 0,
                $this->isWinner ? 1 : 0,
                $this->chips
            ]);
        }
        catch (\Exception $e) {
            print $e;
        }

    }

    }



