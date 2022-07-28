<?php

namespace App\Repositories\General;

use Illuminate\Http\Request;

class Arbpg
{


//    const TEST_ENDPOINT = 'https://digitalpayments.alrajhibank.com.sa/pg/payment/hosted.htm';
//    const ARB_MERCHANT_HOSTED_ENDPOINT_PRODUCTION = 'https://digitalpayments.alrajhibank.com.sa/pg/payment/tranportal.htm';
//
//
//    const ARB_SUCCESS_STATUS = 'CAPTURED';
//    const Tranportal_ID = "cDJgl9I0Y85uf7U";
//    const Tranportal_Password = 'S3v#geCv1$!4G3K';
//    const resource_key = "20109082530220109082530220109082";
//    const website_url = 'https://enayaapp.com';
///////////////////////////////////////////////////////////

    const PRODUCTION = true;
    const PRODUCTION_HOSTED_ENDPOINT = 'https://securepayments.alrajhibank.com.sa/pg/payment/tranportal.htm';
    const PRODUCTION_TRANPORTAL_ID = "EdoN80FK6oj3j7F";
    const PRODUCTION_TRANPORTAL_PASSWORD = "L#6eR66q5cFO@k!";
    const PRODUCTION_RESOURCE_KEY = "21094399262421094399262421094399";

    /*test*/
    const TEST_ENDPOINT = 'https://securepayments.alrajhibank.com.sa/pg/payment/hosted.htm';
    const TEST_TRANPORTAL_ID = "EdoN80FK6oj3j7F";
    const TEST_TRANPORTAL_PASSWORD = "L#6eR66q5cFO@k!";
    const TEST_RESOURCE_KEY = "21094399262421094399262421094399";
    const ARB_SUCCESS_STATUS = 'CAPTURED';
    const website_url = 'https://dhameen.com.sa';


    public function initiatePayment($request)
    {
        $card_number = $request->card_number;
        $expiry_month = $request->expiry_month;
        $expiry_year = $request->expiry_year;
        $cvv = $request->cvv;
        $card_holder = $request->holder_name;
        $amount = $request->amount;
        $order_id = $request->order_id;
        $platform = $request->platform;


        $arbPg = new Arbpg();

//        $arbPg->test();

        $url = $arbPg->getmerchanthostedPaymentid(
            $card_number,
            $expiry_month,
            $expiry_year,
            $cvv,
            $card_holder,
            $order_id,
            $amount,
            $platform);


        return response()->json($url);


// $url= $ARB_PAYMENT_ENDPOINT_TESTING . $paymentId; //in Production use Production End Point
        return response()->redirectTo($url, 302);

    }


    public function paymentResult(Request $request)
    {

        $trandata = $request->trandata;
//        var_dump($trandata);
        $arbPg = new Arbpg();

        $result = $arbPg->getresult($trandata);
        if ($result['status'] == 'success') {
            if ($result['orderType'] == 1) {
                return $this->shopPayment($result['orderId']);
            } elseif ($result['orderType'] == 2) {
                return $this->pricingSendPaymentOrder($result['orderId']);
            } elseif ($result['orderType'] == 3) {
                return $this->damageSendPaymentOrder($result['orderId']);
            }
        }

        return redirect('/api/v1/payment-error');

    }

    public function getmerchanthostedPaymentid(
        $card_number, $expiry_month, $expiry_year, $cvv,
        $card_holder, $order_id, $amount, $status, $apiUrl, $webUrl, $platform = 'web', $actionType , $user_id = null)
    {
        $exp_year = $expiry_year;
        $amount = $amount ?: 0;
        $trackId = $order_id . mt_rand(0000, 9999); // TODO: Change to real value
        $data = [
            "id" => $this::PRODUCTION ? $this::PRODUCTION_TRANPORTAL_ID : $this::TEST_TRANPORTAL_ID,
            "password" => $this::PRODUCTION ? $this::PRODUCTION_TRANPORTAL_PASSWORD : $this::TEST_TRANPORTAL_PASSWORD,
            "expYear" => $exp_year,
            "expMonth" => $expiry_month,
            "member" => $card_holder,
            "cvv2" => $cvv,
            "cardNo" => $card_number,
            "cardType" => "C",
            "action" => "1",
            "udf1" => $order_id,
            "udf2" => $user_id,
            "udf3" => $status,
            "udf4" => $actionType,
            "currencyCode" => "682",
            "responseURL" => $platform == 'web' ? self::website_url . $webUrl : self::website_url . $apiUrl,
            "errorURL" => $platform == 'web' ? self::website_url . '/payment-error' : self::website_url . '/api/v1/payment-error',
            "trackId" => $trackId,
            "amt" => $amount,
        ];
        $data = json_encode($data, JSON_UNESCAPED_SLASHES);
        $wrappedData = $this->wrapData($data);
        $encData = [
            "id" => $this::PRODUCTION ? $this::PRODUCTION_TRANPORTAL_ID : $this::TEST_TRANPORTAL_ID,
            "trandata" => $this->aesEncrypt($wrappedData),
            "responseURL" => $platform == 'web' ? self::website_url . $webUrl : self::website_url . $apiUrl,
            "errorURL" => $platform == 'web' ? self::website_url . '/payment-error' : self::website_url . '/api/v1/payment-error',
        ];
        $wrappedData = $this->wrapData(json_encode($encData, JSON_UNESCAPED_SLASHES));
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this::PRODUCTION ? $this::PRODUCTION_HOSTED_ENDPOINT : $this::TEST_ENDPOINT,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $wrappedData,
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Accept-Language: application/json',
                'Content-Type: application/json',
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        // parse response and get id
        $response_data = json_decode($response, true)[0];
        if ($response_data["status"] == "1") {
            $url = "https:" . explode(":", $response_data["result"])[2];
            $paymentId = explode(":", $response_data["result"])[0];
            return ['status' => 200, 'url' => $url];
        } else {
            // handle error either refresh on contact merchant
//            return $this->getResult($response_data['trandata']);
            return ['status' => 402, 'reason' => $response_data];
        }

    }

