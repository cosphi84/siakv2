<?php

define('BACKEND_COMP_DIR', __DIR__.'/../administrator/components/com_siak');
define('BACKEND_LANG_DIR', __DIR__.'/../administrator/language/id-ID');
define('FRONTEND_COMP_DIR', __DIR__.'/../components/com_siak');
define('FRONTEND_LANG_DIR', __DIR__.'/../language/id-ID');
define('MEDIA_DIR', __DIR__.'/../media/com_siak');
define('COM_SIAK', __DIR__.'/../tmp/com_siak');

if (file_exists(COM_SIAK)) {
    echo 'Cleaning Old Data ...................';
    shell_exec('rm -rf '.COM_SIAK);
    echo 'Done'.PHP_EOL;
}

echo 'Create new COM_SIAK Folder ..............';
if (mkdir(COM_SIAK)) {
    echo 'Done'.PHP_EOL;
} else {
    echo 'FAIL'.PHP_EOL;
    exit();
}

echo 'Copying COM_SIAK Backend .................';
shell_exec('cp -r '.BACKEND_COMP_DIR.' '.COM_SIAK);
shell_exec('mv '.COM_SIAK.'/com_siak '.COM_SIAK.'/backend');
echo 'Done'.PHP_EOL;

echo 'Copying COM_SIAK Frontend .................';
shell_exec('cp -r '.FRONTEND_COMP_DIR.' '.COM_SIAK);
shell_exec('mv '.COM_SIAK.'/com_siak '.COM_SIAK.'/frontend');
echo 'Done'.PHP_EOL;

echo 'Copying COM_SIAK Language .................';
mkdir(COM_SIAK.'/translation');
mkdir(COM_SIAK.'/translation/frontend');
mkdir(COM_SIAK.'/translation/backend');

shell_exec('cp '.BACKEND_LANG_DIR.'/id-ID.com_siak.sys.ini '.COM_SIAK.'/translation/backend/id-ID.com_siak.sys.ini');
shell_exec('cp '.BACKEND_LANG_DIR.'/id-ID.com_siak.ini '.COM_SIAK.'/translation/backend/id-ID.com_siak.ini');
shell_exec('cp '.FRONTEND_LANG_DIR.'/id-ID.com_siak.ini '.COM_SIAK.'/translation/frontend/id-ID.com_siak.sys.ini');
echo 'Done'.PHP_EOL;

echo 'Copying COM_SIAK Media  ...................';
mkdir(COM_SIAK.'/media');
shell_exec('cp -r '.MEDIA_DIR.'/css'.' '.COM_SIAK.'/media');
shell_exec('cp -r '.MEDIA_DIR.'/images'.' '.COM_SIAK.'/media');
shell_exec('cp -r '.MEDIA_DIR.'/js'.' '.COM_SIAK.'/media');
echo 'Done'.PHP_EOL;

echo 'All Done. Tnx'.PHP_EOL;
