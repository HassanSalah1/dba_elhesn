<?php

namespace App\Console\Commands;

use Alhoqbani\SmsaWebService\Smsa;
use App\Entities\OrderStatus;
use App\Entities\ShipmentType;
use App\Models\Order;
use Illuminate\Console\Command;

class TrackSmsaShipment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smsa:daily';

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
     * @return int
     */
    public function handle()
    {
        $orders = Order::where(['status' => OrderStatus::SHIPPED,
            ['shipment_number' , '!=' , null],
            'shipment_type' => ShipmentType::APP_SHIP])
            ->orderBy('id', 'DESC')
            ->limit(30)
            ->get();

        foreach ($orders as $order) {
            $passKey = config('smsa.passkey');
            $smsa = new Smsa($passKey);
            $track = $smsa->track($order->shipment_number);
            if ($track->success) {
                $array = $track->data;
                if(isset($array['status']) && $array['status'] == 'delivered'){
                    $order->update(['status' => OrderStatus::COMPLETED]);
                }
            }
        }

        $this->info('Successfully');
        return Command::SUCCESS;
    }
}
