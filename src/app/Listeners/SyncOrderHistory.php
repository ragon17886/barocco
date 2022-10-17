<?php

namespace App\Listeners;

use App\Models\Orders\Order;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SyncOrderHistory
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(private Order $order)
    {
    }

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        if (!empty($event->user->phone)) {
            $this->order
                ->where('phone', $event->user->phone)
                ->update(['user_id' => $event->user->id]);
        }
    }
}
