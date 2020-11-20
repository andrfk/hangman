<?php

namespace andrfk\hangman\Controller;

use function cli\line;
use function cli\prompt;
use function andrfk\hangman\Model\dbOpen;
use function andrfk\hangman\View\showGame;
use function andrfk\hangman\Model\dbUpdate;
use function andrfk\hangman\Model\gameList;
use function andrfk\hangman\Model\gameReplay;

function gameMenu()
{
    $userName = prompt("Enter your name");

    if (!empty($userName)) {
        while (true) {
            $key = prompt(
                "\nEnter key:\n"
                . "--new - new game\n"
                . "--list - games list\n"
                . "--replay id - replay game with current \n"
                . "--exit - exit\n"
            );

            if ($key === '--new') {
                startGame($userName);
            } elseif ($key === '--list') {
                gameList();
            } elseif (preg_match('/(^--replay [\d]+$)/', $key) !== 0) {
                gameReplay(explode(' ', $key)[1]);
            } elseif ($key === '--exit') {
                exit();
            } else {
                line("Wrong key\n");
            }
        }
    } else {
        gameMenu();
    }


}

function showResult($answers, $word)
{
    if ($answers === 4) {
        echo "\n You won!";
    } else {
        echo "\n You lose!";
    }

    echo "\n The hidden word was: $word\n";
}

function startGame($playerName)
{
    $action = dbOpen();

    $words = array("answer", "abroad", "animal", "action");
    $word = $words[array_rand($words)];

    date_default_timezone_set("Europe/Moscow");
    $gameDate = date("d") . "." . date("m") . "." . date("Y");
    $gameTime = date("H") . ":" . date("i") . ":" . date("s");

    $action->exec(
        "INSERT INTO gamesInfo (
        gameDate,
        gameTime,
        playerName,
        word,
        result) VALUES (
            '$gameDate',
            '$gameTime',
            '$playerName',
            '$word',
            'Игра не закончена')"
    );

    $gameId = $action->querySingle("SELECT gameId FROM gamesInfo ORDER BY gameId DESC LIMIT 1");

    $remaining = substr($word, 1, -1);
    $maxAnswers = strlen($remaining);
    $maxFails = 6;
    $gameField = "______";
    $gameField[0] = $word[0];
    $gameField[-1] = $word[-1];

    $fails = 0;
    $answers = 0;
    $attempts = 0;

    do {
        showGame($fails, $gameField);
        $letter = mb_strtolower(prompt("Letter: "));
        $attemptCount = 0;

        for ($i = 0, $iMax = strlen($remaining); $i < $iMax; $i++) {
            if ($remaining[$i] === $letter) {
                $gameField[$i + 1] = $letter;
                $remaining[$i] = " ";
                $answers++;
                $attemptCount++;
            }
        }

        if ($attemptCount === 0) {
            $fails++;
            $result = 'lose';
        } else {
            $result = 'win';
        }

        $attempts++;

        $action->exec(
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
    } while ($fails < $maxFails && $answers < $maxAnswers);

    showGame($fails, $gameField);
    showResult($answers, $word);
    dbUpdate($gameId, $result);
}