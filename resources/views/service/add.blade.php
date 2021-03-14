<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create new service') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div id="error-message" class="w-full bg-red-500 text-white bold rounded py-2 px-4 mb-4 hidden"></div>
                    <form name="service">
                        <div class="mb-4">
                            <label>
                                <span>Service Name</span>
                                <input type="text" name="name" />
                            </label>
                        </div>
                        @foreach ($products as $key => $product)
                        <div class="mb-4">
                            <label>
                                <input type="radio" name="product_id" value="{{ $product->ID }}" {!! !!$key ? '' : 'checked="checked"' !!} />
                                <span>{{ $product->name }}</span>
                            </label>
                        </div>
                        @endforeach
                        <button class="py-2 px-4 bg-green-500 hover:bg-green-700 text-white font-semibold rounded-lg shadow-md focus:outline-none" type="submit">Add</button>
                    </form>
                    <script type="text/javascript">
                        (() => {
                            function showErrors(errors) {
                                var errorMessage = document.getElementById('error-message'),
                                    messages = '';

                                for (var i in errors) {
                                    messages += i + ': ' + errors[i].join(';');
                                }

                                errorMessage.innerText = messages;
                                errorMessage.classList.remove('hidden');
                            }

                            document.querySelector('form[name="service"] button[type="submit"]').onclick = function (event) {
                                event.preventDefault();

                                var formdata = new FormData,
                                    name = document.querySelector('form[name="service"] input[name="name"]').value,
                                    productId = document.querySelector('form[name="service"] input[name="product_id"]:checked').value;

                                formdata.append('name', name);
                                formdata.append('product_id', productId);

                                var xhr = new XMLHttpRequest;
                                xhr.onload = function () {
                                    var data = JSON.parse(xhr.responseText);

                                    if (data.status) {
                                        location.assign('/services?success=1&message=' + encodeURIComponent(data.message));
                                    } else {
                                        showErrors(data.messages);
                                    }
                                };
                                xhr.onerror = function () {
                                    showErrors({service: ['The service has not been saved']});
                                };
                                xhr.open('POST', '/api/services/add');
                                xhr.send(formdata);
                            };
                        })();
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>