<?php
$params = [];
$view_loaded = false;

function view($name, $input = [])
{
    global $params, $view_loaded;
    $params = array_merge($params, $input);
    include_once "views/$name.php";
    $view_loaded = true;
    include_once "views/app.php";

    return null;
}

function error($message, $n = 0)
{
    $bt = debug_backtrace()[$n];
    abort(500, ["error" => [-1, $message, $bt["file"], $bt["line"]]]);
}

function flash($k, $v)
{
    $_SESSION["flash_set"] = true;
    $_SESSION["flash"][$k] = $v;
}

function get_flash($k)
{
    return @$_SESSION["flash"][$k];
}

function has($name)
{
    global $params;
    return function_exists($name) || array_key_exists($name, $params);
}

function set($name, $value)
{
    global $params;
    $params[$name] = $value;
}

function get($name, $def = null)
{
    global $params;
    if (array_key_exists($name, $params))
        return $params[$name];

    if (function_exists($name))
        return call_user_func($name);

    if ($def !== null)
        return $def;

    error($name, 1);
}

function json($content)
{
    header("Content-type: application/json");

    echo json_encode($content);
}

function redirect($url)
{
    header("Location: $url");
}