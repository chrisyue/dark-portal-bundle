<?php

if (empty($_GET['redirect_uri']) && empty($_GET['code'])) {
    header('status: 422'); die('<h1>:(</h1>');
}

session_start();

if (!empty($_GET['code'])) {
    if (empty($_SESSION['redirect_uri'])) {
        header('status: 422'); die('<h1>:(</h1>');
    }

    $parts = parse_url($_SESSION['redirect_uri']);
    session_unset();

    parse_str($parts['query'], $vars);
    $vars['code'] = $_GET['code'];

    $redirectUri = sprintf('%s://%s%s%s?%s',
        $parts['scheme'], $parts['host'],
        isset($parts['port']) ? sprintf(':%s', $parts['port']) : '',
        $parts['path'],
        http_build_query($vars)
    );

    header(sprintf('Location: %s', $redirectUri)); die();
}

$hosts = array(
    'wechat.xxx.com',
    'weixin.xxx.com',
);

$parts = parse_url($_GET['redirect_uri']);

if (!in_array($parts['host'], $hosts)) {
    header('status: 422'); die('<h1>:(</h1>');
}

$_SESSION['redirect_uri'] = $_GET['redirect_uri'];

$queries = array(
    'appid' => 'wxdcba7b97117e6d0a',
    // temporary solution, not reliable (no scheme and uri check)
    'redirect_uri' => sprintf('http://%s', $_SERVER['HTTP_HOST']),
    'response_type' => 'code',
    'scope' => empty($_GET['scope']) ? 'snsapi_base' : $_GET['scope'],
);

$urlGetCode = sprintf('https://open.weixin.qq.com/connect/oauth2/authorize?%s#wechat_redirect', http_build_query($queries));

header(sprintf('Location: %s', $urlGetCode));
