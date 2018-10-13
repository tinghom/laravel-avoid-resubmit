<?php

namespace Tinghom\Middleware;

use Carbon\Carbon;
use Closure;

class AvoidResubmit
{
    private $token;
    private $runFlag;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->token = $request->session()->token();

        if ($request->isMethod('GET')) {
            return $next($request);
        }

        if (app('cache')->has($this->token)) {
            $this->cacheToken();
            return back();
        }

        $this->cacheToken();
        $this->cacheRunFlag();
        $this->waitingToStopSubmitting();

        if (app('cache')->has($this->getRunFlagName()) &&
            app('cache')->forget($this->getRunFlagName())
        ) {
            return $next($request);
        }

        return abort(403, 'has been submitted');
    }

    /**
     * 等候停止提交
     *
     * @return void
     */
    protected function waitingToStopSubmitting()
    {
        while (app('cache')->has($this->token)) {
            usleep($this->getSleepMicroseconds());
        }
    }

    /**
     * 取得等候停止提交時的間隔微秒數
     *
     * @return int
     */
    protected function getSleepMicroseconds()
    {
        return 200000;
    }

    private function getRunFlagName()
    {
        return "{$this->token}_run_flag";
    }

    private function cacheToken()
    {
        app('cache')->put($this->token, true, Carbon::now()->addSecond());
    }

    private function cacheRunFlag()
    {
        app('cache')->put($this->getRunFlagName(), true, Carbon::now()->addSecond(60));
    }
}
