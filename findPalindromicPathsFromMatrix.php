<?php

/**
 * This is a PHP script with a near tail recursive optimized approach to find all palindromic paths from top-left to bottom-right in a given matrix with
 * @author Francesco Liuzzi <franc.liuzzi@gmail.com>
 */

/** START input generator functions */
function matrixGenerator(int $size = 10, array $chars = ['a', 'b'])
{
    $matrix = [];
    for ($row = 0; $row < $size; $row++) {
        $matrix[$row] = [];
        for ($col = 0; $col < $size; $col++) {
            $matrix[$row][$col] = $chars[rand(0, count($chars) - 1)];
        }
    }

    return $matrix;
}
function printMatrix($matrix)
{
    foreach ($matrix as $row) {
        foreach ($row as $cell) {
            echo $cell . ' ';
        }
        echo "\n";
    }
}
/** END input generator functions */

/** START palindromic test function */
function getHalfString(String $string, bool $secondOneReversed = false) : String
{
    $length = strlen($string);
    $offset = $length % 2;

    if (!$secondOneReversed) { // first
        $start = 0;
        $end = ($length - $offset) / 2;
    } else {
        $start = ($length + $offset) / 2;
        $end = $length;
    }

    $half = substr(
        $string,
        $start,
        $end
    );

    if ($secondOneReversed) {
        return implode(
            '',
            array_reverse(
                str_split($half)
            )
        );
    }

    return $half;
}
function isPalindromic(String $path) : bool
{
    $length = strlen($path);

    if ($length < 2) {
        return true;
    }

    $firstHalf = getHalfString($path);
    $secondHalfReversed = getHalfString($path, true);

    return $firstHalf === $secondHalfReversed;
}
$isPalindromicFn = function (...$argv) {
    return isPalindromic(...$argv);
};
/** END palindromic test function */

function getSubMatrix(array $matrix, array $origin = [0, 0])
{
    [$rowOrigin, $colOrigin] = $origin;

    $newMatrix = array_slice(
        array_map(function ($row) use ($colOrigin) {
            return array_slice(
                $row,
                $colOrigin
            );
        }, $matrix),
        $rowOrigin
    );

    return $newMatrix;
}

function getAllPathsMatchingTest(array $matrix, $testFn = null, $accumulatedPath = '')
{
    $firstChar = $matrix[0][0];
    $rows = count($matrix);
    $cols = count($matrix[0]);
    
    if ($rows == 1 && $cols === 1) { // exit condition
        $candidate = $accumulatedPath . $firstChar;

        if ($testFn === null) {
            return [$candidate];
        } elseif ($testFn($candidate)) {
            return [$candidate];
        }
        return [];
    } elseif ($rows > 1 && $cols === 1) { // go Right
        return [
            ...getAllPathsMatchingTest(
                getSubMatrix($matrix, [1, 0]),
                $testFn,
                $accumulatedPath . $firstChar
            ),
        ];
    } elseif ($rows === 1 && $cols > 1) { // go Down
        return [
            ...getAllPathsMatchingTest(
                getSubMatrix($matrix, [0, 1]),
                $testFn,
                $accumulatedPath . $firstChar
            ),
        ];
    } else { // go both right and down
        return [
            ...getAllPathsMatchingTest(
                getSubMatrix($matrix, [0, 1]),
                $testFn,
                $accumulatedPath . $firstChar
            ),
            ...getAllPathsMatchingTest(
                getSubMatrix($matrix, [1, 0]),
                $testFn,
                $accumulatedPath . $firstChar
            ),
        ];
    }
}

$input =  [
    [ 'a', 'a', 'a', 'b' ],
    [ 'b', 'a', 'a', 'a' ],
    [ 'a', 'b', 'b', 'a' ],
];
// $input = matrixGenerator(10);
echo "Input matrix";
echo "\n";
printMatrix($input);
echo "\n";

function main()
{
    global $input, $isPalindromicFn;

    $allPalindromicPaths = getAllPathsMatchingTest($input, $isPalindromicFn);
    
    print "Found palindromic paths:";
    echo "\n";
    print_r($allPalindromicPaths);
};

main();