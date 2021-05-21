@props(['route', 'data'])
<div class="p-8">
    <form action="{{ $route }}" method="POST" class="p-8 mx-auto w-1/2 rounded bg-gray-200">
        @foreach($data as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
        @csrf
        <p class="text-md fond-bold text-gray-600">
            Это "страница оплаты платежной системы", на которую мы запросили ссылку и перенаправили пользователя. <br>
            На ней он вводит свои реквизиты и производит оплату, после нажатия кнопки "Завершить оплату", система списывает деньги с карты пользователя и отправляет запрос на пополнение баланса.
        </p>
        <button type="submit" class="flex mt-4 p-4 text-md font-bold text-white bg-blue-600 rounded mx-auto">Завершить оплату</button>
    </form>
</div>


