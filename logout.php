<?php

include_once 'data/session.php';

(new session)->logout();

header('Location: ../');