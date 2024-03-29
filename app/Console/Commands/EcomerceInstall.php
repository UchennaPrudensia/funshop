<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class EcomerceInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ecommerce:install {--force : Do not ask for user confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install dummy data for ecommerce app';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

      if($this->option('force'))
      {
        $this->proceed();
      }
      else
      {
        if ($this->confirm('This will delete all the images in the folder named dummy1. Are you sure ? '))
        {
          $this->proceed();
        }
      }

    }



    protected function proceed()
    {

        File::deleteDirectory(public_path('storage/products/dummy1'));

        $this->callSilent('storage:link');

        $copySuccess = File::copyDirectory(public_path('img/products'), public_path('storage/products/dummy1'));

        if($copySuccess)
        {
          $this->info('Images successfully copied to storage/dummy1 folder');
        }

        $this->call('migrate:fresh', [
          '--seed' => true,
          '--force' => true,
        ]);

        $this->call('db:seed', [
          '--class' => 'VoyagerDatabaseSeeder',
          '--force' => true,
        ]);

        $this->call('db:seed', [
          '--class' => 'VoyagerDummyDatabaseSeeder',
          '--force' => true,
        ]);


        $this->call('db:seed', [
          '--class' => 'MenusTableSeederCustom',
          '--force' => true,
        ]);

        $this->call('db:seed', [
          '--class' => 'MenuItemsTableSeederCustom',
          '--force' => true,
        ]);


        $this->call('db:seed', [
          '--class' => 'DataTypesTableSeederCustom',
          '--force' => true,
        ]);

        $this->call('db:seed', [
          '--class' => 'DataRowsTableSeederCustom',
          '--force' => true,
        ]);

        $this->call('db:seed', [
          '--class' => 'RolesTableSeederCustom',
          '--force' => true,
        ]);

        $this->call('db:seed', [
          '--class' => 'UsersTableSeederCustom',
          '--force' => true,
        ]);


        $this->call('db:seed', [
          '--class' => 'PermissionsTableSeederCustom',
          '--force' => true,
        ]);

        $this->call('db:seed', [
          '--class' => 'PermissionRoleTableSeederCustom',
          '--force' => true,
        ]);

        $this->info('Dummy Data Installed');

    }
}
