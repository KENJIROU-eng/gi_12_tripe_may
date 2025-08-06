<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal;
use Illuminate\Support\Facades\Redirect;
use App\Models\Pay;
use Illuminate\Support\Facades\Auth;

class PayPalController extends Controller
{
    private $pay;

    public function __construct(Pay $pay) {
        $this->pay = $pay;
    }

    public function createTransaction($itinerary_id, $total, $user_id)
    {
        $total = number_format((float)$total, 2, '.', '');
        $provider = resolve(PayPal::class);

        // API資格情報の設定
        $provider->setApiCredentials([
            'mode' => 'sandbox',
            'sandbox' => [
                'client_id'     => env('PAYPAL_SANDBOX_CLIENT_ID'),
                'client_secret' => env('PAYPAL_SANDBOX_CLIENT_SECRET'),
                'app_id'        => '',
            ],
            'live' => [
                'client_id'     => '',
                'client_secret' => '',
                'app_id'        => '',
            ],
            'payment_action' => 'CAPTURE',
            'currency'       => 'USD',
            'notify_url'     => '',
            'locale'         => 'ja-JP',
            'validate_ssl'   => true,
        ]);

        // アクセストークンの取得
        $token = $provider->getAccessToken();

        // 支払い注文作成
        $order = $provider->createOrder([
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $total
                    ]
                ]
            ],
            "application_context" => [
                "cancel_url" => route('goDutch.finalize', $itinerary_id),
                "return_url" => route('paypal.success', ['itinerary_id'=> $itinerary_id, 'user_id' => $user_id])
            ]
        ]);

        // PayPal決済ページへリダイレクト
        return Redirect::away(collect($order['links'])->firstWhere('rel', 'approve')['href']);
    }

    public function captureTransaction(Request $request, $itinerary_id, $user_id)
    {
        $provider = resolve(PayPal::class);
        // API資格情報の設定
        $provider->setApiCredentials([
            'mode' => 'sandbox',
            'sandbox' => [
                'client_id'     => env('PAYPAL_SANDBOX_CLIENT_ID'),
                'client_secret' => env('PAYPAL_SANDBOX_CLIENT_SECRET'),
                'app_id'        => '',
            ],
            'live' => [
                'client_id'     => '',
                'client_secret' => '',
                'app_id'        => '',
            ],
            'payment_action' => 'CAPTURE',
            'currency'       => 'USD',
            'notify_url'     => '',
            'locale'         => 'ja-JP',
            'validate_ssl'   => true,
        ]);

        // アクセストークンの取得
        $token = $provider->getAccessToken();
        // PayPalが返してきた token（支払い注文ID）を使って支払いを**確定（キャプチャ）**する
        // PayPal上でユーザーが支払い承認したあと、この関数に token が返ってきます
        $response = $provider->capturePaymentOrder($request->token);

        if ($response['status'] == 'COMPLETED') {
            $amount = $response['purchase_units'][0]['payments']['captures'][0]['amount'];
            $value = $amount['value'];
            $this->pay->user_id = Auth::User()->id;
            $this->pay->itinerary_id = $itinerary_id;
            $this->pay->Price = $value;
            $this->pay->user_get_id = $user_id;
            $this->pay->save();
            return redirect()->route('goDutch.finalize', $itinerary_id);
        } else {
            return redirect()->route('goDutch.finalize', $itinerary_id);
        }
    }

}

//参考　支払い先指定

// 'payee' => [
//             'email_address' => $receiverPaypalEmail
//         ]


