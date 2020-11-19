<?php namespace andrfk\hangman\Controller;
    use function andrfk\hangman\View\showGame;

    function startGame() {
        echo "The game started".PHP_EOL;
        showGame();
    }
?>