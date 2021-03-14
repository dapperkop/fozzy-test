<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        return view('service.index', [
            'services' => Service::with('product')->where('user_id', '=', Auth::id())->paginate(),
            'success' => $request->input('success', false),
            'error' => $request->input('error', false),
            'message' => $request->input('message', null)
        ]);
    }

    public function add()
    {
        return view('service.add', [
            'products' => Product::all()
        ]);
    }

    public function edit(Service $service)
    {
        return view('service.edit', [
            'service' => $service,
            'products' => Product::all()
        ]);
    }

    public function create(Request $request)
    {
        $data = $request->all();

        $isValidData = $this->isValidData($data, [
            'name' => 'required|max:255',
            'product_id' => 'required|exists:products,ID'
        ]);

        if (!$isValidData['status']) {
            return $isValidData;
        }

        $service = new Service;
        $service->user_id = Auth::id();
        $service->product_id = $request->input('product_id');
        $service->name = $request->input('name');

        return $this->saveService($service);
    }

    public function update(Request $request, Service $service)
    {
        if ($service->user_id != Auth::id()) {
            return abort(403);
        }

        $data = $request->all();

        $isValidData = $this->isValidData($data, [
            'name' => 'required|max:255'
        ]);

        if (!$isValidData['status']) {
            return $isValidData;
        }

        $service->name = $request->input('name');

        return $this->saveService($service);
    }

    public function list()
    {
        return [
            'status' => true,
            'services' => Service::with('product')->where('user_id', '=', Auth::id())->get()
        ];
    }

    public function delete(Service $service)
    {
        if ($service->user_id != Auth::id()) {
            return abort(403);
        }

        try {
            $service->delete();
        } catch (\Exception $exception) {
            return [
                'status' => false,
                'message' => 'An error occurred while deleting the service'
            ];
        }

        return [
            'status' => true,
            'message' => 'Service deletion was successful'
        ];
    }

    public function upgrade(Request $request, Service $service, Product $product)
    {
        if ($service->user_id != Auth::id()) {
            return abort(403);
        }

        $data = $request->all();
        $data['product_id'] = $product->ID;

        $isValidData = $this->isValidData($data, [
            'name' => 'required|max:255',
            'product_id' => 'required|exists:products,ID'
        ]);

        if (!$isValidData['status']) {
            return $isValidData;
        }

        $service->name = $request->input('name');
        $service->product_id = $product->ID;

        return $this->saveService($service);
    }

    public function downgrade(Request $request, Service $service, Product $product)
    {
        if ($service->user_id != Auth::id()) {
            return abort(403);
        }

        $data = $request->all();
        $data['product_id'] = $product;

        $isValidData = $this->isValidData($data, [
            'name' => 'required|max:255',
            'product_id' => [
                function ($attribute, Product $value, $fail) use ($service) {
                    if ($value->disk_size != $service->product->disk_size) {
                        $fail('Unable to downgrade in automatic mode');
                    }
                }
            ]
        ]);

        if (!$isValidData['status']) {
            return $isValidData;
        }

        $service->name = $request->input('name');
        $service->product_id = $product->ID;

        return $this->saveService($service);
    }

    public function isValidData($data = [], $rules = [])
    {
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            return [
                'status' => false,
                'messages' => $validator->errors()
            ];
        }

        return [
            'status' => true,
            'messages' => []
        ];
    }

    public function saveService(Service $service)
    {
        if ($service->save()) {
            return [
                'status' => true,
                'message' => 'Service saved successfully'
            ];
        } else {
            return [
                'status' => false,
                'messages' => [
                    'service' => ['The service has not been saved']
                ]
            ];
        }
    }
}
