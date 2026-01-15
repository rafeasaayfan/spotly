<?php

namespace App\Services\Websites\Restaurant\Dashboard;

use App\Models\RestaurantMenuItem;
use App\Models\RestaurantMenuItemOption;

class MenuItemsService
{
    /**
     * Store the menu item options.
     *
     * @param RestaurantMenuItem $menuItem
     * @param array $groupOptions
     * @param string $action
     * @return void
     */
    public static function handleItemOptions(RestaurantMenuItem $menuItem, array $groupOptions, string $action)
    {
        try {
            $optionsToSave = [];
            $keepKeys = [];
            foreach ($groupOptions as $group) {
                if (empty($group['options'])) {
                    continue;
                }
                foreach ($group['options'] as $option) {
                    $optionsToSave[] = [
                        'menu_item_id'   => $menuItem->id,
                        'option_group_id' => $group['group_id'],

                        'name'           => $option['name'],
                        'name_ar'        => $option['name_ar'],

                        'price_delta'    => $option['price_delta'] ?? null,
                        'is_increase'    => $option['is_increase'] ?? true,

                        'option_explain' => $option['option_explain'] ?? null,
                        'is_active'      => (bool) $option['is_active'],

                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ];

                    $keepKeys[] = [
                        'option_group_id' => $group['group_id'],
                        'name' => $option['name'],
                    ];
                }
            }
            if (!empty($optionsToSave)) {
                if ($action === 'create') {
                    RestaurantMenuItemOption::insert($optionsToSave);
                } elseif ($action === 'update') {
                    RestaurantMenuItemOption::upsert(
                        $optionsToSave,
                        ['menu_item_id', 'option_group_id', 'name'],
                        ['name_ar', 'price_delta', 'is_increase', 'option_explain', 'is_active', 'updated_at']
                    );
                }
            }

            if ($action === 'update') {
                self::deleteItemOptions($menuItem->id, $keepKeys);
            }
        } catch (\Exception $e) {
            throw new \Exception('MenuItemsService@handleItemOptions: An error occurred while storing the item options', 0, $e);
        }
    }

    /**
     * Delete the menu item options.
     *
     * @param int $menuItemId
     * @param array $keepKeys
     * @return void
     */
    protected static function deleteItemOptions(int $menuItemId, array $keepKeys)
    {
        try {
            if (empty($keepKeys)) {
                RestaurantMenuItemOption::where('menu_item_id', $menuItemId)->delete();
            } else {
                $ids = RestaurantMenuItemOption::where('menu_item_id', $menuItemId)
                    ->where(function ($query) use ($keepKeys) {
                        foreach ($keepKeys as $key) {
                            $query->orWhere(function ($q) use ($key) {
                                $q->where('option_group_id', $key['option_group_id'])
                                    ->where('name', $key['name']);
                            });
                        }
                    })
                    ->pluck('id');

                RestaurantMenuItemOption::where('menu_item_id', $menuItemId)
                    ->whereNotIn('id', $ids)
                    ->delete();
            }
        } catch (\Exception $e) {
            throw new \Exception('MenuItemsService@deleteItemOptions: An error occurred while deleting the item options', 0, $e);
        }
    }
}
