<?php

namespace App\Http\Controllers\FakePaymentSystem;

use App\Http\Controllers\Controller;
use App\Models\XyzPaymentService;
use Illuminate\Http\Request;

/**
 * Контроллер имитирует деятельность системы оплаты XYZPayment’s,
 * основной его задачей является отправка запроса "от системы оплаты",
 * после завершения "оплаты", если ответ получен, происходит редирект на главную.
*/
class XyzPaymentController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data = $request->all();

        return view('pay-system.xyz', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $transaction = new XyzPaymentService($request->all());

        if($transaction->save()){
            $response = \Http::post(
                route('fill-balance.by-xyz'),
                [
                    'order_id' => $transaction->order_id,
                    'transaction_id' => $transaction->id,
                    'sum' => $transaction->sum,
                    'name' => $transaction->name ?? null,
                    'sign' => env('SECRET_XYZ'),
                ]
            );

            if($response->ok())
                return redirect('/');

        }
    }
}
