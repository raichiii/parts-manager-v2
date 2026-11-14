<?php

namespace App\Http\Controllers\Board;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

use App\Models\Board\Part;
use App\Http\Requests\Board\PartRequest;
use App\Models\Config;
use App\Services\Board\ThingService;
use App\Services\Board\PartService;
use App\Services\Order\UnregisteredModelService;

class PartController extends Controller
{
    private $partService;
    private $thingService;
    private $unregisteredModelService;

    public function __construct(PartService $partService,
        ThingService $thingService,
        UnregisteredModelService $unregisteredModelService)
    {
        $this->partService = $partService;
        $this->thingService = $thingService;
        $this->unregisteredModelService = $unregisteredModelService;
    }

    /**
     * 部品の一覧
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $parts = Part::with(['thing', 'maker'])->orderBy('id', 'desc')->get();
        return view('board.parts.index', compact('parts'));
    }

    /**
     * 部品の作成画面
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        return view('board.parts.create');
    }

    /**
     * 部品の作成
     * @param \App\Http\Requests\Board\PartRequest $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(PartRequest $request)
    {
        try {
            $thing = $this->thingService->create($request);
            $request->merge(['thing_id' => $thing->id]);
            $part = $this->partService->create($request);
            $this->unregisteredModelService->update($thing, 'ModelNo');
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getMessage());
        }
        return redirect()->route('parts.index')->with('success', '部品が追加されました');
    }

    /**
     * 部品の表示
     * @param App\Models\Board\Part $part
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function show(Part $part)
    {
        $part->load(['thing', 'maker']);
        return view('board.parts.show', compact('part'));
    }

    /**
     * 部品の編集画面
     * @param App\Models\Board\Part $part
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit(Part $part)
    {
        $part->load(['thing', 'maker']);
        return view('board.parts.edit', compact('part'));
    }

    /**
     * 部品の変更
     * @param \App\Models\Board\Part $part
     * @param \App\Http\Requests\Board\PartRequest $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(Part $part, PartRequest $request)
    {
        try {
            $thing = $this->thingService->update($part->thing, $request);
            $part = $this->partService->update($part, $request);
            $this->unregisteredModelService->update($thing, 'ModelNo');
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getCode() == 23000 ? "既に登録されています" : $exception->getMessage());
        }
        return redirect()->route('parts.index')->with('success', '変更を保存しました');
    }

    /**
     * 部品の削除
     * @param \App\Models\Board\Part $part
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Part $part)
    {
        try {
            if ($part->mountedBoards()->exists()) {
                return Redirect::back()->withErrors('基板で使用されているため削除できません');
            }
            DB::transaction(function () use ($part) {
                $part->load('thing');
                $this->partService->destroy($part);
                $this->thingService->destroy($part->thing);
            });
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getMessage());
        }
        return redirect()->route('parts.index')->with('success', '部品を削除しました');
    }

    /**
     * 部品実装数検索
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function kensaku(Request $request)
    {
        $user_agent =  $request->header('User-Agent');
        $searchUrl = Config::where('key', 'part_search_address')->first()->value;

        return view('board.parts.kensaku', compact(['searchUrl']));
    }

    /**
     * CSV変更
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function changeCsv(Request $request)
    {
        try {
            $files = [
                $request->file('file-buhin-list'),
                $request->file('file-board-job'),
            ];
            if (!count(array_filter($files))) {
                return Redirect::back()->withErrors('ファイルが選択されていません');
            }
            $file = $files[0];
            if ($file) $this->partService->changeFileBuhinList($file);
            $file = $files[1];
            if ($file) $this->partService->changeFileBoardJob($file);

            return redirect()->route('parts.kensaku')->with('success', 'CSVファイルを更新しました');
        } catch (\Exception $exception) {
            return Redirect::back()->withErrors($exception->getMessage());
        }
    }

    /**
     * 全部品取得API
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function getAll(Request $request)
    {
        try {
            list($boardNames, $parts, $listDatas, $Ps, $boardJob) = $this->partService->getAll();

            return response()->json([
                'boardNames' => $boardNames,
                'parts' => $parts,
                'listDatas' => $listDatas,
                'Ps' => $Ps,
                'boardJob' => $boardJob,
            ]);
        } catch (\Exception $exception) {
            return response() -> json([
                "message" => $exception->getMessage()
            ], 404);
        }
    }

    /**
     * 部品の検索API
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function search(Request $request)
    {
        $keyword = $request->input('query');
        try {
            $parts = Part::whereHas('thing', function ($query) use ($keyword) {
                $query->where('model_no', 'like', '%' . $keyword . '%');
            })->orWhere(function ($query) use ($keyword) {
                $query->orWhere('note', 'like', '%' . $keyword . '%');
            })->limit(15)->get()->load('thing', 'maker');
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 422);
        }
        return response()->json($parts);
    }

    /**
     * 部品マスタCSVファイルダウンロード
     * @return
     */
    public function downloadCsv()
    {
        $filePath = DB_CSV_DIR.'buhin_list.csv';
        $fileName = 'buhin_list.csv';
        $headers = [['Content-Type' => 'text/csv']];
        if(file_exists($filePath)){
            return response()->download($filePath, $fileName, $headers);
        }else{
            return redirect()->route('parts.kensaku')->withErrors('CSVファイルが見つかりませんでした');
        }
    }
}
