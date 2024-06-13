<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use App\Http\Requests\StoreCustomerRequest;
use App\Models\Customer;
use App\Models\User;
use App\Repositories\CustomerRepository;
use App\Repositories\ImageRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    private $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    // Method to display the list of customers
    public function index(Request $request)
    {
        Log::info('Fetching customer list.');
        $customers = $this->customerRepository->get($request);
        
        // Decrypt sensitive fields for each customer
        foreach ($customers as $customer) {
            $customer->address = Crypt::decryptString($customer->address);
            $customer->job = Crypt::decryptString($customer->job);
        }

        Log::info('Customer list fetched successfully.');

        return view('customer.index', compact('customers'));
    }

    public function create(Request $request)
    {
        Log::info('Accessing customer creation page.');
        return view('customer.create');
    }

    public function store(StoreCustomerRequest $request)
    {
        Log::info('Storing new customer.');
        
        // Get the input data
        $input = $request->all();

        // Sanitize input data against XSS
        $input['name'] = htmlspecialchars(strip_tags($input['name']));
        $input['birthdate'] = htmlspecialchars(strip_tags($input['birthdate']));
        $input['address'] = htmlspecialchars(strip_tags($input['address']));
        $input['job'] = htmlspecialchars(strip_tags($input['job']));
        $input['gender'] = htmlspecialchars(strip_tags($input['gender']));

        // Validate input data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
            'password_confirmation' => 'required|same:password',
            'birthdate' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'address' => 'required|string|max:255',
            'job' => 'required|string|max:255',
        ], [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'The email has already been taken.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least :min characters.',
            'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one number, one special character, and be at least 8 characters long.',
            'password_confirmation.required' => 'The password confirmation field is required.',
            'password_confirmation.same' => 'The password confirmation does not match.',
            'birthdate.required' => 'The birthdate field is required.',
            'birthdate.date' => 'The birthdate must be a valid date.',
            'gender.required' => 'Please select a gender.',
            'gender.in' => 'Gender must be either Male or Female.',
            'address.required' => 'The address field is required.',
            'job.required' => 'The job field is required.',
        ]);
        
        if ($validator->fails()) {
            Log::warning('Validation failed for customer creation.', [
                'errors' => $validator->errors()->toArray()
            ]);
            return redirect()->route('customer.create')->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            // Encrypt sensitive data
            $encryptedAddress = Crypt::encryptString($input['address']);
            $encryptedJob = Crypt::encryptString($input['job']);

            // Create the user using Eloquent ORM against SQL injection
            $user = User::create([
                'name' => $input['name'],
                'email' => $request->email,
                'role' => 'Customer',
                'random_key' => Str::random(60),
                'password' => Hash::make($request->password),
            ]);

            // Eloquent ORM against SQL injection
            Customer::create([
                'name' => $input['name'],
                'address' => $encryptedAddress,
                'job' => $encryptedJob,
                'birthdate' => $input["birthdate"],
                'user_id' => $user->id,
                'gender' => $input['gender'],
            ]);

            DB::commit();

            Log::info('Registration successful', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            // Redirect to a success page or customer page
            return redirect('customer')->with('success', 'Registration successful!');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Registration failed', [
                'error' => $e->getMessage()
            ]);

            return redirect()->route('customer')->with('error', 'Registration failed. Please try again.');
        }
    }

    public function show(Customer $customer)
    {
        Log::info('Showing customer details.', ['customer_id' => $customer->id]);
        return view('customer.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        Log::info('Accessing customer edit page.', ['customer_id' => $customer->id]);
        $customer->address = Crypt::decryptString($customer->address);
        $customer->job = Crypt::decryptString($customer->job);
        return view('customer.edit', ['customer' => $customer]);
    }

    public function update(Customer $customer, StoreCustomerRequest $request)
    {
        // Log the start of the update process
        Log::info('Updating customer.', ['customer_id' => $customer->id]);
    
        // Validate the incoming request
        $validatedData = $request->validated();
    
        // Prepare an array to hold only the updated fields for Customer
        $updateCustomerData = [];
    
        // Check each field and update if it's included in the request
        if (isset($validatedData['name'])) {
            $updateCustomerData['name'] = $validatedData['name'];
        }
    
        if (isset($validatedData['birthdate'])) {
            $updateCustomerData['birthdate'] = $validatedData['birthdate'];
        }
    
        if (isset($validatedData['gender'])) {
            $updateCustomerData['gender'] = $validatedData['gender'];
        }
    
        if (isset($validatedData['address'])) {
            $updateCustomerData['address'] = Crypt::encryptString($validatedData['address']);
        }
    
        if (isset($validatedData['job'])) {
            $updateCustomerData['job'] = Crypt::encryptString($validatedData['job']);
        }
    
        // Update customer data with the filtered array of updated fields
        $customer->update($updateCustomerData);
    
        // Update the related User model
        try {
            $user = User::findOrFail($customer->user_id);
    
            // Prepare an array to hold only the updated fields for User
            $updateUserData = [];
    
            if (isset($validatedData['email'])) {
                $updateUserData['email'] = $validatedData['email'];
            }
    
            if (isset($validatedData['role'])) {
                $updateUserData['role'] = $validatedData['role'];
            }
    
            // Update user data with the filtered array of updated fields
            $user->update($updateUserData);
    
        } catch (\Exception $e) {
            Log::error('Failed to update related User model.', ['error' => $e->getMessage()]);
            throw ValidationException::withMessages(['error' => 'Failed to update related User model.']);
        }
    
        // Log the successful update
        Log::info('Customer and related User updated successfully.', ['customer_id' => $customer->id]);
    
        // Redirect to the customer list page with success message
        return redirect('customer')->with('success', 'Customer ' . $customer->name . ' updated!');
    }

    public function destroy(Customer $customer, ImageRepository $imageRepository)
    {
        try {
            Log::info('Attempting to delete customer.', ['customer_id' => $customer->id]);
            $user = User::find($customer->user->id);
            $avatar_path = public_path('img/user/' . $user->name . '-' . $user->id);

            $customer->delete();
            $user->delete();

            if (is_dir($avatar_path)) {
                $imageRepository->destroy($avatar_path);
            }

            Log::info('Customer deleted successfully.', ['customer_id' => $customer->id]);
            return redirect('customer')->with('success', 'Customer ' . $customer->name . ' deleted!');
        } catch (\Exception $e) {
            $errorMessage = "";
            if($e->errorInfo[0] == "23000") {
                $errorMessage = "Data still connected to other tables";
            }
            Log::error('Failed to delete customer.', ['customer_id' => $customer->id, 'error' => $e->getMessage()]);
            return redirect('customer')->with('failed', 'Customer ' . $customer->name . ' cannot be deleted! ' . $errorMessage);
        }
    }
}
