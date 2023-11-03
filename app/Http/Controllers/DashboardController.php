<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\Link;
use App\Models\Pixel;
use App\Models\Space;
use Illuminate\Http\Request;
use App\Traits\DateRangeTrait;
use Carbon\Carbon;
use App\Models\Stat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    use DateRangeTrait;
    /**
     * Show the Dashboard page.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        // If the user previously selected a plan
        if (!empty($request->session()->get('plan_redirect'))) {
            return redirect()->route('checkout.index', ['id' => $request->session()->get('plan_redirect')['id'], 'interval' => $request->session()->get('plan_redirect')['interval']]);
        }

        $latestLinks = Link::with('domain')->where('user_id', $request->user()->id)->orderBy('id', 'desc')->limit(5)->get();

        $clicks = [];

        $popularLinks = Link::with('domain')->where('user_id', $request->user()->id)->orderBy('clicks', 'desc')->limit(5)->get();

        $stats = [
            'spaces' => Space::where('user_id', $request->user()->id)->count(),
            'domains' => Domain::where('user_id', $request->user()->id)->count(),
            'pixels' => Pixel::where('user_id', $request->user()->id)->count()
        ];

        $range = $this->range(Carbon::now()->subDays(29),  Carbon::now());
        $clicksMap = $this->getTraffic($range);

        $totalClicks = 0;
        foreach ($clicksMap as $key => $value) {
            $totalClicks = $totalClicks + $value;
        }

        $totalClicksOld = Stat::where([['name', '=', 'clicks']])
            ->whereBetween('date', [$range['from_old'], $range['to_old']])
            ->sum('count');

        $totalReferrers = Stat::where([['name', '=', 'referrer']])
            ->whereBetween('date', [$range['from'], $range['to']])
            ->sum('count');

        $referrers = $this->getReferrers($range, null, null, 'count', 'desc')
            ->limit(5)
            ->get();

        $countries = $this->getCountries($range, null, null, 'count', 'desc')
            ->limit(5)
            ->get();

        $browsers = $this->getBrowsers($range, null, null, 'count', 'desc')
            ->limit(5)
            ->get();

        $platforms = $this->getPlatforms($range, null, null, 'count', 'desc')
            ->limit(5)
            ->get();

        $first_link = Link::where('user_id', $request->user()->id)->orderBy('created_at', 'asc')->get()->first();

        return view('dashboard.index', [
            'user' => $request->user(), 'latestLinks' => $latestLinks,
            'clicks' => $clicks, 'popularLinks' => $popularLinks,
            'stats' => $stats, 'range' => $range,
            'clicksMap' => $clicksMap,
            'totalClicks' => $totalClicks,
            'totalClicksOld' => $totalClicksOld,
            'totalReferrers' => $totalReferrers,
            'referrers' => $referrers,
            'countries' => $countries,
            'browsers' => $browsers,
            'platforms' => $platforms,
            'first_link_created' => $first_link == null ? date('Y-m-d') : date('Y-m-d', strtotime($first_link->created_at))
        ]);
    }

    private function getReferrers($range, $search = null, $searchBy = null, $sortBy = null, $sort = null)
    {
        return Stat::selectRaw('`value`, SUM(`count`) as `count`')
            ->where([['name', '=', 'referrer'], ['value', '<>', '']])
            ->when($search, function ($query) use ($search, $searchBy) {
                return $query->searchValue($search);
            })
            ->whereBetween('date', [$range['from'], $range['to']])
            ->groupBy('value')
            ->orderBy($sortBy, $sort);
    }

    private function getCountries($range, $search = null, $searchBy = null, $sortBy = null, $sort = null)
    {
        return Stat::selectRaw('`value`, SUM(`count`) as `count`')
            ->where([['name', '=', 'country']])
            ->when($search, function ($query) use ($search, $searchBy) {
                return $query->searchValue($search);
            })
            ->whereBetween('date', [$range['from'], $range['to']])
            ->groupBy('value')
            ->orderBy($sortBy, $sort);
    }

    private function getBrowsers($range, $search = null, $searchBy = null, $sortBy = null, $sort = null)
    {
        return Stat::selectRaw('`value`, SUM(`count`) as `count`')
            ->where([['name', '=', 'browser']])
            ->when($search, function ($query) use ($search, $searchBy) {
                return $query->searchValue($search);
            })
            ->whereBetween('date', [$range['from'], $range['to']])
            ->groupBy('value')
            ->orderBy($sortBy, $sort);
    }

    private function getPlatforms($range, $search = null, $searchBy = null, $sortBy = null, $sort = null)
    {
        return Stat::selectRaw('`value`, SUM(`count`) as `count`')
            ->where([['name', '=', 'platform']])
            ->when($search, function ($query) use ($search, $searchBy) {
                return $query->searchValue($search);
            })
            ->whereBetween('date', [$range['from'], $range['to']])
            ->groupBy('value')
            ->orderBy($sortBy, $sort);
    }

    private function getCities($range, $search = null, $searchBy = null, $sortBy = null, $sort = null)
    {
        return Stat::selectRaw('`value`, SUM(`count`) as `count`')
            ->where([['name', '=', 'city']])
            ->when($search, function ($query) use ($search, $searchBy) {
                return $query->searchValue($search);
            })
            ->whereBetween('date', [$range['from'], $range['to']])
            ->groupBy('value')
            ->orderBy($sortBy, $sort);
    }

    private function getTraffic($range)
    {
        // If the date range is for a single day
        if ($range['unit'] == 'hour') {
            $rows = Stat::where([['name', '=', 'clicks_hours']])
                ->whereBetween('date', [$range['from'], $range['to']])
                ->orderBy('date', 'asc')
                ->get();
            $output = ['00' => 0, '01' => 0, '02' => 0, '03' => 0, '04' => 0, '05' => 0, '06' => 0, '07' => 0, '08' => 0, '09' => 0, '10' => 0, '11' => 0, '12' => 0, '13' => 0, '14' => 0, '15' => 0, '16' => 0, '17' => 0, '18' => 0, '19' => 0, '20' => 0, '21' => 0, '22' => 0, '23' => 0];

            // Map the values to each date
            foreach ($rows as $row) {
                if (!isset($output[$row->value])){
                    $output[$row->value] = 0;
                }
                $output[$row->value] += $row->count;
            }
        } else {
            $rows = Stat::select([
                    DB::raw("date_format(`date`, '" . str_replace(['Y', 'm', 'd'], ['%Y', '%m', '%d'], $range['format']) . "') as `date_result`, SUM(`count`) as `aggregate`")
                ])
                ->whereBetween('date', [$range['from'], $range['to']])
                ->where([['name', '=', 'clicks']])
                ->groupBy('date_result')
                ->orderBy('date_result', 'asc')
                ->get();

            $rangeMap = $this->calcAllDates(Carbon::createFromFormat('Y-m-d', $range['from'])->format($range['format']), Carbon::createFromFormat('Y-m-d', $range['to'])->format($range['format']), $range['unit'], $range['format'], 0);

            // Remap the result set, and format the array
            $collection = $rows->mapWithKeys(function ($result) use ($range) {
                return [strval($range['unit'] == 'year' ? $result->date_result : Carbon::parse($result->date_result)->format($range['format'])) => $result->aggregate];
            })->all();

            // Merge the results with the pre-calculated possible time range
            $output = array_replace($rangeMap, $collection);
        }
        return $output;
    }

    public function all_referrers(Request $request)
    {
        $link = Link::where([['user_id', '<>', 0]])->orderBy('created_at', 'asc')->firstOrFail();

        if ($this->guard($link)) {
            return view('stats.password', ['link' => $link]);
        }

        $range = $this->range($link->created_at);
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? $request->input('per_page') : config('settings.paginate');

        $total = Stat::selectRaw('SUM(`count`) as `count`')
            ->where([['name', '=', 'referrer'], ['value', '<>', '']])
            ->whereBetween('date', [$range['from'], $range['to']])
            ->first();

        $referrers = $this->getReferrers($range, $search, $searchBy, $sortBy, $sort)
            ->paginate($perPage)
            ->appends(['from' => $range['from'], 'to' => $range['to'], 'search' => $search, 'search_by' => $searchBy, 'sort_by' => $sortBy, 'sort' => $sort]);

        $first = $this->getReferrers($range, $search, $searchBy, 'count', 'desc')
            ->first();

        $last = $this->getReferrers($range, $search, $searchBy, 'count', 'asc')
            ->first();

        return view('stats.container', ['view' => 'referrers', 'link' => $link, 'range' => $range, 'export' => 'stats.export.referrers', 'referrers' => $referrers, 'first' => $first, 'last' => $last, 'total' => $total]);
    }

    public function countries(Request $request)
    {
        $link = Link::where([['user_id', '<>', 0]])->orderBy('created_at', 'asc')->firstOrFail();

        if ($this->guard($link)) {
            return view('stats.password', ['link' => $link]);
        }

        $range = $this->range($link->created_at);
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? $request->input('per_page') : config('settings.paginate');

        $total = Stat::selectRaw('SUM(`count`) as `count`')
            ->where([['name', '=', 'country']])
            ->whereBetween('date', [$range['from'], $range['to']])
            ->first();

        $countriesChart = $this->getCountries($range, $search, $searchBy, $sortBy, $sort)
            ->get();

        $countries = $this->getCountries($range, $search, $searchBy, $sortBy, $sort)
            ->paginate($perPage)
            ->appends(['from' => $range['from'], 'to' => $range['to'], 'search' => $search, 'search_by' => $searchBy, 'sort_by' => $sortBy, 'sort' => $sort]);

        $first = $this->getCountries($range, $search, $searchBy, 'count', 'desc')
            ->first();

        $last = $this->getCountries($range, $search, $searchBy, 'count', 'asc')
            ->first();

        return view('stats.container', ['view' => 'countries', 'link' => $link, 'range' => $range, 'export' => 'stats.export.countries', 'countries' => $countries, 'countriesChart' => $countriesChart, 'first' => $first, 'last' => $last, 'total' => $total]);
    }

    public function all_cities(Request $request)
    {
        $link = Link::where([['user_id', '<>', 0]])->orderBy('created_at', 'asc')->firstOrFail();

        if ($this->guard($link)) {
            return view('stats.password', ['link' => $link]);
        }

        $range = $this->range($link->created_at);
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? $request->input('per_page') : config('settings.paginate');

        $total = Stat::selectRaw('SUM(`count`) as `count`')
            ->where([['name', '=', 'city']])
            ->whereBetween('date', [$range['from'], $range['to']])
            ->first();

        $cities = $this->getCities($range, $search, $searchBy, $sortBy, $sort)
            ->paginate($perPage)
            ->appends(['from' => $range['from'], 'to' => $range['to'], 'search' => $search, 'search_by' => $searchBy, 'sort_by' => $sortBy, 'sort' => $sort]);

        $first = $this->getCities($range, $search, $searchBy, 'count', 'desc')
            ->first();

        $last = $this->getCities($range, $search, $searchBy, 'count', 'asc')
            ->first();

        return view('stats.container', ['view' => 'cities', 'link' => $link, 'range' => $range, 'export' => 'stats.export.cities', 'cities' => $cities, 'first' => $first, 'last' => $last, 'total' => $total]);
    }

    public function all_browsers(Request $request)
    {
        $link = Link::where([['user_id', '<>', 0]])->orderBy('created_at', 'asc')->firstOrFail();

        if ($this->guard($link)) {
            return view('stats.password', ['link' => $link]);
        }

        $range = $this->range($link->created_at);
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? $request->input('per_page') : config('settings.paginate');

        $total = Stat::selectRaw('SUM(`count`) as `count`')
            ->where([['name', '=', 'browser']])
            ->whereBetween('date', [$range['from'], $range['to']])
            ->first();

        $browsers = $this->getBrowsers($range, $search, $searchBy, $sortBy, $sort)
            ->paginate($perPage)
            ->appends(['from' => $range['from'], 'to' => $range['to'], 'search' => $search, 'search_by' => $searchBy, 'sort_by' => $sortBy, 'sort' => $sort]);

        $first = $this->getBrowsers($range, $search, $searchBy, 'count', 'desc')
            ->first();

        $last = $this->getBrowsers($range, $search, $searchBy, 'count', 'asc')
            ->first();

        return view('stats.container', ['view' => 'browsers', 'link' => $link, 'range' => $range, 'export' => 'stats.export.browsers', 'browsers' => $browsers, 'first' => $first, 'last' => $last, 'total' => $total]);
    }

    public function all_platforms(Request $request)
    {
        $link = Link::where([['user_id', '<>', 0]])->orderBy('created_at', 'asc')->firstOrFail();

        if ($this->guard($link)) {
            return view('stats.password', ['link' => $link]);
        }

        $range = $this->range($link->created_at);
        $search = $request->input('search');
        $searchBy = in_array($request->input('search_by'), ['value']) ? $request->input('search_by') : 'value';
        $sortBy = in_array($request->input('sort_by'), ['count', 'value']) ? $request->input('sort_by') : 'count';
        $sort = in_array($request->input('sort'), ['asc', 'desc']) ? $request->input('sort') : 'desc';
        $perPage = in_array($request->input('per_page'), [10, 25, 50, 100]) ? $request->input('per_page') : config('settings.paginate');

        $total = Stat::selectRaw('SUM(`count`) as `count`')
            ->where([['name', '=', 'platform']])
            ->whereBetween('date', [$range['from'], $range['to']])
            ->first();

        $platforms = $this->getPlatforms($range, $search, $searchBy, $sortBy, $sort)
            ->paginate($perPage)
            ->appends(['from' => $range['from'], 'to' => $range['to'], 'search' => $search, 'search_by' => $searchBy, 'sort_by' => $sortBy, 'sort' => $sort]);

        $first = $this->getPlatforms($range, $search, $searchBy, 'count', 'desc')
            ->first();

        $last = $this->getPlatforms($range, $search, $searchBy, 'count', 'asc')
            ->first();

        return view('stats.container', ['view' => 'platforms', 'link' => $link, 'range' => $range, 'export' => 'stats.export.platforms', 'platforms' => $platforms, 'first' => $first, 'last' => $last, 'total' => $total]);
    }

    private function guard($link)
    {
        // If the model is not set to public
        if($link->privacy !== 0) {
            $user = Auth::user();

            // If the model's privacy is set to private
            if ($link->privacy == 1) {
                // If the user is not authenticated
                // Or if the user is not the owner of the link and not an admin
                if ($user == null || $user->id != $link->user_id && $user->role != 1) {
                    abort(403);
                }
            }

            // If the model's privacy is set to password
            if ($link->privacy == 2) {
                // If there's no password validation in the current session
                if (!session(md5($link->id))) {
                    // If the user is not authenticated
                    // Or if the user is not the owner of the link and not an admin
                    if ($user == null || $user->id != $link->user_id && $user->role != 1) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
