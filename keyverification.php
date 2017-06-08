<?php


require "vendor/autoload.php";

const CAUSE_NOT_ON_CURVE = 2;
const CAUSE_OUT_OF_RANGE = 1;


$file = file_get_contents($argv[1]);
$lines = explode("\n", $file);

$n = "Result = ";

$lenN = strlen($n);
foreach ($lines as &$line) {
    $posResult = strpos($line, $n);
    if ($posResult === 0) {
        $v = trim(substr($line, $posResult + $lenN)) . PHP_EOL;
        $shouldPass = substr($v, 0, 1) != "F";
        if ($shouldPass) {
            $line = $n. "true";
        } else {
            $line = $n. "false";
        }
    }
}

$lines = array_filter($lines, 'strlen');

$output = implode("\n", $lines);
$output = str_replace("Qx = ", " - \n   x: ", $output);
$output = str_replace("Result = ", "   result: ", $output);
$output = str_replace("Qy = ", "   y: ", $output);

echo $output.PHP_EOL;