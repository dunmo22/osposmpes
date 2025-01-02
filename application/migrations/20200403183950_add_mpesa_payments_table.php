<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Migration_Add_Mpesa_Payments_Table extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'BIGINT',
                'unsigned' => true,
                'auto_increment' => true
            ],
            'transaction_id' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],
            'amount' => [
                'type' => 'INT'
            ],
            'payer_phone_number' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],
            'payer_first_name' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],
            'payer_middle_name' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ],
            'payer_last_name' => [
                'type' => 'VARCHAR',
                'constraint' => '100'
            ]
        ]);

        $this->dbforge->add_field('`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->add_key('id', true);

        $this->dbforge->create_table('mpesa_payments');
    }

    public function down()
    {
        $this->dbforge->drop_table('mpesa_payments');
    }
}