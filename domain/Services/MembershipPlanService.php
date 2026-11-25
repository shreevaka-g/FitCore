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
        if($data && array_key_exists('sr', $data)){
            return $this->membershipPlan->where('name', 'LIKE', '%'.$data['sr'].'%')->orderBy('name')->paginate($limit);
        } else {
            return $this->membershipPlan->orderBy('name')->paginate($limit);
        }
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
        $membershipPlan = $this->membershipPlan->create([
            'name' => $data['name'],
            'description' => $data['description'],
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
