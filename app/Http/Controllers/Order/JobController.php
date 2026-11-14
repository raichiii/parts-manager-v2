<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\Order\Job;
use App\Http\Requests\Order\JobRequest;
use App\Services\Order\JobService;
use App\Listeners\LineEventSubscriber;

use function PHPSTORM_META\type;

class JobController extends Controller
{
    private $jobService;
    private $lineEventSubscriber;

    public function __construct(JobService $jobService,
        LineEventSubscriber $lineEventSubscriber)
    {
        $this->jobService = $jobService;
        $this->lineEventSubscriber = $lineEventSubscriber;
    }


    /**
     * ジョブの一覧
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        $jobs = Job::with(['requester', 'deliveryDestination'])->orderBy('id', 'desc')->get();
        return view('order.jobs.index', compact('jobs'));
    }


    /**
     * ジョブの作成画面
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        return view('order.jobs.create');
    }


    /**
     * ジョブの作成
     * @param App\Http\Requests\Order\JobRequest $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(JobRequest $request)
    {
        try {
            $job = $this->jobService->create($request);
            $this->lineEventSubscriber->handleJobCreated($job);
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getMessage());
        }
        return redirect()->route('jobs.index')->with('success', 'Jobが作成されました');
    }


    /**
     * ジョブの表示
     * @param \App\Models\Order\Job $job
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function show(Job $job)
    {
        return view('order.jobs.show', compact('job'));
    }


    /**
     * ジョブの編集画面
     * @param \App\Models\Order\Job $job
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function edit(Job $job)
    {
        $job = $job->load('requester', 'deliveryDestination');
        return view('order.jobs.edit', compact('job'));
    }


    /**
     * ジョブの変更
     * @param \App\Models\Order\Job $job
     * @param \App\Http\Requests\Order\JobRequest $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(Job $job, JobRequest $request)
    {
        try {
            $job = $this->jobService->update($job, $request);
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getMessage());
        }
        return redirect()->route('jobs.index')->with('success', '変更を保存しました');
    }


    /**
     * ジョブの削除
     * @param \App\Models\Order\Job $job
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Job $job)
    {
        try {
            if ($job->hasOrder()) {
                return Redirect::back()->withErrors('購入依頼が存在するため削除できません');
            }
            $this->jobService->destroy($job);
        } catch (\Exception $exception) {
            return Redirect::back()
                ->withErrors($exception->getMessage());
        }
        return redirect()->route('jobs.index')->with('success', 'ジョブを削除しました');
    }


    /**
     * 全ての会社取得のAPI
     * @return Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function getAll()
    {
        $jobs = Job::all();
        return response()->json($jobs);
    }


    /**
     * Job検索用のAPI
     * @param Request $request
     * @return Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function search(Request $request)
    {
        $keyword = $request->query('query');
        try{
            $jobs = Job::where('number', 'like', '%' . $keyword . '%')
                ->orWhere('name', 'like', '%' . $keyword . '%')
                ->get();
            foreach($jobs as $job) {
                $job->name = $job->number.'('.$job->name.')';
            }
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 422);
        }
        return response()->json($jobs);
    }
}
