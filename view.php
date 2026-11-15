<?php

$autoload = __DIR__ . '/vendor/autoload.php';
if (!file_exists($autoload)) {
    $autoload = dirname(__DIR__, 2) . '/vendor/autoload.php';
}
require_once $autoload;

function render_template(string $template, array $data = []): never
{
    static $twig;

    $twig ??= new Twig\Environment(
        new Twig\Loader\FilesystemLoader(__DIR__),
        ['autoescape' => 'html']
    );

    echo $twig->render($template, $data);
    exit;
}
