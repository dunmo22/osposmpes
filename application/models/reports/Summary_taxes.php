<?php
require_once("Summary_report.php");
class Summary_taxes extends Summary_report
{
	function __construct()
	{
		parent::__construct();
	}

	public function getDataColumns()
	{
		return array($this->lang->line('reports_tax_percent'), $this->lang->line('reports_count'), $this->lang->line('reports_subtotal'), $this->lang->line('reports_total'), $this->lang->line('reports_tax'));
	}

	public function getData(array $inputs)
	{
		$where = '';
		if(empty($this->config->item('filter_datetime_format')))
		{
			$where .= 'WHERE DATE(sale_time) BETWEEN ' . $this->db->escape($inputs['start_date']) . ' AND ' . $this->db->escape($inputs['end_date']) .' ';
		}
		else
		{
			$where .= 'WHERE sale_time BETWEEN ' . $this->db->escape(str_replace('%20',' ', $inputs['start_date'])) . ' AND ' . $this->db->escape(str_replace('%20',' ', $inputs['end_date'])) .' ';
		}

		if ($inputs['sale_type'] == 'sales')
		{
			$where .= 'AND quantity_purchased > 0';
		}
		elseif ($inputs['sale_type'] == 'returns')
		{
			$where .= 'AND quantity_purchased < 0';
		}

		if ($inputs['location_id'] != 'all')
		{
			$where .= 'AND item_location = '. $this->db->escape($inputs['location_id']);
		}

		if($this->config->item('tax_included'))
		{
			$sale_total = '(sales_items.item_unit_price * sales_items.quantity_purchased * (1 - sales_items.discount_percent / 100))';
			$sale_subtotal = '(sales_items.item_unit_price * sales_items.quantity_purchased * (1 - sales_items.discount_percent / 100) * (100 / (100 + sales_items_taxes.percent)))';
			$sale_tax = '(sales_items.item_unit_price * sales_items.quantity_purchased * (1 - sales_items.discount_percent / 100) * (1 - 100 / (100 + sales_items_taxes.percent)))';
		}
		else
		{
			$sale_total = '(sales_items.item_unit_price * sales_items.quantity_purchased * (1 - sales_items.discount_percent / 100) * (1 + (sales_items_taxes.percent / 100)))';
			$sale_subtotal = '(sales_items.item_unit_price * sales_items.quantity_purchased * (1 - sales_items.discount_percent / 100))';
			$sale_tax = '(sales_items.item_unit_price * sales_items.quantity_purchased * (1 - sales_items.discount_percent / 100) * (sales_items_taxes.percent / 100))';
		}

		$decimals = totals_decimals();

		$query = $this->db->query("SELECT percent, count(*) AS count, ROUND(SUM(subtotal), $decimals) AS subtotal, ROUND(SUM(total), $decimals) AS total, ROUND(SUM(tax), $decimals) AS tax
			FROM (
				SELECT
					CONCAT(IFNULL(ROUND(percent, $decimals), 0), '%') AS percent,
					$sale_subtotal AS subtotal,
					IFNULL($sale_total, $sale_subtotal) AS total,
					IFNULL($sale_tax, 0) AS tax
					FROM " . $this->db->dbprefix('sales_items') . ' AS sales_items
					INNER JOIN ' . $this->db->dbprefix('sales') . ' AS sales
						ON sales_items.sale_id = sales.sale_id
					LEFT OUTER JOIN ' . $this->db->dbprefix('sales_items_taxes') . ' AS sales_items_taxes
						ON sales_items.sale_id = sales_items_taxes.sale_id AND sales_items.item_id = sales_items_taxes.item_id AND sales_items.line = sales_items_taxes.line '
					. " $where
				) AS temp_taxes
			GROUP BY percent"
		);

		return $query->result_array();
	}
}
?>