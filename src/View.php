<?php namespace andrfk\hangman\View;

    use function cli\line;

function showGame($fails, $gameField)
{
    $graphic = array(
        " +---+\n     |\n     |\n     |\n    ===\n ",
        " +---+\n 0   |\n     |\n     |\n    ===\n ",
        " +---+\n 0   |\n |   |\n     |\n    ===\n ",
        " +---+\n 0   |\n/|   |\n     |\n    ===\n ",
        " +---+\n 0   |\n/|\  |\n     |\n    ===\n ",
        " +---+\n 0   |\n/|\  |\n/    |\n    ===\n ",
        " +---+\n 0   |\n/|\  |\n/ \  |\n    ===\n "
    );

    echo "\n";

    line($graphic[$fails]);
    line($gameField);

    echo "\n";
}
?>