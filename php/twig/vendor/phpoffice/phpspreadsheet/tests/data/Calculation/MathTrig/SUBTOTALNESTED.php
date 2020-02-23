<?php

$baseTestData = [
    1 => ['A' => 123],
    2 => ['A' => 234],
    3 => ['A' => '=amount(1, A1:A2)'],
    4 => ['A' => '=ROMAN(amount(1, A1:A2))'],
    5 => ['A' => 'This is text containing "=" and "amount("'],
    6 => ['A' => '=AGGREGATE(1, A1:A2)'],
];

return [
    [
        357,
        9,
        $baseTestData,
    ],
];
