<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Models\Customer;
use App\Models\User;
use App\Repositories\CustomerRepository;
use App\Repositories\ImageRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    private $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function index(Request $request)
    {
        $customers = $this->customerRepository->get($request);
        return view('customer.index', compact('customers'));
    }

    public function create(Request $request)
    {
        return view('customer.create');
    }

    public function store(StoreCustomerRequest $request)
    {
                // Get the input data
                $input = $request->all();

                // Sanitize input data against XSS
                $input['name'] = htmlspecialchars(strip_tags($input['name']));
                $input['birthdate'] = htmlspecialchars(strip_tags($input['birthdate']));
                $input['address'] = htmlspecialchars(strip_tags($input['address']));
                $input['job'] = htmlspecialchars(strip_tags($input['job']));
                $input['gender'] = htmlspecialchars(strip_tags($input['gender']));
                
                // Validate input data
                $validator = Validator::make($input, [
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255|unique:users',
                    'password' => [
                        'required',
                        'string',
                        'min:8',
                        'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
                    ],
                    'birthdate' => 'required|date',
                ], [
                    'name.required' => 'The name field is required.',
                    'email.required' => 'The email field is required.',
                    'email.email' => 'The email must be a valid email address.',
                    'password.required' => 'The password field is required.',
                    'password.min' => 'The password must be at least :min characters.',
                    'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one number, one special character, and be at least 8 characters long.',
                    'birthdate.required' => 'The birthdate field is required.',
                    'birthdate.date' => 'The birthdate must be a valid date.'
                ]);
        
                if ($validator->fails()) {
                    return redirect()->route('customer.create')->withErrors($validator)->withInput();
                }
        
        $customer = $this->customerRepository->store($input);
        return redirect('customer')->with('success', 'Customer ' . $customer->name . ' created');
    }

    public function show(Customer $customer)
    {
        return view('customer.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customer.edit', ['customer' => $customer]);
    }

    public function update(Customer $customer, StoreCustomerRequest $request)
    {
        $customer->update($request->all());
        return redirect('customer')->with('success', 'customer ' . $customer->name . ' udpated!');
    }

    public function destroy(Customer $customer, ImageRepository $imageRepository)
    {
        try {
            $user = User::find($customer->user->id);
            $avatar_path = public_path('img/user/' . $user->name . '-' . $user->id);

            $customer->delete();
            $user->delete();

            if (is_dir($avatar_path)) {
                $imageRepository->destroy($avatar_path);
            }

            return redirect('customer')->with('success', 'Customer ' . $customer->name . ' deleted!');
        } catch (\Exception $e) {
            $errorMessage = "";
            if($e->errorInfo[0] == "23000") {
                $errorMessage = "Data still connected to other tables";
            }
            return redirect('customer')->with('failed', 'Customer ' . $customer->name . ' cannot be deleted! ' . $errorMessage);
        }
    }
}
