<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Throwable;
use domain\Facades\MembershipPlanFacade;
use domain\Facades\ImageFacade;
use App\Http\Requests\StoreMembershipPlanRequest;

class MembershipPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {

            $plans = MembershipPlanFacade::allWithParamAndPaginate($request->all());
            $allPlans = MembershipPlanFacade::all();
            $totalCount = $allPlans->count();
            $activeCount = MembershipPlanFacade::activeCount();
            $deactiveCount = MembershipPlanFacade::deactiveCount();
            
            return view('pages.admin.membership-plan.index',compact('plans','totalCount','activeCount','deactiveCount'));
        } catch (Throwable $th) {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMembershipPlanRequest $request)
    {
        try {

            if ($request->hasFile('image')) {
                $imagePath = ImageFacade::store($request->file('image'), 'membership-plans');

                $request->merge(['image_path' => $imagePath]);
            }

            MembershipPlanFacade::store($request->all());

            return redirect()->route('admin.membership-plans.index')->with('success', 'Membership Plan Added Successfully');
        } catch (Throwable $th) {
            return redirect()->back()->withInput()->with('error', 'Something went wrong');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
