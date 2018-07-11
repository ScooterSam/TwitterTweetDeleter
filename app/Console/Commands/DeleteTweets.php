<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Twitter;

class DeleteTweets extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'tweets:delete';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description';

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
		$twitter = new Twitter(
			env('twitter_consumer_key'),
			env('twitter_consumer_sec'),
			env('twitter_access_token'),
			env('twitter_access_secret')
		);

		$running = true;

		while ($running === true) {
			$statuses = $twitter->request('statuses/user_timeline', 'GET', ['count' => 200]);

			$statusCount = count($statuses);

			if (!$statusCount) {
				$running = false;
				break;
			}

			foreach ($statuses as $status) {
				$twitter->request("statuses/destroy/{$status->id}", 'POST');
			}

			$this->info("Successfully deleted {$statusCount} tweets. Sleeping for 3 seconds...");
			sleep(3);
		}


	}
}
