<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Payment;
use App\Models\Transaction;
use App\Repositories\PaymentRepository;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public $paymentRepository;

    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function index()
    {
        $payments = Payment::orderBy('id','DESC')->paginate(5);
        return view('payment.index', compact('payments'));
    }

    public function create(Transaction $transaction)
    {
        return view('transaction.payment.create', compact('transaction'));
    }

    public function store(Transaction $transaction, Request $request)
    {
        $insufficient = $transaction->getTotalPrice() - $transaction->getTotalPayment();
        $request->validate([
            'payment' => 'required|numeric|lte:' . $insufficient
        ]);

        $this->paymentRepository->store($request, $transaction, 'Payment');

        return redirect()->route('transaction.index')->with('success', 'Transaction room ' . $transaction->room->number . ' success, ' . Helper::convertToRinggit($request->payment) . ' paid');
    }

    public function invoice(Payment $payment)
    {
        return view('payment.invoice', compact('payment'));
    }


    public function checkout()
     {
         \Stripe\Stripe::setApiKey(config('stripe.sk'));
     
         $session = \Stripe\Checkout\Session::create([
             'line_items' => [
                 [
                     'price_data' => [
                         'currency' => 'myr',
                         'product_data' => [
                             'name' => 'The Payment!!!',
                         ],
                         'unit_amount' => 31000000, // 5 pounds
                         
                     ],
                     'quantity' => 1,
                 ],
             ],
             'mode' => 'payment',
             'success_url' => route('success'),
            //  'cancel_url' => route('create'),
         ]);
     
         return redirect()->away($session->url);
     }
     


     public function success()
     {
        $payments = Payment::orderBy('id','DESC')->paginate(5);
        return view('payment.index', compact('payments'));
     }
         





}
