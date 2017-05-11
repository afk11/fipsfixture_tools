<?php
require "vendor/autoload.php";
use Mdanter\Ecc\Tests\Curves\SpecBasedCurveTest;

$file = file_get_contents($argv[1]);
$lines = explode("\n", $file);



function parseCurveAndAlgo(string $section) {
    $section = str_replace("[", "", $section);
    $section = str_replace("]", "", $section);

    $parts = explode(",", $section);
    if ($parts[1] == "SHA-1") {
        $sha = "sha1";
    } else {
        $sha = 'sha' . substr($parts[1], -3);
    }

    return [
        'nist-p' . substr($parts[0], -3),
        $sha,
    ];
}


$n = "Result = ";
$lenN = strlen($n);
foreach ($lines as &$line) {
    $start = substr($line, 0, 2);
    if ($start === "[P") {
        list ($curve, $algo) = parseCurveAndAlgo(trim($line));
        if ($algo !== "sha1") {
            $line = "";
            continue;
        }
    }

    $posResult = strpos($line, $n);
    if ($posResult === 0) {
        $v = trim(substr($line, $posResult + $lenN)) . PHP_EOL;

        $shouldPass = substr($v, 0, 1) != "F";

        if ($shouldPass) {
            $line = $n. ($shouldPass ? "true" : "false");
        } else {
            $cause = null;
            $c = substr($v, 3, 1);
            if ($c == "1") {
                $cause = SpecBasedCurveTest::CAUSE_MSG;
            } else if ($c == "2") {
                $cause = SpecBasedCurveTest::CAUSE_R;
            } else if ($c == "3") {
                $cause = SpecBasedCurveTest::CAUSE_S;
            } else if ($c == "4") {
                $cause = SpecBasedCurveTest::CAUSE_Q;
            }
            $line = $n. ($shouldPass ? "true" : "false") . "\n   cause: $cause";
        }
        $line .= "\n   algo: {$algo}";
    }
}

$lines = array_filter($lines, 'strlen');

$output = implode("\n", $lines);
$output = str_replace("Msg = ", " -\n   msg_full: ", $output);
$output = str_replace("Qx = ", "   x: ", $output);
$output = str_replace("Result = ", "   result: ", $output);
$output = str_replace("Qy = ", "   y: ", $output);
$output = str_replace("R = ", "   r: ", $output);
$output = str_replace("S = ", "   s: ", $output);

echo $output.PHP_EOL;