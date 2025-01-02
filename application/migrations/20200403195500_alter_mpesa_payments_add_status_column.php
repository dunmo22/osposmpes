<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Alter_mpesa_payments_add_status_column extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column('mpesa_payments', [
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
                'default' => 'pending'
            ]
        ]);
    }

    public function down()
    {
        $this->dbforge->drop_column('mpesa_payments', 'status');
    }


}