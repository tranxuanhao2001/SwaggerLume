<?php
namespace SwaggerLume\Console;

use SwaggerLume\Console\Helpers\Publisher;
use Illuminate\Console\Command;

/**
 *
 * Class PublishControllerCommand
 *
 * @author Augusto Gerardo Sotelo Labarca <agsotelo@gmail.com>
 *
 */
class PublishControllerCommand extends Command {
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'swagger-lume:publish-controller';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Publish controller';
	
	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		$this->info('Publish controller file');
		
		(new Publisher($this))->publishFile(
				realpath(__DIR__.'/../').'/SwaggerController.php',
				config('swagger-lume.paths.controller'),
				'SwaggerController.php'
				);
	}
}