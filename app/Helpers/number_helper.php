<?php

function numberToWords($number) {
    $dictionary = [
        0 => 'zero',
        1 => 'one',
        2 => 'two',
        3 => 'three',
        4 => 'four',
        5 => 'five',
        6 => 'six',
        7 => 'seven',
        8 => 'eight',
        9 => 'nine',
        10 => 'ten',
        11 => 'eleven',
        12 => 'twelve',
        13 => 'thirteen',
        14 => 'fourteen',
        15 => 'fifteen',
        16 => 'sixteen',
        17 => 'seventeen',
        18 => 'eighteen',
        19 => 'nineteen',
        20 => 'twenty',
        30 => 'thirty',
        40 => 'forty',
        50 => 'fifty',
        60 => 'sixty',
        70 => 'seventy',
        80 => 'eighty',
        90 => 'ninety',
        100 => 'hundred',
        1000 => 'thousand',
        1000000 => 'million',
        1000000000 => 'billion'
    ];

    // Validate and initialize variables
    if (!is_numeric($number)) return false;

    $string = "";
    $fraction = null;

    // Handling negative numbers
    if ($number < 0) {
        return "negative " . numberToWords(abs($number));
    }

    // Handling decimal part
    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    // Processing the whole number part
    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens = (int)($number / 10) * 10;
            $units = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= "-" . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds = (int)($number / 100);
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . " hundred";
            if ($remainder) {
                $string .= " and " . numberToWords($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int)($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = numberToWords($numBaseUnits) . " " . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? " and " : ", ";
                $string .= numberToWords($remainder);
            }
            break;
    }

    // Processing the fractional part, if present and not '00'
    if (null !== $fraction && is_numeric($fraction) && $fraction !== '00') {
        $fractionWords = [];
        foreach (str_split((string)$fraction) as $digit) {
            $fractionWords[] = $dictionary[$digit];
        }
        // Append "and" if there was an integer part
        if (!empty($string)) {
            $string .= " and ";
        }
        $string .= implode(' ', $fractionWords) . " centavos";
    }

    return $string;
}
