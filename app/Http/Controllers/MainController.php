<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Основной контроллер приложения,
 * показывает список пользователей,
 * форму начисления баланса,
 * перекидывает на страницу системы оплаты.
*/
class MainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Получаем список всех пользователей
        $users = User::all();

        return view('main.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        return view('main.create', ['user_id' => $id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Задаем правила валидации данных формы
        $rules = [
            'user_id' => 'exists:App\Models\User,id',
            'pay_system' => ['string'],
            'sum' => ['integer', 'min:0', 'required'],
            'name' => ['string', 'nullable']
        ];

        // Валидируем данные формы
        $validated = $request->validate($rules);

        $orderAttributes = [
            'user_id' => $validated['user_id'],
            'sum' => $validated['sum'],
            'name' => $validated['name'] ?? null,
            'system' => $validated['pay_system'] ?? null
        ];

        $order = new Order($orderAttributes);

        if($order->save()){

            // Прописываем фейковые ответы от сервисов оплаты
            \Http::fake([
                'xyz-payment.ru/*' => \Http::response(route('xyz.create', ['sum' => $validated['sum'], 'order_id' => $order->id, 'name' => $validated['name'] ?? null]), 200),
                'old-pay.ru/*' => \Http::response(['status' => 'success', 'redirect_to' => route('old.create', ['sum' => $validated['sum'], 'order_id' => (int) $order->id, 'name' => $validated['name']])], 200)
            ]);

            switch ($validated['pay_system']){
                case 'xyz':
                    try {
                        // Отправляем GET запрос
                        $response = \Http::get('https://xyz-payment.ru/pay', ['sum' => $validated['sum'], 'order_id' => $order->id, 'name' => $validated['name'] ?? null]);

                        // Редиректим пользователя на страницу оплаты или обратно к форме в случает ошибки
                        if($response->ok()){
                            return redirect($response->body());
                        }
                        else{
                            return back()->with(['message' => 'An error occurred on the XYZPayment service. Please try again.']);
                        }
                    }
                    catch (\Exception $exception){
                        return back()->with(['message' => 'XYZPayment service temporary unavailable. Please try another service.']);
                    }

                    break;
                case 'old':
                    try {
                        // Отправляем POST запрос
                        $response = \Http::post('https://old-pay.ru/api/create', ['sum' => $validated['sum'], 'order_id' => $order->id, 'name' => $validated['name']]);

                        // Редиректим пользователя на страницу оплаты или обратно к форме в случает ошибки
                        if($response->ok()){
                            // Получаем тело запроса и преобразуем JSON в массив
                            $data = $response->json();

                            return redirect($data['redirect_to']);
                        }
                        else{
                            return back()->with(['message' => 'An error occurred on the OLDPay service. Please try again.']);
                        }
                    }
                    catch (\Exception $exception){
                        return back()->with(['message' => 'OLDPay service temporary unavailable. Please try another service.']);
                    }

                    break;
                default:
                    return back()->with(['message' => 'Pick payment service!']);
            }
        }
        else{
            return back()->with(['message' => 'Please try again later.']);
        }

    }

}
