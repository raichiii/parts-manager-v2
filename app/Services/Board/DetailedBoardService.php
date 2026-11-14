<?php

namespace App\Services\Board;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Board\DetailedBoard;
use App\Http\Requests\Board\DetailedBoardRequest;

class DetailedBoardService
{
    /**
     * 在庫基板の詳細記録の作成
     * @param Illuminate\Http\Request $request
     * @return App\Models\Board\DetailedBoard
     * @throws \Exception
     */
    public function create(DetailedBoardRequest $request)
    {
        try {
            $detailedBoard = DB::transaction(function () use ($request) {
                $detailedBoard = new DetailedBoard;
                $detailedBoard->mounted_board_id = $request->mounted_board_id;
                $detailedBoard->hardware_operation = $request->hardware_operation;
                $detailedBoard->adjustment_calibration = $request->adjustment_calibration;
                $detailedBoard->shipment = $request->shipment;
                $detailedBoard->software_version = $request->software_version;
                $detailedBoard->check_sum = $request->check_sum;
                $detailedBoard->lot_amount = $request->lot_amount;
                $detailedBoard->production_date = $request->production_date;
                $detailedBoard->user_id = $request->user_id;
                $detailedBoard->depository = $request->storage_operation;
                $detailedBoard->note = $request->note;
                $detailedBoard->save();

                return DetailedBoard::find($detailedBoard->id);
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }

        return $detailedBoard;
    }

    /**
     * 在庫基板の詳細情報の変更
     * @param App\Models\Board\DetailedBoard $detailedBoard
     * @param App\Http\Requests\Board\DetailedBoardRequest $request
     * @return App\Models\Board\DetailedBoard
     * @throws \Exception
     */
    public function update(DetailedBoard $detailedBoard, DetailedBoardRequest $request)
    {
        //基板が登録済みかどうかの判定
        if (!($request->detailed_board_id) && !($request->mounted_board_id)) {
            throw new \Exception("この基板は存在しません");
        }
        try {
            $detailedBoard = DB::transaction(function () use ($request, $detailedBoard) {
                $detailedBoard->mounted_board_id = $request->mounted_board_id;
                $detailedBoard->hardware_operation = $request->hardware_operation;
                $detailedBoard->adjustment_calibration = $request->adjustment_calibration;
                $detailedBoard->shipment = $request->shipment;
                $detailedBoard->software_version = $request->software_version;
                $detailedBoard->check_sum = $request->check_sum;
                $detailedBoard->lot_amount = $request->lot_amount;
                $detailedBoard->production_date = $request->production_date;
                $detailedBoard->user_id = $request->user_id;
                $detailedBoard->depository = $request->storage_operation;
                $detailedBoard->note = $request->note;
                $detailedBoard->save();

                return DetailedBoard::find($detailedBoard->id);
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }

        return $detailedBoard;
    }

    /**
     * 部品の削除
     * @param App\Models\Board\DetailedBoard $detailedBoard
     * @throws \Exception
     */
    public function destroy(DetailedBoard $detailedBoard)
    {
        try {
            DB::transaction(function () use ($detailedBoard) {
                $detailedBoard->delete();
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * 詳細基板検索
     * @param null
     * @throws \Exception
     */
    public function getAll()
    {
        try {
            $dataPath = DB_CSV_DIR.'detailed_records_of_kibans.csv';
            $data = file_get_contents($dataPath);
            $data = explode("\n", $data);

            return $data;

        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * 詳細基板リスト変更
     * @param Illuminate\Http\Request $request->file
     * @throws \Exception
     */
    public function changeFileDetailedBoard($file)
    {
        try {
            $filePath = DB_CSV_DIR.'detailed_records_of_kibans.csv';
            $fileOldPath = DB_CSV_DIR.'detailed_records_of_kibans_old.csv';
            if (file_exists($filePath)) copy($filePath, $fileOldPath);

            $file = fopen($file->path($file), 'r');
            $fw = fopen($filePath, 'w');
            $line = '';
            while (!feof($file)) {
                $txt = fgets($file);
                if (substr($txt, -2, 1)=="\"" && substr($txt, -3, 2)!=",\"") {
                    $line .= $txt;
                    fwrite($fw, $line);
                    $line = '';
                } else {
                    $line .= rtrim($txt)."\\n";
                }
            }

        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
