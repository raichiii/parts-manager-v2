<?php

namespace App\Http\Controllers\Board;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Models\Board\PrintCircuitBoard;
use App\Models\Config;
use App\Http\Requests\Board\PrintCircuitBoardRequest;
use App\Services\Board\ThingService;
use App\Services\Board\PrintCircuitBoardService;
use App\Services\Order\UnregisteredModelService;

class PrintCircuitBoardController extends Controller
{
    private $printCircuitBoardService;

    public function __construct(PrintCircuitBoardService $printCircuitBoardService,
        ThingService $thingService,
        UnregisteredModelService $unregisteredModelService)
    {
        $this->printCircuitBoardService = $printCircuitBoardService;
        $this->thingService = $thingService;
        $this->unregisteredModelService = $unregisteredModelService;
    }

    /**
     * 生基板一覧
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $printCircuitBoards = PrintCircuitBoard::with(['thing', 'maker'])->orderBy('id', 'desc')->get();
        return view('board.printCircuitBoards.index', compact('printCircuitBoards'));
    }

    /**
     * 生基板作成画面
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        return view('board.printCircuitBoards.create');
    }

    /**
     * 生基板を作成
     * @param \App\Http\Requests\Board\PrintCircuitBoardRequest $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(PrintCircuitBoardRequest $request)
    {
        try {
            $thing = $this->thingService->create($request);
            $request->merge(['thing_id' => $thing->id]);
            $printCircuitBoard = $this->printCircuitBoardService->create($request);
            $this->unregisteredModelService->update($thing, 'ModelNo');
        } catch (\Exception $exception) {
            if ($exception->getCode() == 23000) {
                return redirect()->route('printCircuitBoards.index')->withErrors($exception->getMessage());
            }
            return Redirect::back()
                ->withErrors($exception->getMessage());
        }
        return redirect()->route('printCircuitBoards.index')->with('success', '生基板を作成しました');
    }

    /**
     * 生基板の表示
     * @param App\Models\Board\PrintCircuitBoard $printCircuitBoard
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function show(PrintCircuitBoard $printCircuitBoard)
    {
        $mountedBoards = $printCircuitBoard->mountedBoards()->orderBy('version_name')->get();
        return view('board.printCircuitBoards.show', compact('printCircuitBoard', 'mountedBoards'));
    }

    /**
     * 生基板の編集画面
     * @param App\Models\Board\PrintCircuitBoard $printCircuitBoard
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit(PrintCircuitBoard $printCircuitBoard)
    {
        $printCircuitBoard->load(['thing', 'maker']);
        return view('board.printCircuitBoards.edit', compact('printCircuitBoard'));
    }

    /**
     * 生基板の情報変更
     * @param \App\Models\Board\PrintCircuitBoard $printCircuitBoard
     * @param \App\Http\Requests\Board\PrintCircuitBoardRequest $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(PrintCircuitBoard $printCircuitBoard, PrintCircuitBoardRequest $request)
    {
        try {
            $thing = $this->thingService->update($printCircuitBoard->thing, $request);
            $printCircuitBoard = $this->printCircuitBoardService->update($printCircuitBoard, $request);
            $this->unregisteredModelService->update($thing, 'ModelNo');
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getCode() == 23000 ? "既に登録されています" : $exception->getMessage());
        }
        return redirect()->route('printCircuitBoards.index')->with('success', '変更を保存しました');
    }

    /**
     * 生基板の削除
     * @param \App\Models\Board\PrintCircuitBoard $printCircuitBoard
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function destroy(PrintCircuitBoard $printCircuitBoard)
    {
        try {
            if ($printCircuitBoard->mountedBoards()->exists()) {
                return Redirect::back()
                    ->withErrors(['基板が存在するため削除できません']);
            }
            DB::transaction(function () use ($printCircuitBoard) {
                $printCircuitBoard->load('thing');
                $this->printCircuitBoardService->destroy($printCircuitBoard);
                $this->thingService->destroy($printCircuitBoard->thing);
            });
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getMessage());
        }
        return redirect()->route('printCircuitBoards.index')->with('success', '生基板を削除しました');
    }

    /**
     * 生基板の検索API
     * @param Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function search(Request $request)
    {
        $keyword = $request->input('query');
        try {
            $printCircuitBoards = PrintCircuitBoard::whereHas('thing', function ($query) use ($keyword) {
                $query->where('model_no', 'like', '%' . $keyword . '%');
            })->orWhere(function ($query) use ($keyword) {
                $query->orWhere('description', 'like', '%' . $keyword . '%');
            })->limit(15)->get()->load('thing', 'mountedBoards');
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 422);
        }
        return response()->json($printCircuitBoards);
    }

    /**
     * 部品実装数検索
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function landisk_job_dir(Request $request)
    {
        $job_path = Config::where('key', 'job_path')->first()->value;
        $job_r_path = Config::where('key', 'job_r_path')->first()->value;
        $job_dir_path = '/var/www/webappger/public/landisk/'.$job_path.'job20*';
        $job_dirs = glob($job_dir_path);
        $job_dir_all = glob('/var/www/webapp/public/landisk/'.$job_r_path.'*');
        foreach($job_dirs as $job_dir) {
            $job_dir_all = array_merge($job_dir_all, glob($job_dir.'/*'));
        }
        foreach($job_dir_all as &$job_dir) {
            $job_dir = str_replace('/var/www/webapp/public/landisk/', '', $job_dir);//file://192.168.0.124/disk
        }

        return response()->json($job_dir_all);
    }

    /**
     * 基盤マスタCSVファイルダウンロード
     * @return
     */
    public function downloadCsv()
    {
        $filePath = DB_CSV_DIR.'board_job.csv';
        $fileName = 'board_job.csv';
        $headers = [['Content-Type' => 'text/csv']];
        // dd($filePath, $fileName, $headers);
        if(file_exists($filePath)){
            return response()->download($filePath, $fileName, $headers);
        }else{
            return redirect()->route('parts.kensaku')->withErrors('CSVファイルが見つかりませんでした');
        }
    }
}
