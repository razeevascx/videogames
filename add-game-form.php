<?php

require 'bootstrap.php';

render_template('add-game-form.html', ['csrf_token' => csrf_token()]);
