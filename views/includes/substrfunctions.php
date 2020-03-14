<?php
    function after ($texto1, $inthat)
    {
        if (!is_bool(strpos($inthat, $texto1)))
        return substr($inthat, strpos($inthat,$texto1)+strlen($texto1));
    };

    function after_last ($texto1, $inthat)
    {
        if (!is_bool(strrevpos($inthat, $texto1)))
        return substr($inthat, strrevpos($inthat, $texto1)+strlen($texto1));
    };

    function before ($texto1, $inthat)
    {
        return substr($inthat, 0, strpos($inthat, $texto1));
    };

    function before_last ($texto1, $inthat)
    {
        return substr($inthat, 0, strrevpos($inthat, $texto1));
    };

    function between ($texto1, $that, $inthat)
    {
        return before ($that, after($texto1, $inthat));
    };

    function between_last ($texto1, $that, $inthat)
    {
        return after_last($texto1, before_last($that, $inthat));
    };

    function strrevpos($instr, $needle)
    {
        $rev_pos = strpos (strrev($instr), strrev($needle));
        if ($rev_pos===false) return false;
        else return strlen($instr) - $rev_pos - strlen($needle);
    };

    // after ('@', 'biohazard@online.ge');
    //returns 'online.ge'
    //from the first occurrence of '@'

    // before ('@', 'biohazard@online.ge');
    //returns 'biohazard'
    //from the first occurrence of '@'

    // between ('@', '.', 'biohazard@online.ge');
    //returns 'online'
    //from the first occurrence of '@'

    // after_last ('[', 'sin[90]*cos[180]');
    //returns '180]'
    //from the last occurrence of '['

    // before_last ('[', 'sin[90]*cos[180]');
    //returns 'sin[90]*cos['
    //from the last occurrence of '['

    // between_last ('[', ']', 'sin[90]*cos[180]');
    //returns '180'
    //from the last occurrence of '['
?>

