<?php

namespace App\Services;
use App\Models\Group;
use App\Models\Itinerary;
use App\Models\billUser;

class CostCalculator
{

    public function total_getPay(Itinerary $itinerary, $member)
{

    $all_getPayUser = $itinerary->bills()->where('user_pay_id', $member->id)->get();

    $total = $all_getPayUser->sum('eachPay');

    return $total;
}

public function total_Pay(Itinerary $itinerary, billUser $billUser, $member)
{
    $all_bills_id = $itinerary->bills->pluck('id')->toArray();
    $all_billUsers = $billUser->whereIn('bill_id', $all_bills_id)->get();
    $total = $all_billUsers->where('user_paid_id', $member->id)->sum('eachPay');

    return $total;
}

public function detailPayment(Itinerary $itinerary, billUser $billUser) {

    $groupMembers = $itinerary->group->members;
    $giveUser = [];
    $getUser = [];
    $noPayUser = [];
    foreach ($groupMembers as $member) {
        $total = $this->total_getPay($itinerary, $member->user) - $this->total_Pay($itinerary, $billUser, $member->user);
        if ($total > 0) {
            $getUser[] = [$member->user, $total];
        }elseif ($total < 0) {
            $giveUser[] = [$member->user, - $total];
        }else {
            $noPayUser[] = [$member->user, 0];
        }
    }

    $countGive = count($giveUser);
    $countGet = count($getUser);
    $detail = [];

    for ($i = 0; $i < $countGive; $i++) {
        $amount = $giveUser[$i][1];
        for ($x = 0; $x < $countGet; $x++) {
            if ($getUser[$x][1] == 0) {
                continue;
            }
            if ($amount > $getUser[$x][1]) {
                $detail[] = [$giveUser[$i][0], $getUser[$x][0], $getUser[$x][1]];
                $amount -= $getUser[$x][1];
                $getUser[$x][1] = 0;
                continue;
            } else {
                $detail[] = [$giveUser[$i][0], $getUser[$x][0], $amount];
                $getUser[$x][1] -= $amount;
                break;
            }
        }
    }

    return $detail;

}


}
