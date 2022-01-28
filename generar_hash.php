<?php

$time = time();
echo "Time: $time".PHP_EOL."Hash: ".sha1($argv[1].$time.'12345estaeslaclave').PHP_EOL;