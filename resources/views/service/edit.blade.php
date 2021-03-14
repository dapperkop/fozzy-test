<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit service #') . $service->ID }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div id="error-message" class="w-full bg-red-500 text-white bold rounded py-2 px-4 mb-4 hidden"></div>
                    <form name="service">
                        <input type="hidden" name="ID" value="{{ $service->ID }}" />
                        <input type="hidden" name="current_product_id" value="{{ $service->product_id }}" data-points="{{ $service->product->cpu * $service->product->ram * $service->product->disk_size }}" />
                        <div class="mb-4">
                            <label>
                                <span>Service Name</span>
                                <input type="text" name="name" value="{{ $service->name }}" />
                            </label>
                        </div>
                        @foreach ($products as $product)
                        <div class="mb-4">
                            <label>
                                <input type="radio" name="product_id" value="{{ $product->ID }}" {!! $service->product_id == $product->ID ? 'checked="checked"' : '' !!} data-points="{{ $product->cpu * $product->ram * $product->disk_size }}" />
                                <span>{{ $product->name }}</span>
                            </label>
                        </div>
                        @endforeach
                        <button class="py-2 px-4 bg-green-500 hover:bg-green-700 text-white font-semibold rounded-lg shadow-md focus:outline-none" type="submit" data-action="update">Update</button>
                    </form>
                    <script type="text/javascript">
                        (() => {
                            function changeOption() {
                                var selectedOption = document.querySelector('form[name="service"] input[name="product_id"]:checked'),
                                    currentProduct = document.querySelector('form[name="service"] input[name="current_product_id"]'),
                                    selectedPoints = parseInt(selectedOption.dataset.points, 10),
                                    currentPoints = parseInt(currentProduct.dataset.points, 10),
                                    action, submitText;

                                if (selectedPoints < currentPoints) {
                                    action = 'downgrade';
                                    submitText = 'Downgrade';
                                } else if (selectedPoints > currentPoints) {
                                    action = 'upgrade';
                                    submitText = 'Upgrade';
                                } else {
                                    action = 'update';
                                    submitText = 'Update';
                                }

                                document.querySelector('form[name="service"] button[type="submit"]').dataset.action = action;
                                document.querySelector('form[name="service"] button[type="submit"]').innerText = submitText;
                            }

                            var productOptions = document.querySelectorAll('form[name="service"] input[name="product_id"]');

                            for (var i in productOptions) {
                                productOptions[i].onchange = changeOption;
                            }

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

                                var action = this.dataset.action,
                                    formdata = new FormData,
                                    name = document.querySelector('form[name="service"] input[name="name"]').value,
                                    serviceId = document.querySelector('form[name="service"] input[name="ID"]').value,
                                    url;

                                formdata.append('name', name);

                                if (action == 'update') {
                                    url = '/api/services/edit/' + serviceId;
                                } else {
                                    var productId = document.querySelector('form[name="service"] input[name="product_id"]:checked').value;
                                    url = '/api/services/' + action + '/' + serviceId + '/' + productId;
                                }

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
                                xhr.open('POST', url);
                                xhr.send(formdata);
                            };
                        })();
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>