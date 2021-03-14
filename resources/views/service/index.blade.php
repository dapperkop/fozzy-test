<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Services') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if ($success || $error)
                    <div class="w-full {!! $success ? 'bg-green-500' : 'bg-red-500' !!} text-white bold rounded py-2 px-4 mb-4">{{ $message }}</div>
                    @endif
                    <table class="w-full table-auto">
                        <thead>
                            <tr>
                                <th class="border py-2 px-4 text-left">#ID</th>
                                <th class="border py-2 px-4 text-left">{{ __('Service Name') }}</th>
                                <th class="border py-2 px-4 text-left">{{ __('Product Name') }}</th>
                                <th class="border py-2 px-4 text-left">{{ __('CPU') }}</th>
                                <th class="border py-2 px-4 text-left">{{ __('RAM') }}</th>
                                <th class="border py-2 px-4 text-left">{{ __('Disk Size') }}</th>
                                <th class="border py-2 px-4 text-left">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($services as $service)
                            <tr>
                                <td class="border py-2 px-4">{{ $service->ID }}</td>
                                <td class="border py-2 px-4">{{ $service->name }}</td>
                                <td class="border py-2 px-4">{{ $service->product->name }}</td>
                                <td class="border py-2 px-4">{{ $service->product->cpu . __(' CPU Unit') . ($service->product->cpu > 1 ? __('s') : '') }}</td>
                                <td class="border py-2 px-4">{{ $service->product->ram . __('MB') }}</td>
                                <td class="border py-2 px-4">{{ $service->product->disk_size . __('GB SSD') }}</td>
                                <td class="border py-2 px-4">
                                    <a class="hover:underline" href="{{ route('service.form.update', ['service' => $service->ID]) }}">Edit</a>
                                    <span>/</span>
                                    <a class="delete-service hover:underline cursor-pointer" data-service-id="{{ $service->ID }}">Delete</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td class="border py-2 px-4 text-center" colspan="7">{{ __('No services') }}</td>
                            </tr>
                            @endforelse
                        </tbody>
                        {{ $services->links() }}
                    </table>
                    <script type="text/javascript">
                        (() => {
                            function deleteService() {
                                var serviceId = this.dataset.serviceId;

                                var xhr = new XMLHttpRequest;
                                xhr.onload = function () {
                                    var data = JSON.parse(xhr.responseText), 
                                        status = data.status ? 'success' : 'error', 
                                        message = data.message;

                                    location.assign('/services?' + status + '=1&message=' + encodeURIComponent(message));
                                };
                                xhr.onerror = function () {
                                    location.assign('/services?error=1&message=' + encodeURIComponent('An error occurred while deleting the service'));
                                };
                                xhr.open('GET', '/api/services/delete/' + serviceId);
                                xhr.send();
                            }

                            var deleteButtons = document.querySelectorAll('.delete-service');

                            for (var i in deleteButtons) {
                                deleteButtons[i].onclick = deleteService;
                            }
                        })();
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>