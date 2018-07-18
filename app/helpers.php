<?php

/*
|--------------------------------------------------------------------------
| Goterest Custom helper function
|--------------------------------------------------------------------------
*/
use Symfony\Component\Process\Process;

function makeurl()
{
    $trimmedArgs = [];

    foreach(func_get_args() as $arg){
        if(substr($arg, -1) === "/") $arg = rtrim($arg, '/');
        if(substr($arg, 0, 1) === "/") $arg = trim($arg, '/');

        $trimmedArgs[] = $arg;
    }
    return implode('/', $trimmedArgs);
}


function makeActivationCode($key) {
    return sha1(mt_rand(10000,99999).time(). $key);
}