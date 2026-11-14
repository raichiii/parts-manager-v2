<?php

namespace App\Services\Board;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use App\Models\Board\Part;
use App\Models\Address\Company;
use App\Http\Requests\Board\PartRequest;
use App\Traits\Csv;

class PartService
{
    use Csv;

    /**
     * 部品の作成
     * @param App\Http\Requests\Board\PartRequest $request
     * @return App\Models\Board\Part
     * @throws \Exception
     */
    public function create(PartRequest $request)
    {
        try {
            $part = DB::transaction(function () use ($request) {
                $part = new Part;
                $part->thing_id = $request->thing_id;
                $part->name = $request->name;
                $part->short_name = $request->short_name;
                $part->english_name = $request->english_name;
                $part->pno = $request->pno;
                $part->p = $request->p;
                $part->common = $request->common;
                $part->classification = $request->classification;
                $part->status = $request->status;
                $part->interchange = $request->interchange;
                $part->alternative = $request->alternative;
                $part->note = $request->note;
                $part->save();

                return Part::find($part->id);
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }

        return $part;
    }

    /**
     * 部品の変更
     * @param App\Models\Board\Part $part
     * @param Illuminate\Http\Request $request
     * @return App\Models\Board\Part
     * @throws \Exception
     */
    public function update(Part $part, PartRequest $request)
    {
        try {
            $part = DB::transaction(function () use ($request, $part) {
                $part->name = $request->name;
                $part->short_name = $request->short_name;
                $part->english_name = $request->english_name;
                $part->pno = $request->pno;
                $part->p = $request->p;
                $part->common = $request->common;
                $part->classification = $request->classification;
                $part->status = $request->status;
                $part->interchange = $request->interchange;
                $part->alternative = $request->alternative;
                $part->note = $request->note;
                $part->save();

                return $part;
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }

        return $part;
    }

    /**
     * 部品の削除
     * @param App\Models\Board\Part $part
     * @throws \Exception
     */
    public function destroy(Part $part)
    {
        try {
            DB::transaction(function () use ($part) {
                $part->delete();
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * 部品実装数検索
     * @param null
     * @throws \Exception
     */
    public function kensaku()
    {
    }

    /**
     * 部品リスト変更
     * @param Illuminate\Http\Request $request->file
     * @throws \Exception
     */
    public function changeFileBuhinList($fileBuhinList)
    {
        try {
            $buhinListPath = DB_CSV_DIR . 'buhin_list.csv';
            $buhinListOldPath = DB_CSV_DIR . 'buhin_list_old.csv';
            $buhinListDataPath = DB_CSV_DIR . 'buhin_list_data.csv';
            $data = [
                'id', 'model_no', 'sum', 'credibility', 'stock', 'pno', 'pno1',
                'pno2', 'p', 'name', 'classification', 'maker', 'status',
            ];
            $row = file($fileBuhinList)[0];
            $colNum = mb_substr_count($row, ',') + 1 - count($data);
            $colNames = $data;
            for ($i = $colNum; $i > 0; $i--) {
                array_splice($colNames, 3, 0, 'n' . $i);
            }
            $csvArray = Csv::uploadedCsvToArray($fileBuhinList, $colNames);
            $csvArray = array_slice($csvArray, 4);
            $csvArrayT = array_map(null, ...$csvArray);
            unset($csvArrayT[$colNum + 5]); #p
            unset($csvArrayT[$colNum + 6]); #pno1
            unset($csvArrayT[$colNum + 8]); #pno
            unset($csvArrayT[2]); #sum
            unset($csvArrayT[0]); #id
            $csvArray = array_map(null, ...$csvArrayT);
            if (file_exists($buhinListPath)) {
                copy($buhinListPath, $buhinListOldPath);
            }
            copy($fileBuhinList, $buhinListPath);
            $buhinListData = fopen($buhinListDataPath, 'w');
            foreach ($csvArray as $row) {
                $strRow = implode(',', $row);
                fwrite($buhinListData, $strRow . "\n");
            }
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * 基板ジョブリスト変更
     * @param Illuminate\Http\Request $request->file
     * @throws \Exception
     */
    public function changeFileBoardJob($fileBoardJob)
    {
        try {
            $boardJobPath = DB_CSV_DIR . 'board_job.csv';
            $boardJobOldPath = DB_CSV_DIR . 'board_job_old.csv';
            if (file_exists($boardJobPath)) copy($boardJobPath, $boardJobOldPath);
            copy($fileBoardJob, $boardJobPath);
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * 部品検索
     * @param null
     * @throws \Exception
     */
    public function getAll()
    {
        try {
            $buhinListPath = DB_CSV_DIR . 'buhin_list.csv';
            $buhinListDataPath = DB_CSV_DIR . 'buhin_list_data.csv';
            $data = [
                'id', 'model_no', 'sum', 'credibility', 'stock', 'pno', 'pno1',
                'pno2', 'p', 'name', 'classification', 'maker', 'status',
            ];
            $dataCol = [
                'ID', '型式', '合計', '信憑性', '在庫', '', '',
                'P番号', '', '名前', '分類', 'メーカー', 'ステータス',
            ];
            if (!file_exists($buhinListPath)) {
                throw new \Exception("基板マスタが存在しません");
            }
            $buhinListDatas = file_get_contents($buhinListDataPath);
            $buhinListDatas = explode("\n", $buhinListDatas);
            $row = file($buhinListPath)[0];
            $colNum = mb_substr_count($row, ',') + 1 - count($data);
            $colNames = $data;
            for ($i = $colNum; $i > 0; $i--) {
                array_splice($colNames, 3, 0, 'n' . $i);
            }
            try {
                $csvArray = Csv::uploadedCsvToArray($buhinListPath, $colNames);
            } catch (\Exception $exception) {
                throw $exception;
            }
            $idxP = array_search('p', $colNames);
            $csvArrayT = array_map(null, ...$csvArray);
            $Ps = array_slice($csvArrayT[$idxP], 4);
            $boardNames = $csvArray[2];
            for ($i = 0; $i < count($data); $i++) {
                $boardNames[$data[$i]] = $dataCol[$i];
            }
            $parts = array_values(array_slice($csvArray, 4));

            $boardJobArray = file_get_contents(DB_CSV_DIR . 'board_job.csv');
            $boardJobArray = explode("\n", $boardJobArray);
            foreach (explode(',', $boardJobArray[0]) as $i => $colName) {
                if ($colName == "JOB NO") {
                    global $colJobNo, $colRJobNo;
                    $colJobNo = $i;
                    $colRJobNo = $i + 1;
                    break;
                }
            }
            unset($boardJobArray[0]);
            $boardJob = array();
            foreach ($boardJobArray as $row) {
                $cells = explode(',', $row);
                if (count($cells) >= 6) {
                    $boardJob = array_merge(
                        $boardJob,
                        array($cells[1] => [$cells[$colJobNo], $cells[$colRJobNo]])
                    );
                }
            }

            return array($boardNames, $parts, $buhinListDatas, $Ps, $boardJob);
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
