<?php namespace Patieru\Gearman;

use Illuminate\Queue\QueueServiceProvider as ServiceProvider;
use Patieru\Gearman\Connectors\GearmanConnector;

class GearmanServiceProvider extends ServiceProvider 
{
    /**
     * Register the connectors on the queue manager.
     *
     * @param  \Illuminate\Queue\QueueManager  $manager
     * @return void
     */
    public function registerConnectors($manager)
    {
        parent::registerConnectors($manager);
        
        $this->registerGearmanConnector($manager);
    }

    /**
     * Register the Gearman queue connector.
     *
     * @param  \Illuminate\Queue\QueueManager  $manager
     * @return void
     */
    public function registerGearmanConnector($manager)
    {
        $manager->addConnector('gearman', function() {
            return new GearmanConnector();
        });
    }
}
