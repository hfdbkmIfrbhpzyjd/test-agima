<?php

function Reverse($arr)
{
    $keys = array_keys($arr);
    $values = array_values($arr);

    $result = [];

    for ($i = count($keys) - 1; $i >= 0; $i--) {
        $result[$keys[$i]] = $values[$i];
    }

    return $result;
}


$arr = [
    'a' => '1',
    '2' => 'b',
    'c' => '9',
    '5' => '1',
    '9' => 's',
];

var_dump($arr);


var_dump(Reverse($arr));
