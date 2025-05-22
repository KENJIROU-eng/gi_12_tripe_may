<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillUser;
use App\Models\Group;
use Illuminate\Http\Request;

class BillController extends Controller
{
    private $bill;
    private $group;

    public function __construct(Bill $bill, Group $group)
    {
        $this->bill = $bill;
        $this->group = $group;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // groupIDの付与ー＞のちに行う
        $group = $this->group->findOrFail(1);
        $groupMembers = [];
        foreach ($group->groupMembers as $groupMember) {
            $groupMembers[] = $groupMember->user;
        }
        $all_bills = $this->bill->latest()->paginate(10)->onEachSide(2);
        return view('goDutch.show')
            ->with('all_bills', $all_bills)
            ->with('groupMembers', $groupMembers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_paid_id' => 'required',
            'user_pay_id' => 'required',
            'bill_name' => 'required|min:1|max:1000',
            'cost' => 'required|numeric|min:1'
        ]);

        $this->bill->user_pay_id = $request->user_pay_id;
        $this->bill->name = $request->bill_name;
        $this->bill->cost = $request->cost;

        // itinerary_idを保存する予定
        $this->bill->itinerary_id = 1;

        $this->bill->save();

        $user_paid_id = [];
        foreach ($request->user_paid_id as $user_id) {
            $user_paid_id[] = ['user_paid_id' => $user_id];
        }
        $this->bill->billUser()->createMany($user_paid_id);


        $all_bills = $this->bill->latest()->paginate(10)->onEachSide(2);
        // groupIDの付与ー＞のちに行う
        $group = $this->group->findOrFail(1);
        $groupMembers = [];
        foreach ($group->groupMembers as $groupMember) {
            $groupMembers[] = $groupMember->user;
        }

        return view('goDutch.show')
        ->with('all_bills', $all_bills)
        ->with('groupMembers', $groupMembers);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $bill_id)
    {
        $request->validate([
            'user_paid_id_edit' => 'required',
            'user_pay_id_edit' => 'required',
            'bill_name_edit' => 'required|min:1|max:1000',
            'cost_edit' => 'required|numeric|min:1'
        ]);

        $bill = $this->bill->findOrFail($bill_id);

        $bill->user_pay_id = $request->user_pay_id_edit;
        $bill->name = $request->bill_name_edit;
        $bill->cost = $request->cost_edit;

        // itinerary_idを保存する予定
        $bill->itinerary_id = 1;

        $bill->save();

        $user_paid_id = [];
        foreach ($request->user_paid_id_edit as $user_id) {
            $user_paid_id[] = ['user_paid_id' => $user_id];
        }
        $bill->billUser()->delete();
        $bill->billUser()->createMany($user_paid_id);

        $all_bills = $this->bill->latest()->paginate(10)->onEachSide(2);
        // groupIDの付与ー＞のちに行う
        $group = $this->group->findOrFail(1);
        $groupMembers = [];
        foreach ($group->groupMembers as $groupMember) {
            $groupMembers[] = $groupMember->user;
        }

        return redirect()->route('goDutch.index')
        ->with('all_bills', $all_bills)
        ->with('groupMembers', $groupMembers);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bill $bill, $bill_id)
    {
        $bill = $this->bill->findOrFail($bill_id);
        $bill->delete();
        return redirect()->route('goDutch.index');
    }
}
