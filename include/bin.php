<?php

function getcmd($args) {
    $script = BIN_PATH . "agac.py";
    $cmd = 'python3 ' . escapeshellarg($script) . ' ' . $args . ' 2>&1';
    return $cmd;
}

function binget($args) {
    return shell_exec(getcmd($args));
}

function bindisp($args) {
    passthru(getcmd($args));
}