<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Mpesa_model extends CI_Model
{
    public function savePayment(array $payment)
    {
        if (! $this->db->insert('mpesa_payments', $payment))
            throw new Exception($this->db->error()['message']);
    }

    public function search_payments(SearchParam $search_param, string $search_query): array
    {
        $column = $this->getDatabaseColumn($search_param);
        $this->db->where($column, $search_query);
        $this->db->where('status', 'pending');
        $this->db->where('created_at > DATE_SUB(NOW(), INTERVAL 6 HOUR)'); // should be 10 MINUTE or less
        $query = $this->db->get('mpesa_payments');
        return $query->result();
    }

    private function getDatabaseColumn(SearchParam $param): string {
        switch (true) {
            case $param instanceof AMOUNT:
                return 'amount';
            case $param instanceof TRANSACTION_ID:
                return 'transaction_id';
            case $param instanceof PHONE_NUMBER:
                return 'payer_phone_number';
            default:
                throw new InvalidArgumentException('Invalid SearchParam provided');
        }
    }

    public function getPayment($transactionId)
    {
        $this->db->where('transaction_id', $transactionId);
        $query = $this->db->get('mpesa_payments');
        $result = $query->result();

        if (count($result) < 1)
            throw new Exception('No mpesa transaction with matching transaction id found');

        if (count($result) > 1) {
            log_message('error', 'There exists Mpesa payments with similar `transaction_id`s');
            throw new Exception('There exists Mpesa payments with similar `transaction_id`s');
        }

        if ($result[0]->status !== 'pending') {
            log_message('error', 'Non-pending mpesa payment submitted for re-use');
            throw new Exception('No unused mpesa transaction with matching transaction id found');
        }

        return $result[0];
    }

    public function markPaymentAsUsed($transactionId)
    {
        $str = $this->db->update_string('mpesa_payments', ['status' => 'used'], "transaction_id = '$transactionId'");
        $query = $this->db->query($str);
        if (! $query)
            throw new Exception('Unable to edit Mpesa payment');
    }
}




class SearchParam {}
class AMOUNT extends SearchParam {}
class TRANSACTION_ID extends SearchParam {}
class PHONE_NUMBER extends SearchParam {}