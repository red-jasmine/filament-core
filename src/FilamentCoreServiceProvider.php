<?php

namespace RedJasmine\FilamentCore;

use Filament\Forms\Components\ToggleButtons;
use Filament\Support\Assets\Asset;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables\Columns\TextColumn;
use RedJasmine\FilamentCore\Commands\FilamentCoreCommand;
use RedJasmine\FilamentCore\Testing\TestsFilamentCore;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentCoreServiceProvider extends PackageServiceProvider
{
    public static string $name = 'red-jasmine-filament-core';

    public static string $viewNamespace = 'red-jasmine-filament-core';

    public function configurePackage(Package $package) : void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
                ->hasCommands($this->getCommands())
                ->hasInstallCommand(function (InstallCommand $command) {
                    $command
                        ->publishConfigFile()
                        ->publishMigrations()
                        ->askToRunMigrations()
                        ->askToStarRepoOnGitHub('red-jasmine/filament-core');
                });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands() : array
    {
        return [
            FilamentCoreCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations() : array
    {
        return [

        ];
    }

    public function packageRegistered() : void
    {
    }

    public function packageBooted() : void
    {
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());


        TextColumn::macro('enum', function () {
            return $this->badge()
                        ->formatStateUsing(fn($state) => $state->getLabel())
                        ->color(fn($state) => $state->getColor())
                        ->icon(fn($state) => $state->getIcon());
        });

        ToggleButtons::macro('useEnum', function (string $enumClassName) {

            return $this->enum($enumClassName)
                        ->options($enumClassName::options())
                        ->colors($enumClassName::colors())
                        ->icons($enumClassName::icons());


        });


    }

    /**
     * @return array<Asset>
     */
    protected function getAssets() : array
    {
        return [
            // AlpineComponent::make('filament-core', __DIR__ . '/../resources/dist/components/filament-core.js'),
            //Css::make('filament-core-styles', __DIR__ . '/../resources/dist/filament-core.css'),
            //Js::make('filament-core-scripts', __DIR__ . '/../resources/dist/filament-core.js'),
        ];
    }

    protected function getAssetPackageName() : ?string
    {
        return 'red-jasmine/filament-core';
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData() : array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getIcons() : array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes() : array
    {
        return [];
    }
}
