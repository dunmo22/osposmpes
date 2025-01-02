<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MpesaService extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function registerC2BCallbackUrl(string $shortCode, string $consumerKey, string $consumerSecret)
    {
        $environment = $this->config->item('mpesa_env');
        $url = $this->getC2BUrlRegistrationEndpointUrl($environment);
        $accessToken = $this->generateToken($environment, $consumerKey, $consumerSecret);

        $requestHeaders = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken
        ];
        $requestData = [
            'ValidationURL' => $this->config->item('mpesa_callback_url'),
            'ConfirmationURL' => $this->config->item('mpesa_callback_url'),
            'ResponseType' => 'Completed',
            'ShortCode' => $shortCode
        ];

        $response = $this->makeRequest($url, $requestHeaders, true, true, $requestData);
        error_log('registerCallbackURL: ' . json_encode($response));
        if (isset($response->errorCode))
            throw new Exception($response->errorMessage);
    }

    private function getC2BUrlRegistrationEndpointUrl(string $environment)
    {
        if ($environment === 'live')
            return 'https://api.safaricom.co.ke/mpesa/c2b/v1/registerurl';
        elseif ($environment === 'sandbox')
            return 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl';
        else
            throw new Exception('Invalid Mpesa environment config');
    }

    public function simulateC2BPayment($phoneNumber)
    {
        $environment = $this->config->item('mpesa_env');
        $this->checkEnvironmentValid($environment);
        $url = $environment === 'live' ?
            'https://api.safaricom.co.ke/mpesa/c2b/v1/simulate' :
            'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate';

        $token = $this->generateToken(
            $environment,
            $this->config->item('mpesa_consumer_key'),
            $this->config->item('mpesa_consumer_secret')
        );
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ];

        if (!$tillNumber = $this->config->item('mpesa_till_number'))
            throw new Exception('Mpesa till number not defined');

        $data = [
            'ShortCode' => $tillNumber,
            'CommandID' => 'CustomerPayBillOnline',
            'Amount' => 1,
            'Msisdn' => $phoneNumber,
            'BillRefNumber' => 123456
        ];

        $response = $this->makeRequest($url, $headers, true, true, $data);
        error_log('simulateC2BPayment: ' . json_encode($response));
    }

    function checkEnvironmentValid(string $environment)
    {
        if ($environment !== 'live' && $environment !== 'sandbox') {
            throw new Exception('Invalid Mpesa environment');
        }
    }

    private function generateToken(string $environment, string $consumer_key, string $consumer_secret)
    {
        $url = $environment == 'live' ?
            'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials' :
            'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        if (empty($consumer_key) || empty($consumer_secret)) {
            throw new Exception('Mpesa consumer key or secret not configured');
        }

        $credentials = base64_encode($consumer_key . ':' . $consumer_secret);
        $headers = ['Authorization: Basic ' . $credentials];

        try {
            $response = $this->makeRequest($url, $headers, false, false, null, true);
        } catch (Exception $e) {
            throw new Exception('The provided Mpesa consumer key or secret may be invalid');
        }

        return $response->access_token;
    }

    private function makeRequest(
        string $url,
        array $headers = NULL,
        bool $verifyPeer = true,
        bool $isPost = false,
        array $data = NULL,
        bool $throwOnRequestError = false
    ){
        $curl = curl_init($url);

        if ($headers)
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        if ($isPost)
            curl_setopt($curl, CURLOPT_POST, true);

        if ($data)
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $verifyPeer);

        if ($throwOnRequestError)
            curl_setopt($curl, CURLOPT_FAILONERROR, true);

        $curl_response = curl_exec($curl);

        if ($throwOnRequestError)
            $curl_error = curl_error($curl);

        curl_close($curl);

        if ($throwOnRequestError && $curl_error) {
            throw new Exception($curl_response);
        }

        return json_decode($curl_response);
    }
}

?>