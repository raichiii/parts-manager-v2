<?php

namespace App\Services\Board;

use App\Traits\Csv;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Board\MountedBoard;
use App\Http\Requests\Board\MountedBoardRequest;
use App\Services\Csv\CsvImportPartService;

class MountedBoardService
{
    use Csv;

    /**
     * 基板の作成
     * @param App\Http\Requests\Board\MountedBoardRequest $request
     * @return App\Models\Board\MountedBoard
     * @throws \Exception
     */
    public function create(MountedBoardRequest $request)
    {
        try {
            $mountedBoard = DB::transaction(function () use ($request) {
                $latestVersion = MountedBoard::where('print_circuit_board_id', $request->print_circuit_board_id)
                    ->where('version_name', $request->version_name)->get()->last();
                if($latestVersion){
                    $request->version = $latestVersion->version + 1;
                }
                $mountedBoard = new MountedBoard;
                $mountedBoard->print_circuit_board_id = $request->print_circuit_board_id;
                $mountedBoard->version_name = $request->version_name;
                $mountedBoard->version = $request->version;
                $mountedBoard->description = $request->description;
                $mountedBoard->save();

                foreach ((array)$request->parts as $index => $part) {
                    $mountedBoard->parts()->attach($part, array('reference_number' => $request->reference_number[$index], 'is_mounted' => $request->is_mounted[$index]));
                }
                //csvファイルが添付されていた場合の処理
                if ($request->exists('csv_parts')) {
                    foreach ((array)$request->csv_parts as $part) {
                        $mountedBoard->parts()->attach($part['part_id'], array('reference_number' => $part['reference_number'], 'is_mounted' => $part['is_mounted']));
                    }
                }
                return mountedBoard::find($mountedBoard->id);
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }

        return $mountedBoard;
    }

    /**
     * 基板の変更
     * @param App\Models\Board\MountedBoard $mountedBoard
     * @param App\Http\Requests\Board\MountedBoardRequest $request
     * @return App\Models\Board\MountedBoard
     * @throws \Exception
     */
    public function update(MountedBoard $mountedBoard, MountedBoardRequest $request)
    {
        try {
            $mountedBoard = DB::transaction(function () use ($request, $mountedBoard) {
                $mountedBoard->print_circuit_board_id = $request->print_circuit_board_id;
                $mountedBoard->version_name = $request->version_name;
                $mountedBoard->version = $request->version;
                $mountedBoard->description = $request->description;
                $mountedBoard->save();

                $mountedBoard->parts()->detach();
                foreach ((array)$request->parts as $index => $part) {
                    $mountedBoard->parts()->attach($part, array('reference_number' => $request->reference_number[$index], 'is_mounted' => $request->is_mounted[$index]));
                }
                //csvファイルが添付されていた場合の処理
                if ($request->exists('csv_parts')) {
                    foreach ((array)$request->csv_parts as $part) {
                        $mountedBoard->parts()->attach($part['part_id'], array('reference_number' => $part['reference_number'], 'is_mounted' => $part['is_mounted']));
                    }
                }
                return mountedBoard::find($mountedBoard->id);
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }
        return $mountedBoard;
    }

    /**
     * 基板の削除
     * @param App\Models\Board\MountedBoard $mountedBoard
     * @throws \Exception
     */
    public function destroy(MountedBoard $mountedBoard)
    {
        try {
            DB::transaction(function () use ($mountedBoard) {
                $mountedBoard->delete();
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * csvの情報をrequestにマージするメソッド
     * @param App\Http\Requests\Board\MountedBoardRequest $request
     * @return \Illuminate\Http\Request
     * @throws \Exception
     */
    public function mergeCsvToRequest(MountedBoardRequest $request)
    {
        $errorList = [];
        $csvImportPartService = app()->make(CsvImportPartService::class);
        try {
            //csvのデータを配列に
            $csvArray = $csvImportPartService->uploadedCsvToArray($request->file('csv_file'));
            //csvが空でない場合
            if (!empty($csvArray)) {
                $modelNumbers = $csvImportPartService->getModelNumbers($csvArray);
                //部品のテーブルに登録されていない部品が一つでもあるならば、未登録一覧を返す。
                if (!empty($modelNumbers['unregistered'])) {
                    return $request->merge([
                        'unregistered_parts' => $modelNumbers['unregistered'],
                    ]);
                }
                foreach ($csvArray as &$row) {
                    $validator = Validator::make(
                        $row,
                        $csvImportPartService->validationRules(),
                        $csvImportPartService->validationMessages(),
                    );
                    if ($validator->fails()) {
                        $errorList[] = $row['model_no'];
                    }
                    // 型番をキーに部品IDの情報をmodelNumbersから取得する。
                    $row['part_id'] = $modelNumbers['registered'][$row['model_no']];
                }
                // foreachでデータを追加しているため、unset
                unset($row);
            }
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
        if (empty($errorList)) {
            $request->merge([
                'csv_parts' => $csvArray
            ]);
        } else {
            $request->merge([
                'errors' => $errorList
            ]);
        }
        return $request;
    }
}
