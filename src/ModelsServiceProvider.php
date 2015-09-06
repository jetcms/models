<?php namespace JetCMS\Models;

use Illuminate\Support\ServiceProvider;

class ModelsServiceProvider extends ServiceProvider {
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
            $this->publishes([
                __DIR__.'/../migrations' => base_path('database/migrations')
            ]);
	}
        // ---
	/**
	 * Register the application services. 
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}
        
}