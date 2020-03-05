<?php
    function after ($texto1, $string)
    {
        if (!is_bool(strpos($string, $texto1)))
        return substr($string, strpos($string,$texto1)+strlen($texto1));
    };
    
    function before ($texto1, $string)
    {
        return substr($string, 0, strpos($string, $texto1));
    };
    
    function between ($texto1, $texto2, $string)
    {
        return before ($texto2, after($texto1, $string));
    };
    
    function strrevpos($instr, $needle)
    {
        $rev_pos = strpos (strrev($instr), strrev($needle));
        if ($rev_pos===false) return false;
        else return strlen($instr) - $rev_pos - strlen($needle);
    };
    
    function before_last ($texto1, $string)
    {
        return substr($string, 0, strrevpos($string, $texto1));
    };
?>