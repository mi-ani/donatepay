<?php

namespace App\Http\Controllers\FakePaymentSystem;

use App\Http\Controllers\Controller;
use App\Models\OldPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * Контроллер имитирует деятельность системы оплаты OLDPay,
 * основной его задачей является отправка запроса "от системы оплаты",
 * после завершения "оплаты", если ответ получен, происходит редирект на главную.
 */
class OldPaymentController extends Controller
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

        return view('pay-system.old', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $transaction = new OldPaymentService($request->all());

        if($transaction->save()){
            $response = \Http::post(route('fill-balance.by-old'), ['order_id' => $transaction->order_id, 'payment_id' => $transaction->id]);

            if($response->ok())
                return redirect('/');
        }
    }
}
