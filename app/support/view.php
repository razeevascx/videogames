<?php

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

function render_template(string $template, array $data = []): never
{
    static $twig;

    $twig ??= new Twig\Environment(
        new Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/views'),
        ['autoescape' => 'html']
    );

    echo $twig->render($template, $data);
    exit;
}
