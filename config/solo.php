<?php

declare(strict_types=1);

use SoloTerm\Solo\Commands\Command;
use SoloTerm\Solo\Commands\EnhancedTailCommand;
use SoloTerm\Solo\Commands\MakeCommand;
use SoloTerm\Solo\Hotkeys;
use SoloTerm\Solo\Themes;

// Solo may not (should not!) exist in prod, so we have to
// check here first to see if it's installed.
if (! class_exists('\SoloTerm\Solo\Manager')) {
    return [
    ];
}

return [
    /*
    |--------------------------------------------------------------------------
    | Themes
    |--------------------------------------------------------------------------
    */
    'theme' => env('SOLO_THEME', 'light'),

    'themes' => [
        'light' => Themes\LightTheme::class,
        'dark' => Themes\DarkTheme::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Keybindings
    |--------------------------------------------------------------------------
    */
    'keybinding' => env('SOLO_KEYBINDING', 'default'),

    'keybindings' => [
        'default' => Hotkeys\DefaultHotkeys::class,
        'vim' => Hotkeys\VimHotkeys::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Commands
    |--------------------------------------------------------------------------
    |
    */
    'commands' => [
        'About' => './vendor/bin/sail artisan solo:about',
        'Logs' => EnhancedTailCommand::file(storage_path('logs/laravel-' . now()->format('Y-m-d') . '.log')),
        'Vite' => Command::from('./vendor/bin/sail bun vite')->interactive(),
        'Make' => new MakeCommand(),
        // 'HTTP' => 'php artisan serve',

        // Lazy commands do no automatically start when Solo starts.
        'Dumps' => Command::from('./vendor/bin/sail artisan solo:dumps')->lazy(),
        // 'Reverb' => Command::from('php artisan reverb')->lazy(),
        'Pint' => Command::from('./vendor/bin/sail pint --ansi')->lazy(),
        'Schedule' => Command::from('./vendor/bin/sail artisan schedule:work --ansi')->lazy(),
        'Queue Listen' => Command::from('./vendor/bin/sail artisan queue:listen --ansi')->lazy(),
        'Horizon' => Command::from('./vendor/bin/sail artisan horizon --ansi')->lazy(),
        'Tests' => Command::from('./vendor/bin/sail artisan test --colors=always')->lazy(),
    ],

    /*
    |--------------------------------------------------------------------------
    | Miscellaneous
    |--------------------------------------------------------------------------
    */

    /*
     * If you run the solo:dumps command, Solo will start a server to receive
     * the dumps. This is the address. You probably don't need to change
     * this unless the default is already taken for some reason.
     */
    'dump_server_host' => env('SOLO_DUMP_SERVER_HOST', 'tcp://127.0.0.1:9984'),
];
