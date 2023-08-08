<?php

namespace App\Http\Controllers;

use App\Models\Orders\Order;
use App\Models\Orders\OrderItem;
use App\Models\User\User;
use App\Notifications\TestSms;
use App\Services\Order\OrderItemInventoryService;
use Illuminate\Database\Eloquent\Model;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class DebugController extends Controller
{
    public function index()
    {
        return 'ok';

        /** @var OrderItem */
        $orderItem = OrderItem::findOrFail(6666);
        $orderItem->invertoryNotification()->firstOrCreate(['stock_id' => 14]);

        (new OrderItemInventoryService)->handleChangeItemStatus($orderItem->refresh());

        /** @var User $user */
        $user = User::findOrFail('xxxx');
        dd($user, $user->notifyNow(new TestSms()));

        // php artisan make:mail OrderShipped

        return dd(Order::with('data')->find(3));
    }

    /**
     * Show php info
     */
    public function phpinfo(): never
    {
        exit(phpinfo());
    }

    /**
     * @return void
     */
    public function formatPhones()
    {
        Order::all()->each(function (Model $order) {
            $this->formatPhone($order);
        });

        User::all()->each(function (Model $user) {
            $this->formatPhone($user);
        });
    }

    /**
     * @return void
     */
    protected function formatPhone($model, string $phoneFileldName = 'phone', string $countryCode = 'BY')
    {
        try {
            $phone = $model->$phoneFileldName;
            $phoneUtil = PhoneNumberUtil::getInstance();

            if (empty($phone) || strlen($phone) < 6) {
                return;
            }

            $parsedPhone = $phoneUtil->parse($phone, $countryCode);

            if (!$phoneUtil->isValidNumber($parsedPhone)) {
                return;
            }

            $formatedPhone = $phoneUtil->format($parsedPhone, PhoneNumberFormat::E164);

            $model->$phoneFileldName = $formatedPhone;

            if ($model->isDirty()) {
                echo "Old phone: '$phone', new phone: '$formatedPhone'<br>";
                $model->save();
            }
        } catch (\Throwable $th) {
            dump($th->getMessage());

            return;
        }
    }
}
