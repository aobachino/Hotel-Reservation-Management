<?php

// app/Http/Controllers/Auth/RegisterController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    // Method to display the registration form
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Method to handle the registration form submission
    public function register(Request $request)
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
            return redirect()->route('register')->withErrors($validator)->withInput();
        }

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
            'address' => $input['address'],
            'job' => $input["job"],
            'birthdate' => $input["birthdate"],
            'user_id' => $user->id,
            'gender' => $input["gender"],
        ]);

        Log::info('Registration successful', [
            'user_id' => $user->id,
            'email' => $user->email
        ]);
        
        // Redirect to a success page or home page
        return redirect('/login')->with('success', 'Registration successful!');
    }
}
