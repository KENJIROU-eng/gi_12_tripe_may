<?php

namespace App\Services;
use App\Models\Group;
use App\Models\Itinerary;
use App\Models\billUser;

class CostCalculator
{

    public function total_getPay(Itinerary $itinerary, $member)
{

    // ItineraryのIDの付与ー＞のちに行う
    $all_getPayUser = $itinerary->bills()->where('user_pay_id', $member->id)->get();

    $total = $all_getPayUser->sum('eachPay');

    return $total;
}

public function total_Pay(Itinerary $itinerary, billUser $billUser, $member)
{
    // $groupMembers = [];
    // foreach ($group->members as $groupMember) {
    //     $groupMembers[] = $groupMember->user;
    // }

    // ItineraryのIDの付与ー＞のちに行う
    $all_bills_id = $itinerary->bills->pluck('id')->toArray();
    $all_billUsers = $billUser->whereIn('bill_id', $all_bills_id)->get();
    $total = $all_billUsers->where('user_paid_id', $member->id)->sum('eachPay');

    return $total;
}


}
