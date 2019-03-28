<?php
require_once 'vendor/autoload.php';
require_once 'App/App.php';

\App\App::inst()->Router->dispatch();