<?php

namespace Deployer;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;

require 'recipe/common.php';
require __DIR__ . '/vendor/autoload.php';

try {
    $dotenv = Dotenv::createImmutable(__DIR__ . "/../");
    $dotenv->load();
} catch (InvalidPathException $e) {
    echo "No .env file found, using default path (cache/cached_schema.php)";
}

set('application', 'Chessable');
set('ssh_multiplexing', true);
set('repository', 'git@github.com:chrisvoo/chessable.git');
set('git_tty', true);   // [Optional] Allocate tty for git clone. Default value is false.
set('shared_files', [".env"]); // Shared files between deploys
set('shared_dirs', []);  // Shared dirs between deploys
set('writable_dirs', []); // Writable dirs by web server
set('keep_releases', 5);

// Hosts
host('localhost')
    ->user('ccastelli')
    ->stage('production')
    ->set('deploy_path', '/var/www/chessable');

// use can define as many hosts as you want
//host('chessable.com')
//    ->stage('production')
//    ->user('ubuntu')
//    ->set('deploy_path', '/home/ubuntu/www/chessable');

$tasks = [
    'deploy:info',
    'deploy:prepare', // checks and creates the remote paths
    'deploy:lock', // Locks deployment so only one concurrent deployment can be running.
    'deploy:release', // Create a new release folder based on release_name parameter
    'deploy:update_code', // Download a new version of code using Git.
    'deploy:shared', // Creates shared files and directories from the shared directory into the release_path.
    'deploy:writable', // Makes the directories listed in writable_dirs writable using acl mode
    'deploy:vendors', // Install composer dependencies.
    'deploy:clear_paths', // Deletes dirs specified in clear_paths.
    'deploy:symlink', // Switch the current symlink to release_path.
    'deploy:unlock', // Deletes the .dep/deploy.lock file.
    'cleanup', // Clean up old releases using the keep_releases option.
    'success' // Prints a success message.
];

// Tasks
desc('Deploy Chessable');
task('deploy', $tasks);

// [Optional] If deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
