<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $users = $this->userRepository->showUser($request);
        $customers = $this->userRepository->showCustomer($request);
        return view('user.index', compact('users', 'customers'));
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(StoreUserRequest $request)
    {
        $user = $this->userRepository->store($request);

        // Log event: User created
        Log::info('User created', ['user_id' => $user->id, 'email' => $user->email]);

        return redirect()->route('user.index')->with('success', 'User ' . $user->name . ' created');
    }

    // Method to show the user's information
    public function show(User $user)
    {
        if ($user->role === "Customer") {
            $customer = Customer::where('user_id', $user->id)->first();

            // Decrypt the sensitive data
            $customer->address = Crypt::decryptString($customer->address);
            $customer->job = Crypt::decryptString($customer->job);

            return view('customer.show', compact('customer'));
        }
        return view('user.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('user.edit', ['user' => $user]);
    }

    public function update(User $user, UpdateCustomerRequest $request)
    {
        $user->update($request->all());

        // Log event: User updated
        Log::info('User updated', ['user_id' => $user->id, 'email' => $user->email]);

        return redirect()->route('user.index')->with('success', 'User ' . $user->name . ' updated!');
    }

    public function destroy(User $user)
    {
        try {
            $userName = $user->name;
            $user->delete();

            // Log event: User deleted
            Log::info('User deleted', ['user_id' => $user->id, 'email' => $user->email]);

            return redirect()->route('user.index')->with('success', 'User ' . $userName . ' deleted!');
        } catch (\Exception $e) {
            return redirect()->route('user.index')->with('failed', 'Customer ' . $user->name . ' cannot be deleted! Error Code:' . $e->errorInfo[1]);
        }
    }
}
