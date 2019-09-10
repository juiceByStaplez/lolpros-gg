<?php

namespace Deployer;

require 'recipe/symfony4.php';

set('application', 'lolpros');
set('repository', 'git@github.com:SpireGG/lolpros-gg.git');
set('git_tty', false);
set('allow_anonymous_stats', false);
set('default_timeout', 600);

// Hosts
host('lolpros.xyz')
    ->user('chypriote')
    ->multiplexing(false)
    ->forwardAgent(true)
    ->set('deploy_path', '~/{{application}}');

task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:vendors',
    'database:migrate',
    'deploy:writable',
    'deploy:cache:clear',
    'deploy:cache:warmup',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
]);
