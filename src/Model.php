<?php

namespace andrfk\hangman\Model;

use RedBeanPHP\R;

use function cli\line;
use function andrfk\hangman\View\showGame;

function startGameDataBase($words, $gameDate, $gameTime, $userName, $word)
{
    dbOpen();

    R::exec(
        "INSERT INTO gamesInfo (
        gameDate,
        gameTime,
        playerName,
        word,
        result) VALUES (
            '$gameDate',
            '$gameTime',
            '$userName',
            '$word',
            'Игра не закончена')"
    );

    return R::getCell("SELECT gameId FROM gamesInfo ORDER BY gameId DESC LIMIT 1");
}

function dbCreate()
{
    R::setup('sqlite:dbGame.db');

    $gamesTable = "CREATE TABLE gamesInfo(
        gameId INTEGER PRIMARY KEY,
        gameDate DATE,
        gameTime TIME,
        playerName TEXT,
        word TEXT,
        result TEXT)";
    R::exec($gamesTable);

    $attemptsInfo = "CREATE TABLE attemptsInfo(
        gameId INTEGER,
        attempts INTEGER,
        letter TEXT,
        result TEXT)";
    R::exec($attemptsInfo);
}

function dbOpen()
{
    if (!file_exists("dbGame.db")) {
        dbCreate();
    } else {
        R::setup('sqlite:dbGame.db');
    }
}

function dbUpdate($gameId, $result)
{
    R::exec(
        "UPDATE gamesInfo
        SET result = '$result'
        WHERE gameId = '$gameId'"
    );
}

function gameList()
{
    dbOpen();
    $query = R::getAll('SELECT * FROM gamesInfo');
    foreach ($query as $row) {
        line("ID $row[gameId])");
        line("  Date: $row[gameDate] $row[gameTime]");
        line("  Name: $row[playerName]");
        line("  Word: $row[word]");
        line("  Result: $row[result]");
    }
}

function attemptsUpdate($gameId, $attempts, $letter, $result)
{
    R::exec(
        "INSERT INTO attemptsInfo ( 
        gameId,
        attempts,
        letter,
        result) VALUES (
        '$gameId',
        '$attempts',
        '$letter',
        '$result')"
    );
}

function gameReplay($id)
{
    dbOpen();
    $gameId = R::getCell("SELECT EXISTS(SELECT 1 FROM gamesInfo WHERE gameId = '$id')");

    if ($gameId) {
        $query = R::getAll("SELECT letter, result FROM attemptsInfo WHERE gameId = '$id'");
        $word = R::getCell("SELECT word FROM gamesInfo WHERE gameId = '$id'");

        $gameField = "______";
        $gameField[0] = $word[0];
        $gameField[-1] = $word[-1];
        $remaining = substr($word, 1, -1);
        $fails = 0;

        foreach ($query as $row) {
            showGame($fails, $gameField);
            $letter = $row['letter'];
            $result = $row['result'];
            line("Letter: " . $letter);
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

        line(R::getCell("SELECT result FROM gamesInfo WHERE gameId = '$id'"));
    } else {
        line("Такой игры не существует!");
    }
}