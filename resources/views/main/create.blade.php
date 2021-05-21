<x-layout>
    <form method="POST" action="{{ route('donate.store') }}" class="w-1/2 py-10 mx-auto">
        @if (Session::has('message'))
            <div class="text-md text-red-500 font-bold mb-2">
                {{Session::get('message')}}
            </div>
        @endif
        @csrf
        <input type="hidden" name="user_id" value="{{ $user_id }}">
        <input type="hidden" name="pay_system" value="xyz">
        <div class="mb-2">
            <span class="text-lg text-bold text-gray-200">Выберите систему оплаты:</span>
        </div>
        <div class="flex flex-row justify-between mb-4">
            <div class="flex justify-center items-center rounded bg-gray-700 p-4 w-full mr-4 cursor-pointer border-2 border-yellow-400" data-pay-system="xyz" onclick="setPaySystem(this)">
                <span class="text-sm font-bold text-gray-200">XYZPayment’s</span>
            </div>
            <div class="flex justify-center items-center rounded bg-gray-700 p-4 w-full cursor-pointer border-yellow-400" data-pay-system="old" onclick="setPaySystem(this)">
                <span class="text-sm font-bold text-gray-200">OLDPay</span>
            </div>
        </div>

        <div class="flex flex-row justify-between mb-4">
            <input type="number" name="sum" min="1" step="1" class="border-0 bg-gray-700 text-gray-200 w-full mr-4 p-2 rounded" placeholder="Sum:" required>
            <input type="text" name="name" class="border-0 bg-gray-700 text-gray-200 w-full p-2 rounded" placeholder="Name:">
        </div>

        <div class="flex justify-end">
            <button type="submit" class="w-1/4 p-2 bg-yellow-400 font-bold text-gray-900 rounded">Pay</button>
        </div>
    </form>
    <script type="text/javascript">
        function setPaySystem(paySystem) {
            let systems = document.querySelectorAll('[data-pay-system]');
            // Утснавливаем значение инпута
            document.getElementsByName('pay_system')[0].value = paySystem.dataset.paySystem;

            // Меняем обязательность заполнения имени при разных системах оплаты
            if(paySystem.dataset.paySystem == 'old'){
                document.getElementsByName('name')[0].setAttribute('required', 'true');
            }
            else{
                document.getElementsByName('name')[0].removeAttribute('required');
            }

            // Устанавливаем бордер выбранному элементу
            systems.forEach(function callback(system) {
                system.classList.remove('border-2');
            });

            paySystem.classList.add('border-2');
        }
    </script>
</x-layout>
