<?php
function myFunc() {}
function myFunc( ) {}
function myFunc( $blah ) {}
function myFunc($blah, $blah2, $blah3) {}
function myFunc($blah , string $blah2, $blah3 ) {}
function myFunc($blah,int $blah2,bool $blah3) {}
function myFunc(object $blah,  int $blah2, float $blah3) {}
function myFunc($blah='hello') {}
function myFunc(PHP_CodeSniffer $object, array $array=array(), $blah3='yo') {}
function myFunc(  $blah='hello'   , $blah2=   'hi',  $blah3   = 'yo' ) {}
function myFunc(PHP_CodeSniffer   $object, array  $array=array(), $blah3='yo') {}
function myFunc( PHP_CodeSniffer $object,  array $array=array(), $blah3='yo') {}
function myFunc( array  &$one,  array  &$two) {}
function myFunc(&$blah) {}

function multiLineFunction(
    array $flatList,
    $markup,
    array $otherList,
    $lastOffset=0
) {
}

$noArgs_longVars = function ($longVar1,  $longerVar2= false) use (
    $longVar1 ,  $longerVar2,
    $muchLongerVar3
)  {
    // body
};

function myFunc($req, $opt=null, ...$params) {}
function myFunc($param, &...$moreParams) {}

function MissingParamTypeInDocBlock(array$a = null, callable$c, \ArrayObject$o, $foo = []) {}

function myFunc(...$args) {}
function myFunc(string ...$args) {}

function foo( // comment
    $bar,
    \NS\ClassName     $nsTypeHint,
    /* not a type hint */ $baz,
    string $withTypeHint
) { // comment
    // ...
}

function myFunc(/*...*/) {}
function myFunc( /*...*/ ) {}

function x(
    ?array    $a,
    ?MyNamespace\MyClass    $b,
    ?array   $c,
    ?int  $d
) {
}

function functionName(  ?string  $arg1 = 'foo' ,  ?int  & $arg2   ,  $arg3  ) {}
function functionName(string $arg1,  ... $arg2) {}
function functionName(string $arg1, int  ... $arg2) {}
function functionName(string $arg1, & ... $arg2) {}

$a = function ($var1, $var2=false) use (
    $longVar1, & $longerVar1,
    $longVar2 ,  &$longerVar2,
    $muchLongerVar3
) {};

fn ($a, $b = null) => $a($b);
fn ($a, int $b = null) => $a($b);
fn (int $a, int $b = null) => $a($b);