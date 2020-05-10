<?php

if (!getenv('BASE_STORAGE_DIR')) {
    putenv("BASE_STORAGE_DIR=".realpath("./../storage"));
}