<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Контроллер пополнения баланса, к нему обращается "система оплаты",
 * он проверяет оплату и зачисляет на счет пользователя.
*/
class BalanceFillController extends Controller
{
    /**
     * @param Request $request
     * @return bool
     */
    public function xyzPayment(Request $request){
        $data = $request->all();

        // Проверяем секретный ключ
        if($data['sign'] == env('SECRET_XYZ')){
            $order = Order::find($data['order_id']);

            // увеличиваем баланс
            if($order){
                $user = User::find($order->user_id);
                $user->balance += $order->sum;
                $user->update();

                return true;
            }
        }

        return false;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function oldPayment(Request $request){
        $data = $request->all();

        $order = Order::find($data['order_id']);

        if($order) {
            // Прописываем фейковый ответ от сервисов оплаты
            \Http::fake([
                'old-pay.ru/*' => \Http::response(['status' => 'success', 'sum' => $order->sum, 'order_id' => $order->id], 200)
            ]);

            // Проверяем оплату
            $response = \Http::withHeaders(['X-Secret-Key' => env('SECRET_OLD')])
                ->get('https://old-pay.ru/api/get-status', ['id' => $data['payment_id']])->json();

            // Увеличиваем баланс
            if($response['status'] == 'success'){
                $user = User::find($order->user_id);
                $user->balance += $order->sum;
                $user->update();

                return true;
            }
        }

        return false;
    }
}
