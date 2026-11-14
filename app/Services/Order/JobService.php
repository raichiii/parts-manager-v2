<?php

namespace App\Services\Order;

use Illuminate\Support\Facades\DB;
use App\Models\Order\Job;
use App\Http\Requests\Order\JobRequest;

class JobService
{
    /**
     * ジョブの作成
     * @param App\Http\Requests\Order\JobRequest $request
     * @return App\Models\Order\Job
     * @throws \Exception
     */
    public function create(JobRequest $request)
    {
        try {
            $job = DB::transaction(function () use ($request) {
                $job = new Job;
                $job->number = $request->number;
                $job->name = $request->name;
                $job->requester_id = $request->requester_id;
                $job->delivery_destination_id = $request->delivery_destination_id;
                $job->description = $request->description;
                $job->save();

                return $job;
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }

        return $job;
    }

    /**
     * ジョブの変更
     * @param \App\Models\Order\Job $job
     * @param App\Http\Requests\Order\JobRequest $request
     * @return App\Models\Order\Job
     * @throws \Exception
     */
    public function update(Job $job, JobRequest $request)
    {
        try {
            $job = DB::transaction(function () use ($job, $request) {
                $job->number = $request->number;
                $job->name = $request->name;
                $job->requester_id = $request->requester_id;
                $job->delivery_destination_id = $request->delivery_destination_id;
                $job->description = $request->description;
                $job->save();

                return Job::find($job->id);
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage(), $exception->getCode());
        }
        return $job;
    }

    /**
     * ジョブの削除
     * @param \App\Models\Order\Job $job
     * @throws \Exception
     */
    public function destroy(Job $job)
    {
        try {
            DB::transaction(function () use ($job) {
                $job->delete();
            });
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
