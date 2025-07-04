<?php

namespace App\Http\Controllers;

use App\Models\Belonging;
use App\Models\Itinerary;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BelongingController extends Controller
{
    private $belonging;
    private $itinerary;

    public function __construct(Belonging $belonging, Itinerary $itinerary)
    {
        $this->belonging = $belonging;
        $this->itinerary = $itinerary;
    }

    public function index($itineraryId)
    {
        $itinerary = Itinerary::with('group.users')->findOrFail($itineraryId);

        $all_belongings = $itinerary->belongings;
        $members = $itinerary->group->users;
        $totalCount = $all_belongings->count();
        $checkedCount = $all_belongings->where('checked', true)->count();
        $progressPercent = $totalCount > 0 ? floor(($checkedCount / $totalCount) * 100) : 0;

        return view('belongings.list', [
            'itineraryId' => $itineraryId,
            'all_belongings' => $all_belongings,
            'members' => $members,
            'totalCount' => $totalCount,
            'checkedCount' => $checkedCount,
            'progressPercent' => $progressPercent,
        ]);
    }

    public function store(Request $request, $itineraryId)
    {
        $validated = $request->validate([
            'item' => 'required|string|max:255',
            'description' => 'nullable|string',
            'members' => 'required|array',
            'members.*' => 'exists:users,id',
        ]);

        // Belonging を作成
        $belonging = new Belonging();
        $belonging->name = $validated['item'];
        $belonging->description = $validated['description'] ?? null;
        $belonging->itinerary_id = $itineraryId;
        $belonging->save();

        // 中間テーブルにメンバーを割り当て（初期状態は未チェック）
        $syncData = [];
        foreach ($validated['members'] as $userId) {
            $syncData[$userId] = ['is_checked' => false];
        }
        $belonging->users()->sync($syncData);

        return redirect()->route('belonging.index', $itineraryId)->with('success', 'Item added.');
    }

    public function updateCheck(Request $request, $belongingId, $userId)
    {
        $request->validate([
            'is_checked' => 'required|boolean',
        ]);

        // 中間テーブルを更新
        DB::table('belonging_user')
            ->where('belonging_id', $belongingId)
            ->where('user_id', $userId)
            ->update(['is_checked' => $request->is_checked]);

        // すべての assigned user の is_checked が true なら belongings.checked も true に
        $totalAssigned = DB::table('belonging_user')->where('belonging_id', $belongingId)->count();
        $totalChecked = DB::table('belonging_user')->where('belonging_id', $belongingId)->where('is_checked', true)->count();

        $isAllChecked = $totalAssigned > 0 && $totalAssigned === $totalChecked;

        DB::table('belongings')
            ->where('id', $belongingId)
            ->update(['checked' => $isAllChecked]);

        return response()->json(['message' => '更新成功', 'all_checked' => $isAllChecked]);
    }

    public function update(Request $request, $id)
    {
        // Belonging を ID から取得（自動バインディングが効かない場合の保険）
        $belonging = Belonging::findOrFail($id);

        // バリデーション
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'members' => 'required|array',
            'members.*' => 'exists:users,id',
        ]);

        // 所属アイテム情報の更新
        $belonging->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        // 現在の中間テーブルの is_checked 状態を取得（users.id => is_checked）
        $current = $belonging->users()
            ->pluck('belonging_user.is_checked', 'users.id')
            ->toArray();

        // 新しく指定されたメンバーに対し、以前の is_checked 状態を保持
        $syncData = [];
        foreach ($validated['members'] as $userId) {
            $syncData[$userId] = ['is_checked' => $current[$userId] ?? false];
        }

        // 中間テーブル更新（detach + attach）で状態を反映
        $belonging->users()->sync($syncData);

        return response()->json(['message' => 'Updated successfully']);
    }

    public function destroy($belonging_id)
    {
        $belonging = $this->belonging->findOrFail($belonging_id);
        $belonging->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }

    public function checkDuplicate(Request $request)
    {
        logger()->info('重複チェック リクエスト内容:', $request->all());

        $validated = $request->validate([
            'name' => 'required|string',
            'itinerary_id' => 'required|integer'
        ]);

        $exists = Belonging::where('name', $validated['name'])
                    ->where('itinerary_id', $validated['itinerary_id'])
                    ->first();

        return response()->json(['exists' => $exists !== null, 'id' => $exists?->id]);
    }

    public function addMembers(Request $request, Belonging $belonging)
    {
        $validated = $request->validate([
            'members' => 'array',
            'members.*' => 'integer|exists:users,id',
        ]);

        $newMemberIds = $validated['members'] ?? [];

        // 既存の user_id => is_checked のペアを取得
        $existing = $belonging->users()->pluck('belonging_user.is_checked', 'user_id')->toArray();

        // sync用データ生成（既存は保持、新規は0）
        $syncData = [];

        foreach ($newMemberIds as $userId) {
            $syncData[$userId] = [
                'is_checked' => $existing[$userId] ?? 0
            ];
        }

        $belonging->users()->syncWithoutDetaching($syncData);

        return response()->json(['message' => 'Members added']);
    }


}
