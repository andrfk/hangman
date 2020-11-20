<?php 

namespace andrfk\hangman\Controller;
use function andrfk\hangman\View\showGame;

function showResult ($answers, $word)
{
    
    if ($answers == 4) {
        echo "\n You won!";
    }

    else {
        echo "\n You lose!";
    }

    echo "\n The hidden word was: $word\n";
}

function startGame() 
{
    $word = "answer";
    
    $remaining = substr($word, 1, -1);

    $entryField = "______";
    $entryField[0] = $word[0];
    $entryField[-1] = $word[-1];

    $fails = 0;
    $answers = 0;

    do {
        showGame($fails, $entryField);
        $letter = mb_strtolower(readline("Letter: "));
        $attempt = 0;

        for ($i = 0; $i < strlen($remaining); $i++) {
            if ($remaining[$i] == $letter ) {
                $entryField[$i + 1] = $letter;
                $remaining[$i] = " ";
                $answers++;
                $attempt++;
            }
        }

        if ($attempt == 0) {
            $fails++;
        }


            } while ($fails != 6 && $answers != 4);

            showGame($fails, $entryField);
            showResult($answers, $word);        
}

?>
