<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ComposerHook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:composer-hook';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run some modifications after the composer update command.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $authFacade = file_get_contents(
            base_path('vendor/laravel/framework/src/Illuminate/Support/Facades/Auth.php')
        );

        $authFacade = str_replace(
            '\Illuminate\Contracts\Auth\Authenticatable',
            '\App\Models\User',
            $authFacade
        );

        file_put_contents(
            base_path('vendor/laravel/framework/src/Illuminate/Support/Facades/Auth.php'),
            $authFacade
        );

        $this->info('Composer hook command ran successfully.');
    }
}
