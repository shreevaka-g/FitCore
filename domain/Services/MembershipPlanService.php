<?php

namespace domain\Services;

use App\Models\MembershipPlan;

class MembershipPlanService
{
    protected $membershipPlan;

    public function __construct()
    {
        $this->membershipPlan = new MembershipPlan();
    }

    /**
     * all
     *
     * @return void
     */
    public function all()
    {
        return $this->membershipPlan->all();
    }

    public function allWithParamAndPaginate($data, $limit = 10)
    {
        return $this->membershipPlan
            ->when(!empty($data['sr']), function ($query) use ($data) {
                $query->where('name', 'LIKE', '%' . $data['sr'] . '%');
            })
            ->when(isset($data['status']) && $data['status'] !== '', function ($query) use ($data) {
                $query->where('is_active', $data['status']);
            })
            ->when(!empty($data['duration']), function ($query) use ($data) {

                if ($data['duration'] === 'day') {
                    $query->where('duration_unit', 'days');
                }

                if ($data['duration'] === 'month') {
                    $query->where('duration_unit', 'months')
                        ->where('duration_value', 1);
                }

                if ($data['duration'] === 'quarter') {
                    $query->where('duration_unit', 'months')
                        ->where('duration_value', 3);
                }

                if ($data['duration'] === 'year') {
                    $query->where('duration_unit', 'years')
                        ->where('duration_value', 1);
                }
            })
            ->orderBy('sort_order', 'asc')
            ->paginate($limit)
            ->withQueryString();
    }

    public function activeCount()
    {
        return $this->membershipPlan->where('is_active', 1)->count();
    }

    public function deactiveCount()
    {
        return $this->membershipPlan->where('is_active', 0)->count();
    }

    public function first()
    {
        return $this->membershipPlan->first();
    }

    /**
     * get
     *
     * @param  mixed $product_id
     * @return void
     */
    public function get($id)
    {
        return $this->membershipPlan->find($id);
    }

    public function store($data)
    {

        $sortOrder = $data['sort_order'] ?? 0;

        $this->membershipPlan->where('sort_order', '>=', $sortOrder)->increment('sort_order');

        $membershipPlan = $this->membershipPlan->create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'duration_value' => $data['duration_value'],
            'duration_unit' => $data['duration_unit'],
            'color_code' => $data['color_code'],
            'image_path' => $data['image_path'] ?? null,
            'is_active' => $data['is_active'],
            'sort_order' => $sortOrder,
            ]);
    }

    public function update($id, $data)
    {
        $this->get($id)->update($data);
    }

    public function destroy($id)
    {
        $this->get($id)->delete();
    }

    public function updateMembershipPlanStatus($data)
    {
        $row = $this->get($data['id']);

        if ($row) {
            $row->is_active = $data['status'];
            $row->save();
        }
    }
}
