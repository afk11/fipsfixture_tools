<?php

$file = file_get_contents($argv[1]);
$lines = explode("\n", $file);

$n = "d = ";
$lenN = strlen($n);
foreach ($lines as &$line) {
    $pos = strpos($line, $n);
    if ($pos === 0) {
        $v = trim(substr($line, $pos + $lenN)) . PHP_EOL;
        $line = $n . gmp_strval(gmp_init($v, 16), 10);
        //echo $v.PHP_EOL;
    }
}
$output = implode("\n", $lines);
$output = str_replace("d = ", " -\n   k: ", $output);
$output = str_replace("Qx = ", "   x: ", $output);
$output = str_replace("Qy = ", "   y: ", $output);

echo $output.PHP_EOL;