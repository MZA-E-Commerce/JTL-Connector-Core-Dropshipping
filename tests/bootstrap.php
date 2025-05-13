<?php

declare(strict_types=1);

use Doctrine\Common\Annotations\AnnotationRegistry;

require_once __DIR__ . DIRECTORY_SEPARATOR . '../vendor/autoload.php';

AnnotationRegistry::registerLoader('class_exists');

const TEST_DIR = __DIR__;
const TESTROOT = __DIR__;
