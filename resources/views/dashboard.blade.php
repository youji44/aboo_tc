@extends('layouts.layout')

{{-- Page title --}}
@section('title')
    Welcome
@stop

{{-- page level styles --}}
@section('header_styles')
<style>
    .badge-left {
        min-width: 20px;
        border-radius: 50rem;
        display: inline-block;
        padding: .25em .4em .25em .4em;
        font-size: 90%;
        font-weight: 700;
        line-height: 1;
        text-align: center;
    }
    .colx-3 {
        width: 20%;
        float: left;
    }
    .colx-3 {
        position: relative;
        min-height: 1px;
        padding-right: 15px;
        padding-left: 15px;
    }
    @media screen and (max-width: 768px) {
        .colx-3 {
            width: 100%;
        }
    }
</style>
@stop

{{-- Page content --}}
@section('content')

<div class="header-area">
    <div class="row align-items-center">
        <!-- nav and search button -->
        <div class="col-md-12 col-sm-12 clearfix">
            <div class="nav-btn pull-left">
                <span></span>
                <span></span>
                <span></span>
            </div>
            <div class="search-box pull-left">
                <div class="page-title-area">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="breadcrumbs-area clearfix">
                                <h4 class="page-title pull-left">{{\Session::get('p_loc_name')}} > Dashboard </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="sales-report-area mt-5 mb-5">
    @include('notifications')
    <div class="row">
        <div class="col-6">
            <div class="single-report mb-xs-30">
                <div class="s-report-inner pr--20 pt--30 mb-3">
                    <div class="icon"><i class="fa calendar-check-o fa-calendar-check-o"></i></div>
                    <div class="s-report-title d-flex justify-content-between">
                        <h4 class="header-title mb-0">Incident Reports</h4>
                    </div>
                    <div class="d-flex justify-content-between pb-2">
                        <h2>{{$incident_count}}</h2>
                        <p>Pending</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="single-report mb-xs-30">
                <div class="s-report-inner pr--20 pt--30 mb-3">
                    <div class="icon"><i class="fa calendar-check-o fa-calendar-check-o"></i></div>
                    <div class="s-report-title d-flex justify-content-between">
                        <h4 class="header-title mb-0">Internal Audit</h4>
                    </div>
                    <div class="d-flex justify-content-between pb-2">
                        <h2>{{ $audit_count }}</h2>
                        <p>Pending</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @if(\Sentinel::inRole('admin') || \Sentinel::inRole('superadmin') || \Sentinel::inRole('supervisor'))
        <div class="col-md-4 mt-3">
            <div class="card p-2" style="width: 100%">
                <canvas id="last7" height="250"></canvas>
            </div>
        </div>
        <div class="col-md-8 mt-3">
            <div id="stats-body" class="card p-2">
                <div class="row">
                    <div class="col-md-6">
                        <h4 class="header-title mb-0">Reporting Statistics</h4>
                        <table id="statsTable" class="table table-hover text-center align-middle"  style="font-size:small;">
                            <thead class="text-uppercase">
                            <tr class="bg-light">
                                <th scope="col">STAFF</th>
                                <th scope="col">Yesterday</th>
                                <th scope="col">Today</th>
                                <th scope="col">Percent</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $record as $item)
                                <tr style="background-color: {{$item['color']}}">
                                    <td class="no-sort">{{$item['user']}}</td>
                                    <td class="no-sort">{{$item['yesterday']}}</td>
                                    <td class="no-sort">{{$item['today']}}</td>
                                    <td>{{$item['percent']}}%</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6" style="width: 100%;">
                        <canvas id="user_pie" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="remain_inspects">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="title_inspects" class="modal-title">Modal Title</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div id="inspections" class="modal-body" style="min-height: 240px">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')

    <script>
        $(document).ready(function(){
            let h = $('#last7').height()-72;
            $('#statsTable').DataTable({
                searching: false,
                paging: false,
                info: false,
                scrollY: h,
                scrollCollapse: true,
                fixedHeader:{
                    header: true,
                    footer: true
                },
                order: [[3, 'desc']],
                columnDefs: [ {
                    "targets": [0,1,2],
                    "orderable": false,
                }]

            });
            $('#monthly-assign').DataTable({
                searching: false,
                paging: false,
                info: false,
            });
            $('#quarterly-assign').DataTable({
                searching: false,
                paging: false,
                info: false
            });
            $('#weekly-assign').DataTable({
                searching: false,
                paging: false,
                info: false
            });
            $('.dataTables_scrollBody').slimScroll({
                height: '100%'
            });
        });

        function show_inspections(url, title) {
            $.get(url, function (data,status) {
                $("#title_inspects").html(title);
                $("#inspections").html(data);
                $("#remain_inspects").modal('show');
            })
        }

        if ($('#last7').length) {
            var ctx = document.getElementById("last7").getContext('2d');
            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: JSON.parse('{!! json_encode($last7) !!}'),
                    datasets: [{
                        label: "Reports",
                        data: JSON.parse('{!! json_encode($last7_daily) !!}'),
                        backgroundColor: '{{\Session::get('p_loc_color')}}',
                        borderRadius: 5,
                        borderSkipped: false,
                    }]
                },
                // Configuration options go here
                options: {
                    title: {
                        display: true,
                        text: 'Last 7 Days Total Daily Reports',
                        alignment:'left'
                    },
                    legend: {
                        display: false
                    },
                    animation: {
                        easing: "easeInOutBack"
                    },
                    responsive:true,
                    scales: {
                        yAxes: [{
                            display: 1,
                            ticks: {
                                fontColor: "#bfbfbf",
                                beginAtZero: !0,
                                padding: 0
                            },
                            gridLines: {
                                zeroLineColor: "transparent"
                            }
                        }],
                        xAxes: [{
                            display: true,
                            gridLines: {
                                zeroLineColor: "transparent",
                                display: false
                            },
                            ticks: {
                                beginAtZero: true,
                                padding: 0,
                                fontColor: "#b6b6b6",
                                fontSize:10
                            },
                            barPercentage: 0.3
                        }]
                    }
                }
            });
        }
        if ($('#user_pie').length) {
            let user_array = [];
            const data = JSON.parse('{!! json_encode($record) !!}');
            data.forEach(function (item, key) {
                user_array.push(item.user);
            });
            var ctx = document.getElementById("user_pie").getContext('2d');
            var pie_chart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: user_array,
                    datasets: [{
                        label: "Reports",
                        data: JSON.parse('{!! json_encode($percent) !!}'),
                        backgroundColor: JSON.parse('{!! json_encode($pie_color) !!}'),
                        borderRadius: 5,
                        borderSkipped: false,
                    }]
                },
                // Configuration options go here
                options: {
                    title: {
                        display: true,
                        text: 'Reporting by Users',
                        alignment:'center'
                    },
                    legend: {
                        display: false
                    },
                    animation: {
                        easing: "easeInOutBack"
                    },
                    responsive:true,
                    plugins: {
                        datalabels: {
                            formatter: (value, ctx) => {
                                let sum = 0;
                                let dataArr = ctx.chart.data.datasets[0].data;
                                dataArr.map(data => {
                                    sum += data;
                                });
                                return (value*100 / sum).toFixed(2)+"%";
                            },
                            color: '#fff',
                        }
                    }
                }
            });
        }

    </script>
@stop
