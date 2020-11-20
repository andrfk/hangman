<?php namespace andrfk\hangman\View;

    function showGame($fails, $entryField) {
        $pseudographics = array (
            " +---+\n     |\n     |\n     |\n    ===\n ",
		    " +---+\n 0   |\n     |\n     |\n    ===\n ",
		    " +---+\n 0   |\n |   |\n     |\n    ===\n ",
		    " +---+\n 0   |\n/|   |\n     |\n    ===\n ",
		    " +---+\n 0   |\n/|\  |\n     |\n    ===\n ",
		    " +---+\n 0   |\n/|\  |\n/    |\n    ===\n ",
		    " +---+\n 0   |\n/|\  |\n/ \  |\n    ===\n "
        );

        echo $pseudographics[$fails];

        

        for ($i = 0; $i < strlen($entryField); $i++) {
            echo $entryField[$i];
        }

        echo "\n";

        echo "\n";
    }

    
?>