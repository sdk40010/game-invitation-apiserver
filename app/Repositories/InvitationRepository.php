<?php
namespace App\Repositories;

use App\Models\Invitation;
use App\Http\Resources\InvitationCollection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

use Illuminate\Support\Facades\Log;

class InvitationRepository
{
    /**
     * クエリビルダー(Eloquent)
     */
    protected $invitation;

    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * 検索条件に基づいて募集を検索する
     */
    public function search(array $searchParams)
    {
        // TODO それぞれのwhenメソッドの中でOR検索にも対応する
        $tags = $searchParams['tags'];
        $title = $searchParams['title'];
        $minStartTime = $searchParams['minStartTime'];
        $maxStartTime = $searchParams['maxStartTime'];
        $minCapacity = $searchParams['minCapacity'];
        $maxCapacity = $searchParams['maxCapacity'];
        $page = $searchParams['page'];
        $perPage = 20;

        $invitations = $this->invitation
            // タグ
            ->when(isset($tags), function (Builder $query) use ($tags) {
                $names = $this->splitIntoWards($tags);
                $query
                    ->join('tagmaps', 'invitations.id', '=', 'tagmaps.invitation_id')
                    ->join('tags', 'tags.id', '=', 'tagmaps.tag_id')
                    ->whereIn('tags.name', $names)
                    ->groupBy('invitations.id')
                    ->havingRaw('count(*) = ?', [count($names)]);
            })
            // タイトル
            ->when(isset($title), function (Builder $query) use ($title) {
                foreach ($this->splitIntoWards($title) as $keyword) {
                    $query->where('invitations.title', 'like', '%'.$keyword.'%');
                }
            })
            // 開始時刻の範囲
            ->when(isset($minStartTime), function (Builder $query) use ($minStartTime) {
                $query->where('invitations.start_time', '>=', $minStartTime);
            })
            ->when(isset($maxStartTime), function (Builder $query) use ($maxStartTime) {
                $query->where('invitations.start_time', '<=', $maxStartTime);
            })
            // 定員の範囲
            ->when(isset($minCapacity), function (Builder $query) use ($minCapacity) {
                $query->where('invitations.capacity', '>=', $minCapacity);
            })
            ->when(isset($maxCapacity), function (Builder $query) use ($maxCapacity) {
                $query->where('invitations.capacity', '<=', $maxCapacity);
            })
            ->orderBy('start_time', 'asc')
            ->get();

        return new InvitationCollection(
            new LengthAwarePaginator(
                $invitations->forPage($page, $perPage),
                $invitations->count(),
                $perPage,
                $page
            )
        );
    }

    /**
     * 空白文字で文字列を分割した上で、重複を排除する
     */
    private function splitIntoWards($string, $limit = -1)
    {
        return array_values(array_unique(
            preg_split('/[\p{Z}\p{Cc}]++/u', $string, $limit, PREG_SPLIT_NO_EMPTY)
        ));
    }
}
