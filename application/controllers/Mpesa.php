<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once("Secure_Controller.php");

class Mpesa extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('mpesaService');
        $this->load->model('mpesa_model');
    }

    public function payment()
    {
        error_log(json_encode($this->input->raw_input_stream));
        if (!$this->checkPaymentRequestValid($this->input->raw_input_stream)) {
            echo json_encode([
                "ResponseCode" => "1",
                "ResponseDesc" => "Invalid Request Body"
            ]);
            return;
        }

        echo json_encode([
            "ResponseCode" => "0",
            "ResponseDesc" => "success"
        ]);

        $response = json_decode($this->input->raw_input_stream);

        try {
            $this->mpesa_model->savePayment([
                'transaction_id' => $response->TransID,
                'amount' => $response->TransAmount,
                'payer_phone_number' => $response->MSISDN,
                'payer_first_name' => $response->FirstName,
                'payer_middle_name' => $response->MiddleName,
                'payer_last_name' => $response->LastName
            ]);
        } catch (Exception $e) {
            log_message('error', 'Error saving mpesa payment. ' . $e->getMessage());
        }
    }

    private function checkPaymentRequestValid($responseBody)
    {
        if (!$jsonResponseBody = json_decode($responseBody))
            return false;

        return isset($jsonResponseBody->TransID) &&
            isset($jsonResponseBody->TransAmount) &&
            isset($jsonResponseBody->MSISDN) &&
            isset($jsonResponseBody->FirstName) &&
            isset($jsonResponseBody->MiddleName) &&
            isset($jsonResponseBody->LastName);
    }

    public function search_payments()
    {
        $this->form_validation->set_rules('search_param', 'Search parameter',
            'required|in_list[amount,transaction_id,phone_number]');

        switch ($this->input->post('search_param')) {
            case 'amount':
                $searchParamRules = 'numeric';
                break;
            case 'transaction_id':
                $searchParamRules = 'alpha_numeric|exact_length[10]';
                break;
            case 'phone_number':
                $searchParamRules = 'callback_phone_number|callback_format_phone_number';
                break;
            default:
                $searchParamRules = '';
        }
        $this->form_validation->set_rules('search_query', 'Search query', 'required|' . $searchParamRules);

        if ($this->form_validation->run()) {
            $search_param = $this->getSearchParam($this->input->post('search_param'));
            $payments = $this->mpesa_model->search_payments(
                $search_param, $this->input->post('search_query'));

            echo json_encode([
                'success' => true,
                'payments' => $payments
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => $this->form_validation->error_string()
            ]);
        }
    }

    function phone_number($input): bool
    {
        if (strlen($input) === 10)
            return $input[0] === '0';
        elseif (strlen($input) === 12)
            return $input[0] === '2';
        else
            return false;
    }

    function format_phone_number($phoneNumber): string {
        if ($phoneNumber[0] === '0')
            return '254' . substr($phoneNumber, 1);
        else
            return $phoneNumber;
    }

    function getSearchParam($input): SearchParam
    {
        switch ($input) {
            case 'amount':
                return new AMOUNT();
            case 'transaction_id':
                return new TRANSACTION_ID();
            default:
                return new PHONE_NUMBER();
        }
    }

    public function test()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules(
            'mpesa_test_phone_number',
            'Mpesa phone number for test',
            'regex_match[/^0([0-9]){9}$/]'
        );

        if ($this->form_validation->run() === false) {
            echo json_encode([
                'success' => false,
                'message' => $this->form_validation->error('mpesa_test_phone_number')
            ]);
        } else {
            $phoneNumber = $this->input->post('mpesa_test_phone_number');
            $formattedPhoneNumber = '254' . substr($phoneNumber, 1);
            $this->mpesaService->simulateC2BPayment($formattedPhoneNumber);
        }

    }
}