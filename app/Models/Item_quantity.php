<?php

namespace App\Models;

use CodeIgniter\Model;
use stdClass;

/**
 * Item_quantity class
 */
class Item_quantity extends Model
{
    public function exists(int $item_id, int $location_id): bool
    {
        $builder = $this->db->table('item_quantities');
        $builder->where('item_id', $item_id);
        $builder->where('location_id', $location_id);

        return ($builder->get()->getNumRows() == 1);	//TODO: ===
    }

    public function save(array $location_detail, int $item_id, int $location_id): bool
    {
        if(!$this->exists($item_id, $location_id))
        {
			$builder = $this->db->table('item_quantities');
        	return $builder->insert($location_detail);
        }

		$builder = $this->db->table('item_quantities');
        $builder->where('item_id', $item_id);
        $builder->where('location_id', $location_id);

        return $builder->update($location_detail);
    }

    public function get_item_quantity(int $item_id, int $location_id)
    {
        $builder = $this->db->table('item_quantities');
        $builder->where('item_id', $item_id);
        $builder->where('location_id', $location_id);
        $result = $builder->get()->getRow();

        if(empty($result) == TRUE)
        {
            //Get empty base parent object, as $item_id is NOT an item
            $result = new stdClass();

            //Get all the fields from items table (TODO: to be reviewed)
            foreach($this->db->getFieldNames('item_quantities') as $field)
            {
                $result->$field = '';
            }

            $result->quantity = 0;
        }

        return $result;
    }

	/**
	 * changes to quantity of an item according to the given amount.
	 * if $quantity_change is negative, it will be subtracted,
	 * if it is positive, it will be added to the current quantity
	 */
	public function change_quantity(int $item_id, int $location_id, int $quantity_change): bool
	{
		$quantity_old = $this->get_item_quantity($item_id, $location_id);
		$quantity_new = $quantity_old->quantity + $quantity_change;
		$location_detail = ['item_id' => $item_id, 'location_id' => $location_id, 'quantity' => $quantity_new];

		return $this->save($location_detail, $item_id, $location_id);	//TODO: need to sort out the unhandled reflection exception error.  Probably needs to be placed in a try catch to catch the exception  and do something with it.
	}

	/**
	 * Set to 0 all quantity in the given item
	 */
	public function reset_quantity(int $item_id): bool
	{
		$builder = $this->db->table('item_quantities');
		$builder->where('item_id', $item_id);

        return $builder->update(['quantity' => 0]);
	}

	/**
	 * Set to 0 all quantity in the given list of items
	 */
	public function reset_quantity_list(array $item_ids): bool
	{
		$builder = $this->db->table('item_quantities');
		$builder->whereIn('item_id', $item_ids);

        return $builder->update(['quantity' => 0]);
	}
}
?>
