<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\StreamedResponse;

trait Csv
{
    /**
     * csvファイルからデータを抽出し、配列で返す
     * @param mixed $file
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public static function uploadedCsvToArray($file, array $data): array
    {
        $dataLength = count($data);
        $resultArray = [];
        $csv = new \SplFileObject($file);
        $csv->setCsvControl(escape: "\\");
        $csv->seek(PHP_INT_MAX);
        $csvLastRow = $csv->key();
        $csv->setFlags(
            \SplFileObject::READ_CSV     |
                \SplFileObject::READ_AHEAD   |
                \SplFileObject::SKIP_EMPTY   |
                \SplFileObject::DROP_NEW_LINE
        );
        while (is_null($csv->current())) {
            $csv->seek($csv->key() - 1);
        }
        $csvLastBuhinRow = $csv->key();
        $csv->rewind();
        foreach ($csv as $row) {
            if (count($row) == 0) {
                continue;
            }
            if ($dataLength != count($row)) {
                if ($csv->key() == $csvLastBuhinRow - 1) {
                    $emptyStrs = array_fill(0, count($data) - count($row), '');
                    $row = array_merge($row, $emptyStrs);
                } else {
                    $displayRowNum = $csv->key() + 1;
                    throw new \Exception("{$displayRowNum}行目の区切りカンマの数が異なります。");
                }
            }
            foreach ($data as $index => $value) {
                $rowData[$value] = $row[$index];
            }
            $resultArray[] = $rowData;
            $rowData = [];
        }

        return $resultArray;
    }

    /**
     * csvファイルからデータを抽出し、配列で返す（SJIS補正）
     * @param mixed $file
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public static function uploadedCsvToArraySJIS($file, array $data): array
    {
        $resultArray = [];
        $csv = new \SplFileObject($file);
        $csv->setFlags(
            \SplFileObject::READ_AHEAD   |
                \SplFileObject::SKIP_EMPTY   |
                \SplFileObject::DROP_NEW_LINE
        );
        foreach ($csv as $key => $row) {
            $row = mb_convert_encoding($row, 'UTF-8', 'SJIS');
            $row = str_getcsv($row, escape: "\\");
            if (count($data) != count($row)) {
                throw new \Exception();
            }
            foreach ($data as $index => $value) {
                $tmp[$value] = $row[$index];
            }
            $resultArray[] = $tmp;
            $tmp = [];
        }
        return $resultArray;
    }

    /**
     * 引数のファイルパスに基づき、csvファイルを作成
     * @param mixed $filePath
     * @return mixed
     * @throws \Exception
     */
    public static function makeCsv($filePath)
    {
        $stream = fopen($filePath, 'w');
        if ($stream == false) {
            throw new \Exception("CSVファイルを作成出来ませんでした");
        } else {
            fwrite($stream, pack('C*', 0xEF, 0xBB, 0xBF)); // BOM をつける
        }
        fclose($stream);

        return $filePath;
    }

    /**
     * ファイルにCSVの配列を書き込む
     * @param array $header
     * @param array $csv
     * @param mixed $file
     */
    public static function write(array $header, array $csv, $file)
    {
        if (!is_null($header)) {
            array_unshift($csv, $header);
        }
        $stream = fopen($file, 'a');
        foreach ($csv as $row) {
            fputcsv($stream, $row, escape: "\\");
        }
        fclose($stream);
    }

    /**
     * ファイルにCSVの配列を書き込み、レスポンスとして返す
     * @param array $header
     * @param array $csv
     * @param mixed $file
     * @return \Symfony\Component\HttpFoundation\StreamedResponse $response
     */
    public static function downloadCsv(array $header, array $csv, $file)
    {
        if (!is_null($header)) {
            array_unshift($csv, $header);
        }

        $response = new StreamedResponse(function () use ($csv) {
            $stream = fopen('php://output', 'w');
            stream_filter_prepend($stream, 'convert.iconv.utf-8/cp932//TRANSLIT');
            foreach ($csv as $row) {
                fputcsv($stream, $row, escape: "\\");
            }
            fclose($stream);
        });

        $response->headers->set('Content-Type', 'application/x-download;charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $file);
        return $response;
    }
}
