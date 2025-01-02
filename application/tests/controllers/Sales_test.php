<?php


class Sales_test extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->resetInstance();
    }

    public function test_index_whenNotLoggedIn()
    {
        $this->request('GET', 'sales/index');
        $this->assertRedirect('login');
    }

    public function test_index_whenLoggedIn()
    {
        /*$this->request->setCallable(function () {
            $auth = $this->getDouble(
                'Employee', ['is_logged_in' => TRUE]
            );

            load_class_instance('Employee', $auth);
        });*/

        MonkeyPatch::patchMethod('Employee', ['is_logged_in' => TRUE, 'has_module_grant' => TRUE]);

        $output = $this->request('GET', 'sales/index');
        $this->assertRedirect('login');

//        $this->assertContains('ble ble ble ble', $output);
    }
}
