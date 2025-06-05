<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Group;
use App\Models\Itinerary;
use App\Models\BillUser;
use Illuminate\Http\Request;
use App\Services\CostCalculator;

class BillController extends Controller
{
    private $bill;
    private $group;
    private $itinerary;
    private $billUser;

    public function __construct(Bill $bill, Group $group, Itinerary $itinerary, BillUser $billUser)
    {
        $this->bill = $bill;
        $this->billUser = $billUser;
        $this->group = $group;
        $this->itinerary = $itinerary;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(CostCalculator $costCalculator)
    {
        // groupIDの付与ー＞のちに行う
        $group = $this->group->findOrFail(1);
        $groupMembers = [];
        foreach ($group->members as $groupMember) {
            $groupMembers[] = $groupMember->user;
        }

        // ItineraryのIDの付与ー＞のちに行う
        $itinerary = $this->itinerary->findOrFail(1);
        $all_bills = $itinerary->bills()->latest()->paginate(6)->onEachSide(2);

        //calculation
        $total_getPay = [];
        $total_Pay = [];
        foreach ($groupMembers as $member) {
            $total_getPay[$member->id] = $costCalculator->total_getPay($this->group, $this->itinerary, $member);
            $total_Pay[$member->id] = $costCalculator->total_Pay($this->group, $this->itinerary, $this->billUser, $member);
        }

        return view('goDutch.show')
            ->with('all_bills', $all_bills)
            ->with('groupMembers', $groupMembers)
            ->with('total_getPay', $total_getPay)
            ->with('total_Pay', $total_Pay);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, CostCalculator $costCalculator)
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

        $number_payment = count($request->user_paid_id);
        $each_cost = $request->cost / $number_payment;
        if (in_array($request->user_pay_id, $request->user_paid_id)) {
            $getPay = $request->cost / $number_payment * ($number_payment - 1);
        } else {
            $getPay = $request->cost;
        };
        $this->bill->eachPay = $getPay;
        $this->bill->save();

        $billUser = [];
        foreach ($request->user_paid_id as $user_id) {
            if($user_id == $request->user_pay_id){
                $billUser[] = ['user_paid_id' => $user_id, 'eachPay' => 0];
            }else{
                $billUser[] = ['user_paid_id' => $user_id, 'eachPay' => $each_cost];
            }
        }
        $this->bill->billUser()->createMany($billUser);


        $all_bills = $this->bill->latest()->paginate(10)->onEachSide(2);
        // groupIDの付与ー＞のちに行う
        $group = $this->group->findOrFail(1);
        $groupMembers = [];
        foreach ($group->members as $groupMember) {
            $groupMembers[] = $groupMember->user;
        }

        //calculation
        $total_getPay = [];
        $total_Pay = [];
        foreach ($groupMembers as $member) {
            $total_getPay[$member->id] = $costCalculator->total_getPay($this->group, $this->itinerary, $member);
            $total_Pay[$member->id] = $costCalculator->total_Pay($this->group, $this->itinerary, $this->billUser, $member);
        }

        return view('goDutch.show')
        ->with('all_bills', $all_bills)
        ->with('groupMembers', $groupMembers)
        ->with('total_getPay', $total_getPay)
        ->with('total_Pay', $total_Pay);
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
        foreach ($group->members as $groupMember) {
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