    public function getPaymentId()
    {
        $plainData = $this->getRequestData();
        $wrappedData = $this->wrapData($plainData);
        $encData = [
            "id" => $this::Tranportal_ID,
            "trandata" => $this->aesEncrypt($wrappedData),
            "errorURL" => "https://tocars.net/api/v1/payment-result",
            "responseURL" => "https://tocars.net/api/v1/payment-result",
        ];
        $wrappedData = $this->wrapData(json_encode($encData, JSON_UNESCAPED_SLASHES));
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this::TEST_ENDPOINT,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $wrappedData,

            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Accept-Language: application/json',
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);
        print_r($response);
        curl_close($curl);

        // parse response and get id
        $data = json_decode($response, true)[0];
        print_r($data);
        if ($data["status"] == "1") {
            $id = explode(":", $data["result"])[0];
            return $id;
        } else {
            // handle error either refresh on contact merchant
            return -1;
        }
    }


    public function getResult($trandata)
    {


        $decrypted = $this->aesDecrypt($trandata);
        $raw = urldecode($decrypted);
        $dataArr = json_decode($raw, true);
//        dd($dataArr);
//        var_dump($dataArr);
        if (isset($dataArr[0]['errorText'])) {
            return ["status" => 400, 'data' => $dataArr[0]];

        }
        $paymentStatus = $dataArr[0]["result"];
        if (isset($paymentStatus) && $paymentStatus === $this::ARB_SUCCESS_STATUS) {
            return ["status" => 200, 'data' => $dataArr[0]];

        }
        return ["status" => 400, 'data' => $dataArr[0]];

    }

    private function getRequestData()
    {

        // $this->load->model('checkout/order');

        $amount = 100;

        $trackId = (string)rand(1, 1000000); // TODO: Change to real value

        $data = [
            "id" => $this::Tranportal_ID,
            "password" => $this::Tranportal_Password,
            "action" => "1",
            "currencyCode" => "682",
            "errorURL" => "https://tocars.net/api/v1/payment-result",
            "responseURL" => "https://tocars.net/api/v1/payment-result",
            "trackId" => $trackId,
            "amt" => $amount,

        ];

        $data = json_encode($data, JSON_UNESCAPED_SLASHES);
        //var_dump($data);
        return $data;
    }


    private function wrapData($data)
    {
        $data = <<<EOT
[$data]
EOT;
        return $data;
    }

    private function aesEncrypt($plainData)
    {
        $key = $this::PRODUCTION ? $this::PRODUCTION_RESOURCE_KEY : $this::TEST_RESOURCE_KEY;
        $iv = "PGKEYENCDECIVSPC";
        $str = $this->pkcs5_pad($plainData);
        $encrypted = openssl_encrypt($str, "aes-256-cbc", $key, OPENSSL_ZERO_PADDING, $iv);
        $encrypted = base64_decode($encrypted);
        $encrypted = unpack('C*', ($encrypted));
        $encrypted = $this->byteArray2Hex($encrypted);
        $encrypted = urlencode($encrypted);
        return $encrypted;
    }

    private function pkcs5_pad($text, $blocksize = 16)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    private function byteArray2Hex($byteArray)
    {
        $chars = array_map("chr", $byteArray);
        $bin = join($chars);
        return bin2hex($bin);
    }

    private function aesDecrypt($code)
    {
        $code = $this->hex2ByteArray(trim($code));
        $code = $this->byteArray2String($code);
        $iv = "PGKEYENCDECIVSPC";
        $key = self::PRODUCTION ? self::PRODUCTION_RESOURCE_KEY : self::TEST_RESOURCE_KEY;
        $code = base64_encode($code);
        $decrypted = openssl_decrypt($code, 'AES-256-CBC', $key, OPENSSL_ZERO_PADDING,
            $iv);

        return $this->pkcs5_unpad($decrypted);
    }

    private function pkcs5_unpad($text)
    {
        $pad = ord($text[strlen($text) - 1]);
        if ($pad > strlen($text)) return false;
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) return false;
        return substr($text, 0, -1 * $pad);
    }

    private function hex2ByteArray($hexString)
    {
        $string = hex2bin($hexString);
        return unpack('C*', $string);
    }

    private function byteArray2String($byteArray)
    {
        $chars = array_map("chr", $byteArray);
        return join($chars);
    }

}
