<?php

namespace andrfk\hangman\Model;

use SQLite3;

use function cli\line;
use function andrfk\hangman\View\showGame;

function dbCreate()
{
    $action = new SQLite3(('dbGame.db'));

    $gamesTable = "CREATE TABLE gamesInfo(
        gameId INTEGER PRIMARY KEY,
        gameDate DATE,
        gameTime TIME,
        playerName TEXT,
        word TEXT,
        result TEXT)";
    $action->exec($gamesTable);

    $attemptsInfo = "CREATE TABLE attemptsInfo(
        gameId INTEGER,
        attempts INTEGER,
        letter TEXT,
        result TEXT)";

    $action->exec($attemptsInfo);
    return $action;
}

function dbOpen()
{
    if (!file_exists("dbGame.db")) {
        $action = dbCreate();
    } else {
        $action = new SQLite3('dbGame.db');
    }
    return $action;
}

function dbUpdate($gameId, $result)
{
    $action = dbOpen();
    $action->exec(
        "UPDATE gamesInfo
        SET result = '$result'
        WHERE gameId = '$gameId'"
    );
}

function gameList()
{
    $action = dbOpen();
    $query = $action->query('SELECT * FROM gamesInfo');
    while ($row = $query->fetchArray()) {
        line(
            "ID $row[0])\n    
        Date: $row[1] $row[2]\n    
        Name: $row[3]\n    
        Word: $row[4]\n    
        Result: $row[5]"
        );
    }
}

function gameReplay($id)
{
    $action = dbOpen();
    $gameId = $action->querySingle("SELECT EXISTS(SELECT 1 FROM gamesInfo WHERE gameId = '$id')");

    if ($gameId) {
        $query = $action->query("SELECT letter, result FROM attemptsInfo WHERE gameId = '$id'");
        $word = $action->querySingle("SELECT word FROM gamesInfo WHERE gameId = '$id'");

        $gameField = "______";
        $gameField[0] = $word[0];
        $gameField[-1] = $word[-1];
        $remaining = substr($word, 1, -1);
        $fails = 0;

        while ($row = $query->fetchArray()) {
            showGame($fails, $gameField);
            $letter = $row[0];
            $result = $row[1];
            line("Буква: " . $letter);
            for ($i = 0, $iMax = strlen($remaining); $i < $iMax; $i++) {
                if ($remaining[$i] === $letter) {
                    $gameField[$i + 1] = $letter;
                    $remaining[$i] = " ";
                }
            }

            if ($result === 0) {
                $fails++;
            }
        }

        showGame($fails, $gameField);
        line($action->querySingle("SELECT result FROM gamesInfo WHERE gameId = '$id'"));
    } else {
        line("Такой игры не существует!");
    }
}