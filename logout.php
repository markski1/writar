<?php

include_once 'data/session.php';

$session = new session;

$session->logout();

header('Location: index.php');