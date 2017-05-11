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
    $start = substr($line, 0, 2);
    if ($start === "Qx" || $start === "Qy") {
        $line = "";
    }
}

$lines = array_filter($lines, 'strlen');

$output = implode("\n", $lines);
$output = str_replace("Msg = ", " -\n   algo: sha1\n   msg_full: ", $output);
$output = str_replace("d = ", "   private: ", $output);
$output = str_replace("k = ", "   k: ", $output);
$output = str_replace("R = ", "   r: ", $output);
$output = str_replace("S = ", "   s: ", $output);



echo $output.PHP_EOL;