<?php

namespace App\Http\Controllers\Board;

use App\Http\Controllers\Controller;
use App\Services\Board\DetailedBoardService;
use App\Models\Config;
use App\Models\Board\DetailedBoard;
use App\Models\Board\PrintCircuitBoard;
use App\User;
use App\Http\Requests\Board\DetailedBoardRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;

class DetailedBoardController extends Controller
{

    private $detailedBoardService;

    public function __construct(DetailedBoardService $detailedBoardService)
    {
        $this->detailedBoardService = $detailedBoardService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $detailedBoards = DetailedBoard::with(['mountedBoard.printCircuitBoard.thing'])->get()->sortByDesc('id')->values();
        return view('board.detailedBoards.index', compact('detailedBoards'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        $today = Carbon::today()->toDateString();
        return view('board.detailedBoards.create', compact('users', 'today'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DetailedBoardRequest $request)
    {
        try {
            $this->detailedBoardService->create($request);
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getMessage());
        }
        return redirect()->route('detailedBoards.index')->with('success', '在庫基板の詳細記録が追加されました');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(DetailedBoard $detailedBoard)
    {
        return view('board.detailedBoards.show', compact('detailedBoard'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(DetailedBoard $detailedBoard)
    {
        $users = User::all();
        $detailedBoard->load('mountedBoard.printCircuitBoard.thing');
        $mountedBoards = PrintCircuitBoard::find($detailedBoard->mountedBoard->printCircuitBoard->id)->mountedBoards;
        $versions = [];
        foreach ($mountedBoards as $mountedBoard) {
            array_push($versions, $mountedBoard->version);
        }
        return view('board.detailedBoards.edit', compact('detailedBoard', 'versions', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DetailedBoard $detailedBoard, DetailedBoardRequest $request)
    {
        try {
            $detailedBoard = $this->detailedBoardService->update($detailedBoard, $request);
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getMessage());
        }
        return redirect()->route('detailedBoards.show', [$detailedBoard])->with('success', '変更を保存しました');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DetailedBoard $detailedBoard)
    {
        try {
            $this->detailedBoardService->destroy($detailedBoard);
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getMessage());
        }
        return redirect()->route('detailedBoards.index')->with('success', '在庫基板の記録を削除しました');
    }

    /**
     * 詳細基板検索
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function kensaku(Request $request)
    {
        $user_agent =  $request->header('User-Agent');

        $colNames = [
            'id' => 'ID', 'model_no' => '型番', 'hardware_operation' => 'HW動作', 'adjustment_calibration' => '調整校正',
            'shipment' => '出荷', 'hardware_version' => 'HW Ver.', 'software_version' => 'SW Ver.', 'check_sum' => 'チェックサム',
            'created_at' => '製造日', 'lot_no' => 'Lot No.', 'user' => '初期担当', 'depository' => '棚', 'note' => '備考',
        ];
        $colNames = json_encode($colNames);
        $searchUrl = Config::where('key', 'detailed_board_search_address')->first()->value;

        if (strpos($user_agent, 'iPhone') || strpos($user_agent, 'iPod') || strpos($user_agent, 'Android')) {
            return view('board.detailedBoards.kensaku_sp', compact(['colNames', 'searchUrl']));
        }

        return view('board.detailedBoards.kensaku', compact(['colNames', 'searchUrl']));
    }

    /**
     * CSV変更
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function changeCsv(Request $request)
    {
        $files = [
            $request->file('file-detailed-board'),
        ];
        if (!count(array_filter($files))) {
            return Redirect::back()->withErrors('ファイルが選択されていません');
        }
        $file = $files[0];
        if ($file) $this->detailedBoardService->changeFileDetailedBoard($file);

        return redirect()->route('detailedBoards.kensaku')->with('success', 'CSVファイルを更新しました');
    }

    /**
     * 全詳細基板取得API
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function getAll(Request $request)
    {
        $detailedBoards = $this->detailedBoardService->getAll();

        return response()->json($detailedBoards);
    }
}
