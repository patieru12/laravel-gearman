# Description

This package gives you the possibily to add gearman as native queue back-end service

#Installation

first you need to add it to your composer.json

    "patieru/gearman": "^1.1"

second, in `config/app.php`, you need to comment out the native queue service provider

    //Illuminate\Queue\QueueServiceProvider::class,

and to put this instead:

    Patieru\Gearman\GearmanServiceProvider::class,

Then in your config/queue.php file you can add:

    'default' => 'gearman',
    'connections' => [
        ................
        'gearman' => [
            'driver' => 'gearman',
            'host'   => 'localhost', //means you gearman server is installed on you local machine
            'queue'  => 'default',
            'port'   => 4730,
            'timeout' => 1000 //milliseconds
        ],
    ]

or, if you have multiple gearman servers:

    'connections' => [
        ...........................
        'gearman' => [
            'driver' => 'gearman',
            'hosts'  => [
                ['host' => 'localserver.local', 'port' => 4730],
                ['host' => 'localserver2.local', 'port' => 4730],
            ],
            'queue'  => 'default',
            'timeout' => 1000 //milliseconds
        ]
    ]

Then Remember to change default connection for Queue
change from 

    QUEUE_CONNECTION=sync

to 

    QUEUE_CONNECTION=gearman

in .env


Then in your code you can add code as (this is the native way to add jobs to the queue):

    Job::dispatch();

Small hint, you can call Namespaced classes and everything that is written in the docs of laravel for calling custom methods is valid here, too.


# Example:

I add a "service" folder to my app folder and inside I create a file "SendMail.php"
The code of the class is here:

    <?php

    namespace App\Jobs;

    use App\AudioProcessor;
    use App\Podcast;
    use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Foundation\Bus\Dispatchable;
    use Illuminate\Queue\InteractsWithQueue;
    use Illuminate\Queue\SerializesModels;

    class ProcessPodcast implements ShouldQueue {

        use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
        protected $podcast;

        public function __construct(Podcast $podcast){
            $this->podcast = $podcast;
        }

        public function handle(AudioProcessor $processor){
            // Process uploaded podcast...
        }

    }

In you controller

    <?php

    namespace App\Http\Controllers;

    use App\Http\Controllers\Controller;
    use App\Jobs\ProcessPodcast;
    use Illuminate\Http\Request;

    class PodcastController extends Controller {
        /**
        * Store a new podcast.
        *
        * @param  Request  $request
        * @return Response
        */
        public function store(Request $request)
        {
            // Create podcast...

            ProcessPodcast::dispatch($podcast);
        }
    }


In my routes file I add a new Route


    Route::post('/gearman', 'App\Http\Controllers\PodcastController@store');

Finally I just run on my console:

    php artisan queue:listen

And I go to check what's on my email

#Bugs

Please if you notice a bug open an issue or submit request. 

Hope this package will help you
