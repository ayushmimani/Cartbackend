<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Redirect;
// use Illuminate\Support\Facades\URL;
// use Illuminate\Support\Facades\Session;
// use PayPal\Rest\ApiContext;
// use PayPal\Auth\OAuthTokenCredential;
// use PayPal\Api\Amount;
// use PayPal\Api\Payer;
// use PayPal\Api\Payment;
// use PayPal\Api\RedirectUrls;

// use PayPal\Api\Transaction;
// use PayPal\Api\PaymentExecution;




// class PayPalController extends Controller
// {
    //
    // private $_api_context;

    // public function __construct(){
    //     dd(config('paypal'));
    //    $paypal = config('paypal'); 

    //    $this->_api_context= new ApiContext(new OAuthTokenCredential($paypal['client_id'],$paypal['secret']));
    //    $this->_api_context->setConfig($paypal['settings']);
    // }

    // public function payWithPayPal(){
    //      return view('paypal');
    // }

    // public function PostPaymentWithPapPal(){
       
    //     $payer = new Payer();
    //     $payer->setPaymentMethod('paypal');

    //     $amount = new Amount();
    //     $amount->setTotal($request->input('amount'));
    //     $amount->setCurrency('USD');

    //     $transaction = new RedirectUrls();
    //     $redirectUrls->setReturnUrl(URL::route('status'))
    //                 ->setCancelUrl(URL::route('status'));

    //     $payment = new Payment();
    //     $payment->setIntent('sale')
    //             ->setPayer($payer)
    //             ->setTransactions(array($transaction))
    //             ->setRedirectUrls($redirectUrls);
                
    //     try{
    //         $payment->create($this->_api_context);
    //     }catch(\PayPal\Exeception\PPConnectionException $ex){
    //          if(\Config::get('app.debug')){
    //             Session::put('error','Connection timeout');
    //             return Redirect::route('payWithPayPal');
    //          }else{
    //             Session::put('error','Some error occur');
    //             return Redirect::route('payWithPayPal');
    //          }
    //     }     

    //     foreach($payment->getLinks() as $link){
    //         if($link->getRel()=='approval_url'){
    //             $redirect_url =$link->getHref();
    //             break;
    //         }
    //     }

    //     Session::put('paypal_payment_id',$payment->getId);

    //     if(isset($redirect_url)){
    //         return REdirect::away($redirect_url);
    //     }

    //     Session::put('error','Unknown error occured');
    //     return Redirect::route('payWithPayPal');

    // }

    // public function getPaymentStatus(Request $request){
    //     $input= $request->all();
        
    //     $paymentId = $input['paymentId'];
    //     $payerId = $input['payerId'];
    //     $token = $input['token'];

    //     if(empty($payerId) || empty($token)){
    //         Session::put('error','Payment failed');
    //         return Redirect::route('payWithPayPal');
    //     }

    //     $payment  = Payment::get($paymentId,$this->_api_context);

    //     $execution = new PaymentExecution();
    //     $execution->setPayerId($payerId);

    //     $result =$payment->execute($execution,$this->_api_context);
    //     dd($result);
    // }
//}




  
namespace App\Http\Controllers;
  
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Http\Request;
  
class PayPalController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
        return view('paypal');
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function payment(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
  
        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypal.payment.success'),
                "cancel_url" => route('paypal.payment/cancel'),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => "100.00"
                    ]
                ]
            ]
        ]);
  
        if (isset($response['id']) && $response['id'] != null) {
  
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return redirect()->away($links['href']);
                }
            }
  
            return redirect()
                ->route('cancel.payment')
                ->with('error', 'Something went wrong.');
  
        } else {
            return redirect()
                ->route('create.payment')
                ->with('error', $response['message'] ?? 'Something went wrong.');
        }
    
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function paymentCancel()
    {
        return redirect()
              ->route('paypal')
              ->with('error', $response['message'] ?? 'You have canceled the transaction.');
    }
  
    /**
     * Write code on Method 
     * Written by Appfinz Technologies
     * @return response()
     */
    public function paymentSuccess(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);
  
        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            return redirect()
                ->route('paypal')
                ->with('success', 'Transaction complete.');
        } else {
            return redirect()
                ->route('paypal')
                ->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }
}
