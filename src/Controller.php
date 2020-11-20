<?php

namespace andrfk\hangman\Controller;

use function cli\line;
use function cli\prompt;
use function andrfk\hangman\Model\dbOpen;
use function andrfk\hangman\View\showGame;
use function andrfk\hangman\Model\dbUpdate;
use function andrfk\hangman\Model\gameList;
use function andrfk\hangman\Model\gameReplay;

function gameMenu($key)
{
    if ($key[1] === '--new') {
        startGame();
    } elseif ($key[1] === '--list') {
        gameList();
    } elseif ($key[1] === '--replay') {
        if (isset($key[2])) {
            gameReplay((int)$key[2]);
        }
    } else {
        line("Wrong key\n");
    }
}

function showResult($answers, $word)
{
    if ($answers === 4) {
        line("You won!");
    } else {
        line("You lose!");
    }
    line("The hidden word was: $word\n");
}

function startGame()
{
    $words = array("answer", "abroad", "animal", "action");
    $word = $words[array_rand($words)];

    date_default_timezone_set("Europe/Moscow");
    $gameDate = date("d") . "." . date("m") . "." . date("Y");
    $gameTime = date("H") . ":" . date("i") . ":" . date("s");

    $userName = prompt("Enter your name");
    if ($userName === "--exit") {
        exit();
    }

    $gameId = startGameDataBase($words, $gameDate, $gameTime, $userName, $word);

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

        if ($letter === "--exit") {
            line("The game hs been closed");
            return;
        }

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
        attemptsUpdate($gameId, $attempts, $letter, $result);
    } while ($fails < $maxFails && $answers < $maxAnswers);

    if ($fails < $maxFails) {
        $result = 'Win';
    } else {
        $result = 'Lose';
    }

    showGame($fails, $gameField);
    showResult($answers, $word);
    dbUpdate($gameId, $result);
}