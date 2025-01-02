<?php
/**
 * @backupGlobals disabled
 */
class Barcode_lib_test extends UnitTestCase
{
	public function setUp(): void
	{
		$this->resetInstance();

		$this->obj = $this->newLibrary('Barcode_lib');
	}

	public function test_barcode_weight_first()
	{
        $this->CI->config->set_item('barcode_formats', json_encode(array("02(\d{5})(\w{6})")));

		$item_number = "0250000123456";
		$quantity = 0;
		$this->obj->parse_barcode_fields($quantity, $item_number);

		echo $quantity;
		$this->assertEquals(123.456, $quantity);
		$this->assertEquals("50000", $item_number);
	}

	public function test_barcode_weight_last()
	{
        $this->CI->config->set_item('barcode_formats', json_encode(array("02(\w{6})(\d{5})")));

		$item_number = "0212345650001";
		$quantity = 0;
		$this->obj->parse_barcode_fields($quantity, $item_number);

		$this->assertEquals(50.001, $quantity);
		$this->assertEquals(123456, $item_number);
	}
}