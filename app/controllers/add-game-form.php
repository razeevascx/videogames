<?php

require_once dirname(__DIR__) . '/bootstrap.php';

render_template('add-game-form.html', ['csrf_token' => csrf_token()]);
