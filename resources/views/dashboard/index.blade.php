@extends('layouts.app')

@section('site_title', formatTitle([__('Dashboard'), config('settings.title')]))

@section('content')
<script src="{{ asset('js/app.extras.js') }}" defer></script>
<div class="bg-base-1 flex-fill">
    <div class="bg-base-0">
        <div class="container py-5">
            <div class="d-flex">
                <div class="row no-gutters w-100">
                    <div class="d-flex col-12 col-md">
                        <div class="flex-shrink-1">
                            <a href="{{ route('account') }}" class="d-block"><img src="{{ gravatar(Auth::user()->email, 128) }}" class="rounded-circle width-16 height-16"></a>
                        </div>
                        <div class="flex-grow-1 d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'mr-3' : 'ml-3') }}">
                            <div>
                                <h4 class="font-weight-medium mb-0">{{ Auth::user()->name }}</h4>

                                <div class="d-flex flex-wrap">
                                    @if(paymentProcessors())
                                        <div class="d-inline-block mt-2 {{ (__('lang_dir') == 'rtl' ? 'ml-4' : 'mr-4') }}">
                                            <div class="d-flex">
                                                <div class="d-inline-flex align-items-center">
                                                    @include('icons.package', ['class' => 'text-muted fill-current width-4 height-4'])
                                                </div>

                                                <div class="d-inline-block {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2') }}">
                                                    <a href="{{ route('account.plan') }}" class="text-dark text-decoration-none">{{ Auth::user()->plan->name }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="d-inline-block mt-2 {{ (__('lang_dir') == 'rtl' ? 'ml-4' : 'mr-4') }}">
                                            <div class="d-flex">
                                                <div class="d-inline-flex align-items-center">
                                                    @include('icons.email', ['class' => 'text-muted fill-current width-4 height-4'])
                                                </div>

                                                <div class="d-inline-block {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2') }}">
                                                    {{ Auth::user()->email }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(paymentProcessors())
                        @if(Auth::user()->planIsDefault())
                            <div class="col-12 col-md-auto d-flex flex-row-reverse align-items-center">
                                <a href="{{ route('pricing') }}" class="btn btn-outline-primary btn-block d-flex justify-content-center align-items-center mt-3 mt-md-0 {{ (__('lang_dir') == 'rtl' ? 'ml-md-3' : 'mr-md-3') }}">@include('icons.unarchive', ['class' => 'width-4 height-4 fill-current '.(__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2')]){{ __('Upgrade') }}</a>
                            </div>
                        @else
                            <div class="col-12 col-md-auto d-flex flex-row-reverse align-items-center">
                                <a href="{{ route('pricing') }}" class="btn btn-outline-primary btn-block d-flex justify-content-center align-items-center mt-3 mt-md-0 {{ (__('lang_dir') == 'rtl' ? 'ml-md-3' : 'mr-md-3') }}">@include('icons.package', ['class' => 'width-4 height-4 fill-current '.(__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2')]){{ __('Plans') }}</a>
                            </div>
                        @endif
                    @endif

                    <div class="col-12 col-md-auto d-flex flex-row-reverse align-items-center">
                        <a href="{{ route('links') }}" class="btn btn-primary btn-block d-flex justify-content-center align-items-center mt-3 mt-md-0">@include('icons.add', ['class' => 'width-4 height-4 fill-current '.(__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2')]){{ __('New link') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-base-1">
        <div class="container py-3 my-3">
            <h4 class="mb-3">{{ __('Overview') }}</h4>

            <div class="row m-n2">
                @php
                    $cards = [
                        'users' =>
                        [
                            'title' => 'Links',
                            'value' => $linksCount,
                            'route' => 'links',
                            'icon' => 'link'
                        ],
                        [
                            'title' => 'Spaces',
                            'value' => $stats['spaces'],
                            'route' => 'spaces',
                            'icon' => 'workspaces'
                        ],
                        [
                            'title' => 'Domains',
                            'value' => $stats['domains'],
                            'route' => 'domains',
                            'icon' => 'website'
                        ],
                        [
                            'title' => 'Pixels',
                            'value' => $stats['pixels'],
                            'route' => 'pixels',
                            'icon' => 'filter-center-focus'
                        ]
                    ];
                @endphp

                @foreach($cards as $card)
                    <div class="col-12 col-md-6 col-xl-3 p-2">
                        <div class="card border-0 shadow-sm h-100 overflow-hidden">
                            <div class="card-body d-flex">
                                <div class="d-flex position-relative text-primary width-10 height-10 align-items-center justify-content-center flex-shrink-0">
                                    <div class="position-absolute bg-primary opacity-10 top-0 right-0 bottom-0 left-0 border-radius-xl"></div>
                                    @include('icons.' . $card['icon'], ['class' => 'fill-current width-5 height-5'])
                                </div>

                                <div class="flex-grow-1"></div>

                                <div class="d-flex align-items-center h2 font-weight-bold mb-0 text-truncate">
                                    {{ number_format($card['value'], 0, __('.'), __(',')) }}
                                </div>
                            </div>
                            <div class="card-footer bg-base-2 border-0">
                                <a href="{{ route($card['route']) }}" class="text-muted font-weight-medium d-inline-flex align-items-baseline">{{ __($card['title']) }} @include((__('lang_dir') == 'rtl' ? 'icons.chevron-left' : 'icons.chevron-right'), ['class' => 'width-3 height-3 fill-current '.(__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2')])</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex align-items-end mb-3">
                <div class="col">
                    <h4 class="mb-3 mt-5">{{ __('Analytics') }}</h4>
                </div>
                <div class="col" style="text-align: right;">
                    <a href="#" class="btn border text-secondary" id="date-range-selector">
                        <div class="d-flex align-items-center cursor-pointer">
                            @include('icons.date-range', ['class' => 'fill-current width-4 height-4 flex-shrink-0'])&#8203;

                            <span class="{{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2') }} d-none d-lg-block text-nowrap" id="date-range-value">
                                @if($range['from'] == $range['to'])
                                    @if(\Carbon\Carbon::createFromFormat('Y-m-d', $range['from'])->isToday())
                                        {{ __('Today') }}
                                    @elseif(\Carbon\Carbon::createFromFormat('Y-m-d', $range['from'])->isYesterday())
                                        {{ __('Yesterday') }}
                                    @else
                                        {{ \Carbon\Carbon::createFromFormat('Y-m-d', $range['from'])->format(__('Y-m-d')) }} - {{ \Carbon\Carbon::createFromFormat('Y-m-d', $range['to'])->format(__('Y-m-d')) }}
                                    @endif
                                @else
                                    @if(\Carbon\Carbon::createFromFormat('Y-m-d', $range['from'])->format('Y-m-d') == \Carbon\Carbon::now()->subDays(6)->format('Y-m-d') && \Carbon\Carbon::createFromFormat('Y-m-d', $range['to'])->format('Y-m-d') == \Carbon\Carbon::now()->format('Y-m-d'))
                                        {{ __('Last :days days', ['days' => 7]) }}
                                    @elseif(\Carbon\Carbon::createFromFormat('Y-m-d', $range['from'])->format('Y-m-d') == \Carbon\Carbon::now()->subDays(29)->format('Y-m-d') && \Carbon\Carbon::createFromFormat('Y-m-d', $range['to'])->format('Y-m-d') == \Carbon\Carbon::now()->format('Y-m-d'))
                                        {{ __('Last :days days', ['days' => 30]) }}
                                    @elseif(\Carbon\Carbon::createFromFormat('Y-m-d', $range['from'])->format('Y-m-d') == \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') && \Carbon\Carbon::createFromFormat('Y-m-d', $range['to'])->format('Y-m-d') == \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d'))
                                        {{ __('This month') }}
                                    @elseif(\Carbon\Carbon::createFromFormat('Y-m-d', $range['from'])->format('Y-m-d') == \Carbon\Carbon::now()->subMonthNoOverflow()->startOfMonth()->format('Y-m-d') && \Carbon\Carbon::createFromFormat('Y-m-d', $range['to'])->format('Y-m-d') == \Carbon\Carbon::now()->subMonthNoOverflow()->endOfMonth()->format('Y-m-d'))
                                        {{ __('Last month') }}
                                    @elseif(\Carbon\Carbon::createFromFormat('Y-m-d', $range['from'])->format('Y-m-d') == $first_link_created && \Carbon\Carbon::createFromFormat('Y-m-d', $range['to'])->format('Y-m-d') == \Carbon\Carbon::now()->format('Y-m-d'))
                                        {{ __('All time') }}
                                    @else
                                        {{ \Carbon\Carbon::createFromFormat('Y-m-d', $range['from'])->format(__('Y-m-d')) }} - {{ \Carbon\Carbon::createFromFormat('Y-m-d', $range['to'])->format(__('Y-m-d')) }}
                                    @endif
                                @endif
                            </span>

                            @include('icons.expand-more', ['class' => 'flex-shrink-0 fill-current width-3 height-3 '.(__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2')])
                        </div>
                    </a>

                    <form method="GET" name="date-range" action="{{ route(Route::currentRouteName()) }}">
                        <input name="from" type="hidden">
                        <input name="to" type="hidden">
                    </form>
                </div>
            </div>

            <div id='trend-chart-container' class="px-3 border-bottom">
                <div class="row">
                    <!-- Title -->
                    <div class="col-12 col-md-auto d-none d-xl-flex align-items-center border-bottom border-bottom-md-0 {{ (__('lang_dir') == 'rtl' ? 'border-left-md' : 'border-right-md') }}">
                        <div class="px-2 py-4 d-flex">
                            <div class="d-flex position-relative text-primary width-10 height-10 align-items-center justify-content-center flex-shrink-0">
                                <div class="position-absolute bg-primary opacity-10 top-0 right-0 bottom-0 left-0 border-radius-xl"></div>
                                @include('icons.assesment', ['class' => 'fill-current width-5 height-5'])
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md">
                        <div class="row">
                            <!-- Clicks -->
                            <div class="col-12 col-lg-4 border-bottom border-bottom-lg-0 {{ (__('lang_dir') == 'rtl' ? 'border-left-lg' : 'border-right-lg')  }}">
                                <div class="px-2 py-4">
                                    <div class="d-flex">
                                        <div class="text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                            <div class="d-flex align-items-center text-truncate">
                                                <div class="d-flex align-items-center justify-content-center bg-primary rounded width-4 height-4 flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}" id="clicks-legend"></div>

                                                <div class="flex-grow-1 d-flex font-weight-bold text-truncate">
                                                    <div class="text-truncate">{{ __('Clicks') }}</div>
                                                    <div class="flex-shrink-0 d-flex align-items-center mx-2" data-tooltip="true" title="{{ __('The total number of clicks for the current dataset.') }}">
                                                        @include('icons.info', ['class' => 'width-4 height-4 fill-current text-muted'])
                                                    </div>
                                                </div>
                                            </div>

                                            @include('stats.growth', ['growthCurrent' => $totalClicks, 'growthPrevious' => $totalClicksOld])
                                        </div>

                                        <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'mr-auto' : 'ml-auto') }}">
                                            <div class="h2 font-weight-bold mb-0">{{ number_format($totalClicks, 0, __('.'), __(',')) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Most -->
                            <div class="col-12 col-lg-4 border-bottom border-bottom-lg-0 {{ (__('lang_dir') == 'rtl' ? 'border-left-lg' : 'border-right-lg')  }}">
                                <div class="px-2 py-4">
                                    <div class="row">
                                        <div class="col">
                                            <div class="d-flex align-items-center text-truncate">
                                                @if(max($clicksMap) > 0)
                                                    <div class="flex-grow-1 font-weight-bold text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                                        @if($range['unit'] == 'hour')
                                                            {{ \Carbon\Carbon::createFromFormat('H', array_search(max($clicksMap), $clicksMap))->format('H:i') }}
                                                        @elseif($range['unit'] == 'day')
                                                            {{ \Carbon\Carbon::parse(array_search(max($clicksMap), $clicksMap))->format(__('Y-m-d')) }}
                                                        @elseif($range['unit'] == 'month')
                                                            {{ \Carbon\Carbon::parse(array_search(max($clicksMap), $clicksMap))->format(__('Y-m')) }}
                                                        @else
                                                            {{ array_search(max($clicksMap), $clicksMap) }}
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="flex-grow-1 font-weight-bold text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">{{ __('No data') }}</div>
                                                @endif
                                                <div class="align-self-end">
                                                    @if(max($clicksMap) > 0)
                                                        <div class="d-flex align-items-center justify-content-end {{ (__('lang_dir') == 'rtl' ? 'mr-3' : 'ml-3') }}">
                                                            {{ number_format(max($clicksMap), 0, __('.'), __(',')) }}
                                                        </div>
                                                    @else
                                                        —
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-center text-truncate text-success">
                                                <div class="d-flex align-items-center justify-content-center width-4 height-4 {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">@include('icons.trending-up', ['class' => 'fill-current width-3 height-3'])</div>

                                                <div class="flex-grow-1 text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">{{ mb_strtolower(__('Most popular')) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Least -->
                            <div class="col-12 col-lg-4">
                                <div class="px-2 py-4">
                                    <div class="row">
                                        <div class="col">
                                            <div class="d-flex align-items-center text-truncate">
                                                <div class="flex-grow-1 font-weight-bold text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                                    @if($range['unit'] == 'hour')
                                                        {{ \Carbon\Carbon::createFromFormat('H', array_search(min($clicksMap), $clicksMap))->format('H:i') }}
                                                    @elseif($range['unit'] == 'day')
                                                        {{ \Carbon\Carbon::parse(array_search(min($clicksMap), $clicksMap))->format(__('Y-m-d')) }}
                                                    @elseif($range['unit'] == 'month')
                                                        {{ \Carbon\Carbon::parse(array_search(min($clicksMap), $clicksMap))->format(__('Y-m')) }}
                                                    @else
                                                        {{ array_search(min($clicksMap), $clicksMap) }}
                                                    @endif
                                                </div>
                                                <div class="align-self-end">
                                                    <div class="d-flex align-items-center justify-content-end {{ (__('lang_dir') == 'rtl' ? 'mr-3' : 'ml-3') }}">
                                                        {{ number_format(min($clicksMap), 0, __('.'), __(',')) }}
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-center text-truncate text-danger">
                                                <div class="d-flex align-items-center justify-content-center width-4 height-4 {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">@include('icons.trending-down', ['class' => 'fill-current width-3 height-3'])</div>

                                                <div class="flex-grow-1 text-truncate {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">{{ mb_strtolower(__('Least popular')) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="height: 230px">
                <canvas id="trendChart"></canvas>
            </div>
            <script>
                'use strict';

                window.addEventListener("DOMContentLoaded", function () {
                    Chart.defaults.font = {
                        family: "Inter, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji'",
                        size: 12
                    };

                    const phBgColor = window.getComputedStyle(document.getElementById('trend-chart-container')).getPropertyValue('background-color');
                    const clicksColor = window.getComputedStyle(document.getElementById('clicks-legend')).getPropertyValue('background-color');

                    const ctx = document.querySelector('#trendChart').getContext('2d');
                    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                    gradient.addColorStop(0, clicksColor.replace('rgb', 'rgba').replace(')', ', 0.35)'));
                    gradient.addColorStop(1, clicksColor.replace('rgb', 'rgba').replace(')', ', 0.01)'));

                    let tooltipTitles = [
                        @foreach($clicksMap as $date => $value)
                            @if($range['unit'] == 'hour')
                                '{{ \Carbon\Carbon::createFromFormat('H', $date)->format(__('H:i')) }}',
                            @elseif($range['unit'] == 'day')
                                '{{ \Carbon\Carbon::parse($date)->format(__('Y-m-d')) }}',
                            @elseif($range['unit'] == 'month')
                                '{{ \Carbon\Carbon::parse($date)->format(__('Y-m')) }}',
                            @else
                                '{{ $date }}',
                            @endif
                        @endforeach
                    ];

                    const lineOptions = {
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        hitRadius: 5,
                        pointHoverBorderWidth: 3,
                        lineTension: 0,
                    }

                    let trendChart = new Chart(ctx, {
                        type: 'line',

                        data: {
                            labels: [
                                @foreach($clicksMap as $date => $value)
                                        @if($range['unit'] == 'hour')
                                    '{{ \Carbon\Carbon::createFromFormat('H', $date)->format(__('H:i')) }}',
                                @elseif($range['unit'] == 'day')
                                    '{{ __(':month :day', ['month' => mb_substr(__(\Carbon\Carbon::parse($date)->format('F')), 0, 3), 'day' => __(\Carbon\Carbon::parse($date)->format('j'))]) }}',
                                @elseif($range['unit'] == 'month')
                                    '{{ __(':year :month', ['year' => \Carbon\Carbon::parse($date)->format('Y'), 'month' => mb_substr(__(\Carbon\Carbon::parse($date)->format('F')), 0, 3)]) }}',
                                @else
                                    '{{ $date }}',
                                @endif
                                @endforeach
                            ],
                            datasets: [{
                                label: '{{ __('Clicks') }}',
                                data: [
                                    @foreach($clicksMap as $date => $value)
                                    {{ $value }},
                                    @endforeach
                                ],
                                fill: true,
                                backgroundColor: gradient,
                                borderColor: clicksColor,
                                pointBorderColor: clicksColor,
                                pointBackgroundColor: clicksColor,
                                pointHoverBackgroundColor: phBgColor,
                                pointHoverBorderColor: clicksColor,
                                ...lineOptions
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                mode: 'index',
                                intersect: false
                            },
                            plugins: {
                                legend: {
                                    rtl: {{ (__('lang_dir') == 'rtl' ? 'true' : 'false') }},
                                    display: false
                                },
                                tooltip: {
                                    rtl: {{ (__('lang_dir') == 'rtl' ? 'true' : 'false') }},
                                    mode: 'index',
                                    intersect: false,
                                    reverse: true,

                                    padding: {
                                        top: 14,
                                        right: 16,
                                        bottom: 16,
                                        left: 16
                                    },

                                    backgroundColor: '{{ (config('settings.dark_mode') == 1 ? '#FFF' : '#000') }}',

                                    titleColor: '{{ (config('settings.dark_mode') == 1 ? '#000' : '#FFF') }}',
                                    titleMarginBottom: 7,
                                    titleFont: {
                                        size: 16,
                                        weight: 'normal'
                                    },

                                    bodyColor: '{{ (config('settings.dark_mode') == 1 ? '#000' : '#FFF') }}',
                                    bodySpacing: 7,
                                    bodyFont: {
                                        size: 14
                                    },

                                    footerMarginTop: 10,
                                    footerFont: {
                                        size: 12,
                                        weight: 'normal'
                                    },

                                    cornerRadius: 4,
                                    caretSize: 7,

                                    boxPadding: 4,

                                    callbacks: {
                                        label: function (tooltipItem) {
                                            return ' ' + tooltipItem.dataset.label + ': ' + parseFloat(tooltipItem.dataset.data[tooltipItem.dataIndex]).format(0, 3, '{{ __(',') }}').toString();
                                        },
                                        title: function (tooltipItem) {
                                            return tooltipTitles[tooltipItem[0].dataIndex];
                                        }
                                    }
                                },
                            },
                            scales: {
                                x: {
                                    display: true,
                                    grid: {
                                        lineWidth: 0,
                                        tickLength: 0
                                    },
                                    ticks: {
                                        maxTicksLimit: @if($range['unit'] == 'day') 12 @else 15 @endif,
                                        padding: 10,
                                    }
                                },
                                y: {
                                    display: true,
                                    beginAtZero: true,
                                    grid: {
                                        tickLength: 0
                                    },
                                    ticks: {
                                        maxTicksLimit: 8,
                                        padding: 10,
                                        callback: function (value) {
                                            return commarize(value, 1000);
                                        }
                                    }
                                },
                            }
                        }
                    });

                    // The time to wait before attempting to change the colors on first attempt
                    let colorSchemeTimer = 500;

                    // Update the chart colors when the color scheme changes
                    const observer = new MutationObserver(function (mutationsList, observer) {
                        for (const mutation of mutationsList) {
                            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                                setTimeout(function () {
                                    const phBgColor = window.getComputedStyle(document.getElementById('trend-chart-container')).getPropertyValue('background-color');
                                    const clicksColor = window.getComputedStyle(document.getElementById('clicks-legend')).getPropertyValue('background-color');

                                    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                                    gradient.addColorStop(0, clicksColor.replace('rgb', 'rgba').replace(')', ', 0.35)'));
                                    gradient.addColorStop(1, clicksColor.replace('rgb', 'rgba').replace(')', ', 0.01)'));

                                    trendChart.data.datasets[0].backgroundColor = gradient;
                                    trendChart.data.datasets[0].borderColor = clicksColor;
                                    trendChart.data.datasets[0].pointBorderColor = clicksColor;
                                    trendChart.data.datasets[0].pointBackgroundColor = clicksColor;
                                    trendChart.data.datasets[0].pointHoverBackgroundColor = phBgColor;
                                    trendChart.data.datasets[0].pointHoverBorderColor = clicksColor;

                                    trendChart.options.plugins.tooltip.backgroundColor = (document.querySelector('html').classList.contains('dark') == 0 ? '#000' : '#FFF');
                                    trendChart.options.plugins.tooltip.titleColor = (document.querySelector('html').classList.contains('dark') == 0 ? '#FFF' : '#000');
                                    trendChart.options.plugins.tooltip.bodyColor = (document.querySelector('html').classList.contains('dark') == 0 ? '#FFF' : '#000');
                                    trendChart.update();

                                    // Update the color scheme timer to be faster next time it's used
                                    colorSchemeTimer = 100;
                                }, colorSchemeTimer);
                            }
                        }
                    });

                    observer.observe(document.querySelector('html'), { attributes: true });

                    // change date range picker------------------------------------------
                    document.querySelector('#date-range-selector') && document.querySelector('#date-range-selector').addEventListener('click', function (e) {
                        console.log('222');
                        e.preventDefault();
                    });

                    jQuery('#date-range-selector').daterangepicker({
                        @php
                            $utcOffset = \Carbon\Carbon::now()->utcOffset();
                        @endphp

                        ranges: {
                            "{{ __('Today') }}": [moment().utcOffset({{ $utcOffset }}), moment().utcOffset({{ $utcOffset }})],
                            "{{ __('Yesterday') }}": [moment().utcOffset({{ $utcOffset }}).subtract(1, 'days'), moment().utcOffset({{ $utcOffset }}).subtract(1, 'days')],
                            "{{ __('Last :days days', ['days' => 7]) }}": [moment().utcOffset({{ $utcOffset }}).subtract(6, 'days'), moment().utcOffset({{ $utcOffset }})],
                            "{{ __('Last :days days', ['days' => 30]) }}": [moment().utcOffset({{ $utcOffset }}).subtract(29, 'days'), moment().utcOffset({{ $utcOffset }})],
                            "{{ __('This month') }}": [moment().utcOffset({{ $utcOffset }}).startOf('month'), moment().utcOffset({{ $utcOffset }}).endOf('month')],
                            "{{ __('Last month') }}": [moment().utcOffset({{ $utcOffset }}).subtract(1, 'month').startOf('month'), moment().utcOffset({{ $utcOffset }}).subtract(1, 'month').endOf('month')],
                            "{{ __('All time') }}": [moment('{{ date('Y-m-d', strtotime($first_link_created)) }}'), moment().utcOffset({{ $utcOffset }})]
                        },
                        locale: {
                            direction: "{{ (__('lang_dir') == 'rtl' ? 'rtl' : 'ltr') }}",
                            format: "{{ str_ireplace(['y', 'm', 'd'], ['YYYY', 'MM', 'DD'], __('Y-m-d')) }}",
                            separator: " - ",
                            applyLabel: "{{ __('Apply') }}",
                            cancelLabel: "{{ __('Cancel') }}",
                            customRangeLabel: "{{ __('Custom') }}",
                            daysOfWeek: [
                                "{{ __('Su') }}",
                                "{{ __('Mo') }}",
                                "{{ __('Tu') }}",
                                "{{ __('We') }}",
                                "{{ __('Th') }}",
                                "{{ __('Fr') }}",
                                "{{ __('Sa') }}"
                            ],
                            monthNames: [
                                "{{ __('January') }}",
                                "{{ __('February') }}",
                                "{{ __('March') }}",
                                "{{ __('April') }}",
                                "{{ __('May') }}",
                                "{{ __('June') }}",
                                "{{ __('July') }}",
                                "{{ __('August') }}",
                                "{{ __('September') }}",
                                "{{ __('October') }}",
                                "{{ __('November') }}",
                                "{{ __('December') }}"
                            ]
                        },
                        startDate : "{{ \Carbon\Carbon::createFromFormat('Y-m-d', $range['from'])->format(__('Y-m-d')) }}",
                        endDate : "{{ \Carbon\Carbon::createFromFormat('Y-m-d', $range['to'])->format(__('Y-m-d')) }}",
                        opens: "{{ (__('lang_dir') == 'rtl' ? 'right' : 'left') }}",
                        applyClass: "btn-primary",
                        cancelClass: "btn-secondary",
                        linkedCalendars: false,
                        alwaysShowCalendars: true
                    });

                    jQuery('#date-range-selector').on('apply.daterangepicker', function (ev, picker) {
                        document.querySelector('input[name="from"]').value = picker.startDate.format('YYYY-MM-DD');
                        document.querySelector('input[name="to"]').value = picker.endDate.format('YYYY-MM-DD');

                        document.querySelector('form[name="date-range"]').submit();
                    });

                    jQuery('#date-range-selector').on('hide.daterangepicker', function (ev, picker) {
                        document.querySelector('#date-range-selector').classList.remove('active');
                    });

                    jQuery('#date-range-selector').on('show.daterangepicker', function (ev, picker) {
                        console.log('aaa');
                        document.querySelector('#date-range-selector').classList.add('active');
                    });
                    // ------------------------------------------------------------------------------
                });
            </script>

            <div class="row m-n2">
                <div class="col-12 col-lg-6 p-2">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-md"><div class="font-weight-medium py-1">{{ __('Referrers') }}</div></div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($referrers) == 0)
                                {{ __('No data') }}.
                            @else
                                <div class="list-group list-group-flush my-n3">
                                    <div class="list-group-item px-0 text-muted">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                {{ __('Website') }}
                                            </div>
                                            <div class="col-auto">
                                                {{ __('Clicks') }}
                                            </div>
                                        </div>
                                    </div>

                                    @foreach($referrers as $referrer)
                                        <div class="list-group-item px-0 border-0">
                                            <div class="d-flex flex-column">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <div class="d-flex text-truncate align-items-center">
                                                        @if($referrer->value)
                                                            <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                                                <img src="https://icons.duckduckgo.com/ip3/{{ $referrer->value }}.ico" rel="noreferrer" class="width-4 height-4">
                                                            </div>

                                                            <div class="d-flex text-truncate">
                                                                <div class="text-truncate" dir="ltr">{{ $referrer->value }}</div> <a href="http://{{ $referrer->value }}" target="_blank" rel="nofollow noreferrer noopener" class="text-secondary d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2') }}">@include('icons.open-in-new', ['class' => 'fill-current width-3 height-3'])</a>
                                                            </div>
                                                        @else
                                                            <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                                                <img src="{{ asset('/images/icons/referrers/unknown.svg') }}" rel="noreferrer" class="width-4 height-4">
                                                            </div>

                                                            <div class="text-truncate">
                                                                {{ __('Direct, Email, SMS') }}
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <div class="d-flex align-items-baseline {{ (__('lang_dir') == 'rtl' ? 'mr-3 text-left' : 'ml-3 text-right') }}">
                                                        <div>
                                                            {{ number_format($referrer->count, 0, __('.'), __(',')) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="progress height-1.25 w-100">
                                                    <div class="progress-bar rounded" role="progressbar" style="width: {{ (($referrer->count / $totalReferrers) * 100) }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- @if(count($referrers) > 0)
                            <div class="card-footer bg-base-2 border-0">
                                <a href="{{ route('stats.all_referrers', ['from' => $range['from'], 'to' => $range['to']]) }}" class="text-muted font-weight-medium d-flex align-items-center justify-content-center">{{ __('View all') }} @include((__('lang_dir') == 'rtl' ? 'icons.chevron-left' : 'icons.chevron-right'), ['class' => 'width-3 height-3 fill-current '.(__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2')])</a>
                            </div>
                        @endif --}}
                    </div>
                </div>

                <div class="col-12 col-lg-6 p-2">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-md"><div class="font-weight-medium py-1">{{ __('Countries') }}</div></div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($countries) == 0)
                                {{ __('No data') }}.
                            @else
                                <div class="list-group list-group-flush my-n3">
                                    <div class="list-group-item px-0 text-muted">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                {{ __('Name') }}
                                            </div>
                                            <div class="col-auto">
                                                {{ __('Clicks') }}
                                            </div>
                                        </div>
                                    </div>

                                    @foreach($countries as $country)
                                        <div class="list-group-item px-0 border-0">
                                            <div class="d-flex flex-column">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <div class="d-flex text-truncate align-items-center">
                                                        <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}"><img src="{{ asset('/images/icons/countries/'. formatFlag($country->value)) }}.svg" class="width-4 height-4"></div>
                                                        <div class="text-truncate">
                                                            @if(!empty(explode(':', $country->value)[1]))
                                                                <a href="{{ route('stats.all_cities', ['search' => explode(':', $country->value)[0].':', 'from' => $range['from'], 'to' => $range['to']]) }}" class="text-body" data-tooltip="true" title="{{ __(explode(':', $country->value)[1]) }}">{{ explode(':', $country->value)[1] }}</a>
                                                            @else
                                                                {{ __('Unknown') }}
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="d-flex align-items-baseline {{ (__('lang_dir') == 'rtl' ? 'mr-3 text-left' : 'ml-3 text-right') }}">
                                                        <div>
                                                            {{ number_format($country->count, 0, __('.'), __(',')) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="progress height-1.25 w-100">
                                                    <div class="progress-bar rounded" role="progressbar" style="width: {{ (($country->count / $totalClicks) * 100) }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- @if(count($countries) > 0)
                            <div class="card-footer bg-base-2 border-0">
                                <a href="{{ route('stats.all_countries', ['from' => $range['from'], 'to' => $range['to']]) }}" class="text-muted font-weight-medium d-flex align-items-center justify-content-center">{{ __('View all') }} @include((__('lang_dir') == 'rtl' ? 'icons.chevron-left' : 'icons.chevron-right'), ['class' => 'width-3 height-3 fill-current '.(__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2')])</a>
                            </div>
                        @endif --}}
                    </div>
                </div>

                <div class="col-12 col-lg-6 p-2">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-md"><div class="font-weight-medium py-1">{{ __('Browsers') }}</div></div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($browsers) == 0)
                                {{ __('No data') }}.
                            @else
                                <div class="list-group list-group-flush my-n3">
                                    <div class="list-group-item px-0 text-muted">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                {{ __('Name') }}
                                            </div>
                                            <div class="col-auto">
                                                {{ __('Clicks') }}
                                            </div>
                                        </div>
                                    </div>

                                    @foreach($browsers as $browser)
                                        <div class="list-group-item px-0 border-0">
                                            <div class="d-flex flex-column">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <div class="d-flex text-truncate align-items-center">
                                                        <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}"><img src="{{ asset('/images/icons/browsers/'.formatBrowser($browser->value)) }}.svg" class="width-4 height-4"></div>
                                                        <div class="text-truncate">
                                                            @if($browser->value)
                                                                {{ $browser->value }}
                                                            @else
                                                                {{ __('Unknown') }}
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="d-flex align-items-baseline {{ (__('lang_dir') == 'rtl' ? 'mr-3 text-left' : 'ml-3 text-right') }}">
                                                        <div>
                                                            {{ number_format($browser->count, 0, __('.'), __(',')) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="progress height-1.25 w-100">
                                                    <div class="progress-bar rounded" role="progressbar" style="width: {{ (($browser->count / $totalClicks) * 100) }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- @if(count($browsers) > 0)
                            <div class="card-footer bg-base-2 border-0">
                                <a href="{{ route('stats.all_browsers', ['from' => $range['from'], 'to' => $range['to']]) }}" class="text-muted font-weight-medium d-flex align-items-center justify-content-center">{{ __('View all') }} @include((__('lang_dir') == 'rtl' ? 'icons.chevron-left' : 'icons.chevron-right'), ['class' => 'width-3 height-3 fill-current '.(__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2')])</a>
                            </div>
                        @endif --}}
                    </div>
                </div>

                <div class="col-12 col-lg-6 p-2">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12 col-md"><div class="font-weight-medium py-1">{{ __('Platforms') }}</div></div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(count($platforms) == 0)
                                {{ __('No data') }}.
                            @else
                                <div class="list-group list-group-flush my-n3">
                                    <div class="list-group-item px-0 text-muted">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                {{ __('Name') }}
                                            </div>
                                            <div class="col-auto">
                                                {{ __('Clicks') }}
                                            </div>
                                        </div>
                                    </div>

                                    @foreach($platforms as $platform)
                                        <div class="list-group-item px-0 border-0">
                                            <div class="d-flex flex-column">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <div class="d-flex text-truncate align-items-center">
                                                        <div class="d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}"><img src="{{ asset('/images/icons/platforms/'.formatPlatform($platform->value)) }}.svg" class="width-4 height-4"></div>
                                                        <div class="text-truncate">
                                                            @if($platform->value)
                                                                {{ $platform->value }}
                                                            @else
                                                                {{ __('Unknown') }}
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="d-flex align-items-baseline {{ (__('lang_dir') == 'rtl' ? 'mr-3 text-left' : 'ml-3 text-right') }}">
                                                        <div>
                                                            {{ number_format($platform->count, 0, __('.'), __(',')) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="progress height-1.25 w-100">
                                                    <div class="progress-bar rounded" role="progressbar" style="width: {{ (($platform->count / $totalClicks) * 100) }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        {{-- @if(count($platforms) > 0)
                            <div class="card-footer bg-base-2 border-0">
                                <a href="{{ route('stats.all_platforms', ['from' => $range['from'], 'to' => $range['to']]) }}" class="text-muted font-weight-medium d-flex align-items-center justify-content-center">{{ __('View all') }} @include((__('lang_dir') == 'rtl' ? 'icons.chevron-left' : 'icons.chevron-right'), ['class' => 'width-3 height-3 fill-current '.(__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2')])</a>
                            </div>
                        @endif --}}
                    </div>
                </div>
            </div>

            <h4 class="mb-3 mt-5">{{ __('Activity') }}</h4>

            <div class="row m-n2">
                <div class="col-12 col-lg-6 p-2">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header align-items-center">
                            <div class="row">
                                <div class="col"><div class="font-weight-medium py-1">{{ __('Latest links') }}</div></div>
                            </div>
                        </div>

                        <div class="card-body">
                            @if(count($latestLinks) == 0)
                                {{ __('No data') }}.
                            @else
                                <div class="list-group list-group-flush my-n3">
                                    @foreach($latestLinks as $link)
                                        <div class="list-group-item px-0">
                                            <div class="row align-items-center">
                                                <div class="col d-flex text-truncate">
                                                    <div class="text-truncate">
                                                        <div class="d-flex align-items-center">
                                                            <img src="https://icons.duckduckgo.com/ip3/{{ parse_url($link->url)['host'] }}.ico" rel="noreferrer" class="width-4 height-4 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">

                                                            <div class="text-truncate">
                                                                <a href="{{ route('stats.overview', $link->id) }}" class="{{ ($link->disabled || $link->expiration_clicks && $link->clicks >= $link->expiration_clicks || \Carbon\Carbon::now()->greaterThan($link->ends_at) && $link->ends_at ? 'text-danger' : '') }}" dir="ltr">{{ str_replace(['http://', 'https://'], '', ($link->domain->url ?? config('app.url'))) .'/'.$link->alias }}</a>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center">
                                                            <div class="width-4 flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}"></div>
                                                            <div class="text-muted text-truncate small cursor-help" data-toggle="tooltip-url" title="{{ $link->url }}">
                                                                @if($link->title){{ $link->title }}@else<span dir="ltr">{{ str_replace(['http://', 'https://'], '', $link->url) }}</span>@endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="form-row">
                                                        <div class="col">
                                                            @include('shared.buttons.copy-link', ['class' => 'btn-sm text-primary'])
                                                        </div>
                                                        <div class="col">
                                                            @include('links.partials.menu')
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        @if(count($latestLinks) > 0)
                            <div class="card-footer bg-base-2 border-0">
                                <a href="{{ route('links') }}" class="text-muted font-weight-medium d-flex align-items-center justify-content-center">{{ __('View all') }} @include((__('lang_dir') == 'rtl' ? 'icons.chevron-left' : 'icons.chevron-right'), ['class' => 'width-3 height-3 fill-current '.(__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2')])</a>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-lg-6 p-2">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header align-items-center">
                            <div class="row">
                                <div class="col"><div class="font-weight-medium py-1">{{ __('Popular links') }}</div></div>
                            </div>
                        </div>

                        <div class="card-body">
                            @if(count($popularLinks) == 0)
                                {{ __('No data') }}.
                            @else
                                <div class="list-group list-group-flush my-n3">
                                    @foreach($popularLinks as $link)
                                        <div class="list-group-item px-0">
                                            <div class="row align-items-center">
                                                <div class="col d-flex text-truncate">
                                                    <div class="text-truncate">
                                                        <div class="d-flex align-items-center">
                                                            <img src="https://icons.duckduckgo.com/ip3/{{ parse_url($link->url)['host'] }}.ico" rel="noreferrer" class="width-4 height-4 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">

                                                            <div class="text-truncate">
                                                                <a href="{{ route('stats.overview', $link->id) }}" class="{{ ($link->disabled || $link->expiration_clicks && $link->clicks >= $link->expiration_clicks || \Carbon\Carbon::now()->greaterThan($link->ends_at) && $link->ends_at ? 'text-danger' : '') }}" dir="ltr">{{ str_replace(['http://', 'https://'], '', ($link->domain->url ?? config('app.url'))) .'/'.$link->alias }}</a>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center">
                                                            <div class="width-4 flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}"></div>
                                                            <div class="text-muted text-truncate small cursor-help" data-toggle="tooltip-url" title="{{ $link->url }}">
                                                                @if($link->title){{ $link->title }}@else<span dir="ltr">{{ str_replace(['http://', 'https://'], '', $link->url) }}</span>@endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <div class="form-row">
                                                        <div class="col">
                                                            <a href="{{ route('stats.overview', $link->id) }}" class="btn btn-sm text-primary d-flex align-items-center" data-tooltip="true" title="{{ __('Stats') }}">
                                                                @include('icons.bar-chart', ['class' => 'fill-current width-4 height-4'])&#8203;
                                                            </a>
                                                        </div>
                                                        <div class="col">
                                                            @include('links.partials.menu')
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        @if(count($popularLinks) > 0)
                            <div class="card-footer bg-base-2 border-0">
                                <a href="{{ route('links', ['sort' => 'max']) }}" class="text-muted font-weight-medium d-flex align-items-center justify-content-center">{{ __('View all') }} @include((__('lang_dir') == 'rtl' ? 'icons.chevron-left' : 'icons.chevron-right'), ['class' => 'width-3 height-3 fill-current '.(__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2')])</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <h4 class="mb-3 mt-5">{{ __('More') }}</h4>

            <div class="row m-n2">
                <div class="col-12 col-xl-4 p-2">
                    <div class="card border-0 h-100 shadow-sm">
                        <div class="card-body d-flex">
                            <div class="d-flex position-relative text-primary width-12 height-12 align-items-center justify-content-center flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">
                                <div class="position-absolute bg-primary opacity-10 top-0 right-0 bottom-0 left-0 border-radius-2xl"></div>
                                @include('icons.workspaces', ['class' => 'fill-current width-6 height-6'])
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                                <a href="{{ route('spaces.new') }}" class="text-dark font-weight-medium text-decoration-none stretched-link">{{ __('Space') }}</a>

                                <div class="text-muted">
                                    {{ __('Create a new space.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-4 p-2">
                    <div class="card border-0 h-100 shadow-sm">
                        <div class="card-body d-flex">
                            <div class="d-flex position-relative text-primary width-12 height-12 align-items-center justify-content-center flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">
                                <div class="position-absolute bg-primary opacity-10 top-0 right-0 bottom-0 left-0 border-radius-2xl"></div>
                                @include('icons.website', ['class' => 'fill-current width-6 height-6'])
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                                <a href="{{ route('domains.new') }}" class="text-dark font-weight-medium text-decoration-none stretched-link">{{ __('Domain') }}</a>

                                <div class="text-muted">
                                    {{ __('Add a new domain.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-xl-4 p-2">
                    <div class="card border-0 h-100 shadow-sm">
                        <div class="card-body d-flex">
                            <div class="d-flex position-relative text-primary width-12 height-12 align-items-center justify-content-center flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-3' : 'mr-3') }}">
                                <div class="position-absolute bg-primary opacity-10 top-0 right-0 bottom-0 left-0 border-radius-2xl"></div>
                                @include('icons.filter-center-focus', ['class' => 'fill-current width-6 height-6'])
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                                <a href="{{ route('pixels.new') }}" class="text-dark font-weight-medium text-decoration-none stretched-link">{{ __('Pixel') }}</a>

                                <div class="text-muted">
                                    {{ __('Integrate a new pixel.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('shared.modals.share-link')
@endsection

@include('shared.sidebars.user')
