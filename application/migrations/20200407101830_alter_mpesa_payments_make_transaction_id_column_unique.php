<?php  defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_alter_mpesa_payments_make_transaction_id_column_unique extends CI_Migration {
    public function up()
    {
        $this->dbforge->modify_column('mpesa_payments', [
            'transaction_id' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'unique' => true
            ]
        ]);
    }

    public function down()
    {
        $this->dbforge->modify_column('mpesa_payments', [
            'transaction_id' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'unique' => false
            ]
        ]);
    }
}
