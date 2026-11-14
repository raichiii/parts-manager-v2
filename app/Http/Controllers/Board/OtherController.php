<?php

namespace App\Http\Controllers\Board;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Models\Board\Other;
use App\Http\Requests\Board\OtherRequest;
use App\Services\Board\ThingService;
use App\Services\Board\OtherService;
use App\Services\Order\UnregisteredModelService;

class OtherController extends Controller
{
    private $otherService;
    private $thingService;
    private $unregisteredModelService;

    public function __construct(OtherService $otherService,
        ThingService $thingService,
        UnregisteredModelService $unregisteredModelService)
    {
        $this->otherService = $otherService;
        $this->thingService = $thingService;
        $this->unregisteredModelService = $unregisteredModelService;
    }

    /**
     * その他の一覧
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $others = Other::with(['thing', 'maker'])->orderBy('id', 'desc')->get();
        return view('board.others.index', compact('others'));
    }

    /**
     * その他の作成画面
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        return view('board.others.create');
    }

    /**
     * その他の作成
     * @param App\Http\Requests\Board\OtherRequest $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(OtherRequest $request)
    {
        try {
            $thing = $this->thingService->create($request);
            $request->merge(['thing_id' => $thing->id]);
            $other = $this->otherService->create($request);
            $this->unregisteredModelService->update($thing, 'ModelNo');
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getMessage());
        }
        return redirect()->route('others.index')->with('success', 'その他が追加されました');
    }

    /**
     * その他の表示
     * @param App\Models\Board\Other $other
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function show(Other $other)
    {
        $other->load(['thing', 'maker']);
        return view('board.others.show', compact('other'));
    }

    /**
     * その他の編集画面
     * @param App\Models\Board\Other $other
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit(Other $other)
    {
        $other->load(['thing', 'maker']);
        return view('board.others.edit', compact('other'));
    }

    /**
     * その他の変更
     * @param App\Models\Board\Other $other
     * @param App\Http\Requests\Board\OtherRequest $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(Other $other, OtherRequest $request)
    {
        try {
            $thing = $this->thingService->update($other->thing, $request);
            $other = $this->otherService->update($other, $request);
            $this->unregisteredModelService->update($thing, 'ModelNo');
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getCode() == 23000 ? "既に登録されています" : $exception->getMessage());
        }
        return redirect()->route('others.index')->with('success', '変更を保存しました');
    }

    /**
     * その他の削除
     * @param App\Models\Board\Other $other
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Other $other)
    {
        try {
            DB::transaction(function () use ($other) {
                $other->load('thing');
                $this->otherService->destroy($other);
                $this->thingService->destroy($other->thing);
            });
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getMessage());
        }
        return redirect()->route('others.index')->with('success', 'その他を削除しました');
    }

    /**
     * その他の検索API
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function search(Request $request)
    {
        $keyword = $request->input('query');
        try {
            $others = Other::whereHas('thing', function ($query) use ($keyword) {
                $query->where('model_no', 'like', '%' . $keyword . '%');
            })->orWhere(function ($query) use ($keyword) {
                $query->orWhere('note', 'like', '%' . $keyword . '%');
            })->limit(15)->get()->load('thing', 'maker');
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 422);
        }
        return response()->json($others);
    }
}
