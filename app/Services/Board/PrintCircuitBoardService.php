<?php

namespace App\Services\Board;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Board\PrintCircuitBoard;
use App\Http\Requests\Board\PrintCircuitBoardRequest;

class PrintCircuitBoardService
{
    /**
     * 生基板の作成
     * @param App\Http\Requests\Board\PrintCircuitBoardRequest $request
     * @return App\Models\Board\PrintCircuitBoard
     * @throws \Exception
     */
    public function create(PrintCircuitBoardRequest $request)
    {
        try {
            $printCircuitBoard = DB::transaction(function () use ($request) {
                $printCircuitBoard = new PrintCircuitBoard;
                $printCircuitBoard->thing_id = $request->thing_id;
                $printCircuitBoard->valid_years = $request->valid_years;
                $printCircuitBoard->description = $request->description;
                $printCircuitBoard->ordered_at = $request->ordered_at;
                $printCircuitBoard->save();

                return $printCircuitBoard;
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }

        return $printCircuitBoard;
    }

    /**
     * 生基板の更新
     * @param App\Models\Board\PrintCircuitBoard $printCircuitBoard
     * @param App\Http\Requests\Board\PrintCircuitBoardRequest $request
     * @return App\Models\Board\PrintCircuitBoard
     * @throws \Exception
     */
    public function update(PrintCircuitBoard $printCircuitBoard, PrintCircuitBoardRequest $request)
    {
        try {
            $printCircuitBoard = DB::transaction(function () use ($request, $printCircuitBoard) {
                $printCircuitBoard->valid_years = $request->valid_years;
                $printCircuitBoard->description = $request->description;
                $printCircuitBoard->ordered_at = $request->ordered_at;
                $printCircuitBoard->save();

                return $printCircuitBoard;
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }

        return $printCircuitBoard;
    }

    /**
     * 生基板の削除
     * @param App\Models\Board\PrintCircuitBoard $printCircuitBoard
     * @throws \Exception
     */
    public function destroy(PrintCircuitBoard $printCircuitBoard)
    {
        try {
            DB::transaction(function () use ($printCircuitBoard) {
                $printCircuitBoard->delete();
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
