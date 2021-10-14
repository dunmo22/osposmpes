<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Item_taxes class
 */
class Item_taxes extends Model
{
	/**
	 * Gets tax info for a particular item
	 */
	public function get_info(int $item_id): array
	{
		$builder = $this->db->table('items_taxes');
		$builder->where('item_id',$item_id);

		//return an array of taxes for an item
		return $builder->get()->getResultArray();
	}

	/**
	 * Inserts or updates an item's taxes
	 */
	public function save(array &$items_taxes_data, int $item_id): bool
	{
		$success = TRUE;

		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->transStart();

		$this->delete($item_id);

		foreach($items_taxes_data as $row)
		{
			$row['item_id'] = $item_id;
			$builder = $this->db->table('items_taxes');
			$success &= $builder->insert($row);
		}

		$this->db->transComplete();

		$success &= $this->db->transStatus();

		return $success;
	}

	/**
	 * Saves taxes for multiple items
	 */
	public function save_multiple(array &$items_taxes_data, string $item_ids): bool	//TODO: investigate why this is sent as a : delimited string rather than an array.
	{
		$success = TRUE;

		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->transStart();

		foreach(explode(':', $item_ids) as $item_id)
		{
			$this->delete($item_id);

			foreach($items_taxes_data as $row)
			{
				$row['item_id'] = $item_id;
				$builder = $this->db->table('items_taxes');
				$success &= $builder->insert($row);
			}
		}

		$this->db->transComplete();

		$success &= $this->db->transStatus();

		return $success;
	}

	/**
	 * Deletes taxes given an item
	 */
	public function delete(int $item_id = null, bool $purge = false): bool
	{
		$builder = $this->db->table('items_taxes');
		return $builder->delete(['item_id' => $item_id]);
	}
}
?>
