<?php

require_once __DIR__ . '/../models/loginModel.php';

LoginModel::logout();

header('Location: /');
exit;