<?php // ttms/logout.php
require __DIR__.'/config.php';
session_destroy();
header('Location: dashboard.php');
