<?php

$file = file_get_contents($argv[1]);
$lines = explode("\n", $file);

$n = "d = ";
$lenN = strlen($n);

function parseCurveAndAlgo(string $section) {
    $section = str_replace("[", "", $section);
    $section = str_replace("]", "", $section);
    $algo = strtolower(str_replace("-", "", substr($section, 6, 7)));
    return [
        'nist-p' . substr($section, -3),
        $algo,
    ];
}


$algo = null;
$curve = null;
foreach ($lines as &$line) {
    $start = substr($line, 0, 2);
    if ($start === "[P") {
        list ($curve, $algo) = parseCurveAndAlgo(trim($line));
    }

    $pos = strpos($line, $n);
    if ($pos === 0) {
        $v = trim(substr($line, $pos + $lenN)) . PHP_EOL;
        $line = $n . gmp_strval(gmp_init($v, 16), 10);
        //echo $v.PHP_EOL;
    }

    if (strpos($line, "Msg = ") === 0) {
        $line = str_replace("Msg = ", "Algo = {$algo}\nMsg = ", $line);
    }

    $start = substr($line, 0, 2);
    if ($start === "Qx" || $start === "Qy") {
        $line = "";
    }
}

$lines = array_filter($lines, 'strlen');

$output = implode("\n", $lines);
$output = str_replace("Msg = ", "   msg_full: ", $output);
$output = str_replace("d = ", "   private: ", $output);
$output = str_replace("Algo = ", " -\n   algo: ", $output);
$output = str_replace("k = ", "   k: ", $output);
$output = str_replace("R = ", "   r: ", $output);
$output = str_replace("S = ", "   s: ", $output);

echo $output.PHP_EOL;