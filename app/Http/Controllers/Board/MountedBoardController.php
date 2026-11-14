<?php

namespace App\Http\Controllers\Board;

use App\Http\Controllers\Controller;
use App\Models\Board\PrintCircuitBoard;
use App\Models\Board\MountedBoard;
use App\Services\Board\MountedBoardService;
use App\Http\Requests\Board\MountedBoardRequest;
use Illuminate\Support\Facades\Redirect;

class MountedBoardController extends Controller
{
    private $mountedBoardService;

    public function __construct(MountedBoardService $mountedBoardService)
    {
        $this->mountedBoardService = $mountedBoardService;
    }

    /**
     * 基板作成画面
     * @param App\Models\Board\PrintCircuitBoard $printCircuitBoard
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create(PrintCircuitBoard $printCircuitBoard)
    {
        $latestMountedBoards = MountedBoard::getLatestMountedBoards($printCircuitBoard->id);
        return view('board.mountedBoards.create', compact('latestMountedBoards'));
    }

    /**
     * 基板の作成
     * @param App\Models\Board\PrintCircuitBoard $printCircuitBoard
     * @param App\Http\Requests\Board\MountedBoardRequest $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(PrintCircuitBoard $printCircuitBoard, MountedBoardRequest $request)
    {
        $csvFile = $request->file('csv_file');
        try {
            if (is_null($csvFile) && !$request->exists('parts')) {
                return Redirect::back()
                    ->withErrors("部品をcsvファイルで添付または、フォームで入力してください");
            }
            if (!is_null($csvFile)) {
                $request = $this->mountedBoardService->mergeCsvToRequest($request);
                if ($request->exists('unregistered_parts')) {
                    $unregisteredParts = $request->unregistered_parts;
                    array_unshift($unregisteredParts, '以下の部品が登録されていません');
                    return Redirect::back()
                        ->withErrors($unregisteredParts);
                }
            }
            $mountedBoard = $this->mountedBoardService->create($request);
            $printCircuitBoard = $mountedBoard->printCircuitBoard;
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getCode() == 23000 ? "データが重複しています" : $exception->getMessage());
        }
        return redirect()->route('printCircuitBoards.show', [$printCircuitBoard])->with('success', '基板が追加されました');
    }

    /**
     * 基板の表示
     * @param App\Models\Board\PrintCircuitBoard $printCircuitBoard
     * @param App\Models\Board\MountedBoard $mountedBoard
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function show(PrintCircuitBoard $printCircuitBoard, MountedBoard $mountedBoard)
    {
        $parts = $mountedBoard->parts;
        return view('board.mountedBoards.show', compact('mountedBoard', 'parts'));
    }

    /**
     * 基板の編集画面
     * @param App\Models\Board\PrintCircuitBoard $printCircuitBoard
     * @param App\Models\Board\MountedBoard $mountedBoard
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit(PrintCircuitBoard $printCircuitBoard, MountedBoard $mountedBoard)
    {
        $parts = $mountedBoard->parts()->with('thing', 'maker')->get();
        return view('board.mountedBoards.edit', compact('mountedBoard', 'parts'));
    }

    /**
     * 基板の情報変更
     * @param App\Models\Board\PrintCircuitBoard $printCircuitBoard
     * @param App\Models\Board\MountedBoard $mountedBoard
     * @param App\Http\Requests\Board\MountedBoardRequest $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(PrintCircuitBoard $printCircuitBoard, MountedBoard $mountedBoard, MountedBoardRequest $request)
    {
        $csvFile = $request->file('csv_file');
        try {
            if (is_null($csvFile) && !$request->exists('parts')) {
                return Redirect::back()
                    ->withErrors("部品をcsvファイルで添付または、フォームで入力してください");
            }
            if (!is_null($csvFile)) {
                $request = $this->mountedBoardService->mergeCsvToRequest($request);
                if ($request->exists('unregistered_parts')) {
                    $unregisteredParts = $request->unregistered_parts;
                    array_unshift($unregisteredParts, '以下の部品が登録されていません');
                    return Redirect::back()
                        ->withErrors($unregisteredParts);
                }
            }
            $mountedBoard = $this->mountedBoardService->update($mountedBoard, $request);
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getCode() == 23000 ? "データが重複しています" : $exception->getMessage());
        }
        return redirect()->route('mountedBoards.show', [$printCircuitBoard, $mountedBoard])->with('success', '基板を変更しました');
    }

    /**
     * 基板の削除
     * @param App\Models\Board\PrintCircuitBoard $printCircuitBoard
     * @param App\Models\Board\MountedBoard $mountedBoard
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function destroy(PrintCircuitBoard $printCircuitBoard, MountedBoard $mountedBoard)
    {
        try {
            $this->mountedBoardService->destroy($mountedBoard);
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getMessage());
        }
        return redirect()->route('printCircuitBoards.show', [$printCircuitBoard])->with('success', '基板を削除しました');
    }
}
