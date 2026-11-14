<?php

namespace App\Services\Python;

use Illuminate\Support\Facades\Redirect;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class PythonService
{
    public static $CALC_DIFF = 'calc_diff';

    /**
     * Pythonの実行
     * @param String $file
     * @return Object
     * @throws ProcessFailedException
     */
    public function exec($file, $arg=null)
    {
        $process = new Process([
            'python3',
            base_path().'/app/Services/Python/'.$file.'.py',
            $arg,
        ]);
        try {
            $process->mustRun();
            return $process->getOutput();
        } catch (ProcessFailedException $exception) {
            return view($exception->getMessage());
        }
    }
}
