@extends('layouts.layout')

{{-- Page title --}}
@section('title')
    Incident Report
@stop
{{-- page level styles --}}
@section('header_styles')
    <style>
        .col-form-label-sm{
            padding-bottom: 0;
            margin-bottom: 0;
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
                                    <h4 class="page-title pull-left">{{\Session::get('p_loc_name')}} > Incident Report</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <ul class="nav nav-tabs mt-3" id="myTab" role="tablist">
        <li class="nav-item active">
            <a class="nav-link active alert-info" id="inspection-tab" data-toggle="tab" href="#inspection" role="tab" aria-controls="inspection-tab" aria-selected="true">Preliminary Report
                @if(count($incident_reporting) > 0) <span class="badge badge-danger ml-1">{{count($incident_reporting)}}</span> @endif
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link alert-secondary" id="detail_report-tab" data-toggle="tab" href="#detail_report" role="tab" aria-controls="detail_report-tab" aria-selected="true">Detailed Reports</a>
        </li>
        <li class="nav-item">
            <a class="nav-link alert-secondary" id="summary-tab" data-toggle="tab" href="#summary" role="tab" aria-controls="summary-tab" aria-selected="true">Summary</a>
        </li>
    </ul>
    <div class="tab-content mt-3" id="myTabContent">
        <div class="tab-pane active" id="inspection" role="tabpanel" aria-labelledby="inspection-tab">
            <div class="row">
                <div class="col-xl mt-2">
                    <a class="btn btn-success btn-sm" href="{{ route('incident.reporting.add','0') }}"><i class="ti-plus"></i> Add New</a>
                </div>
            </div>
            <div class="row">
                <div class="col-xl mt-2">
                    <div class="card">
                        <div class="card-body">
                            @include('notifications')
                            <div class="text-success">Total: {{count($incident_reporting)}}</div>
                            <div class="single-table">
                                <div class="table-responsive">
                                    <table id="inspectDataTable" class="table table-hover progress-table text-center table-bordered align-middle"  style="font-size:small;">
                                        <thead class="text-uppercase">
                                        <tr class="bg-light">
                                            <th scope="col">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" name="check" class="custom-control-input" id="checkAll1">
                                                    <label class="custom-control-label" for="checkAll1"></label>
                                                </div>
                                            </th>
                                            <th scope="col">#</th>
                                            <th scope="col">DATE</th>
                                            <th scope="col">TIME</th>
                                            <th scope="col">LOCATION CODE</th>
                                            <th scope="col">DEPARTMENT</th>
                                            <th scope="col">DESCRIPTION</th>
                                            <th scope="col">TYPE</th>
                                            <th scope="col">REPORT COMPLETED BY</th>
                                            <th scope="col">TOTAL COMMENTS</th>
                                            <th scope="col">STATUS</th>
                                            <th scope="col">ACTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($incident_reporting as $key=>$item)
                                            <tr>
                                                <td><div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input checked_inspection" id="check_{{$item->id}}">
                                                        <label class="custom-control-label" for="check_{{$item->id}}"> </label>
                                                    </div></td>
                                                <td>{{ $key+1 }}</td>
                                                <td>{{ date('Y-m-d',strtotime($item->date))}}</td>
                                                <td>{{ date('H:i',strtotime($item->time))}}</td>
                                                <td>{{$item->location_code}}</td>
                                                <td>{{$item->department_name}}</td>
                                                <td>{{$item->incident_title}}</td>
                                                <td>{{$item->type}}</td>
                                                <td>{{ $item->user_name }}</td>
                                                <td><button data-tip="tooltip" title="View comments" class="btn btn-warning btn-sm" onclick="show_comments('{{route('incident.reporting.comments', $item->id)}}?mode=view')">{{ $item->comments_count }}</button></td>
                                                <td>@if($item->status == '0')
                                                        <span class="status-p bg-warning">Pending</span>
                                                    @else
                                                        <span class="status-p bg-success">Checked</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button data-tip="tooltip" title="Show" data-placement="top" onclick="show_detail('{{ route('incident.reporting.detail',$item->id) }}')" type="button" class="btn btn-{{$item->images==''?'outline-':''}}warning btn-sm"><i class="ti-search"></i></button>
                                                    <button class="btn btn-outline-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"><i class="ti-menu"></i></button>
                                                    <div class="dropdown-menu p-2">
                                                        <button data-tip="tooltip" title="Add comments" class="btn btn-primary btn-sm" onclick="show_comments('{{route('incident.reporting.comments', $item->id)}}?mode=add')"><i class="ti-plus"></i></button>
                                                        <button data-tip="tooltip" title="PDF" data-placement="top" onclick="show_print('{{route('incident.reporting.print',$item->id)}}')" class="btn btn-success btn-sm"><i class="ti-cloud-down"></i></button>
                                                        <a data-tip="tooltip" title="Edit" data-placement="top" href="{{ route('incident.reporting.add',$item->id) }}" type="button" class="btn btn-info btn-sm"><i class="ti-pencil-alt"></i></a>
                                                        @if(\Sentinel::inRole('admin') || \Sentinel::inRole('superadmin') || \Sentinel::inRole('supervisor'))
                                                            <button data-tip="tooltip" title="Check" data-placement="top" onclick="show_approve('{{route('incident.reporting.check.edit',$item->id)}}')" type="button" class="btn btn-success btn-sm"><i class="ti-check-box"></i></button>
                                                            <button data-tip="tooltip" title="Delete" data-placement="left" onclick="delete_item(this,'{{$item->id}}','{{route('incident.reporting.delete')}}')" data-target="#delete_form" type="button" class="btn btn-danger btn-sm"><i class="ti-trash"></i></button>
                                                            <form id="form_{{$item->id}}" hidden action="{{route('incident.reporting.delete')}}" method="post">
                                                                @csrf <input title="" hidden name="id" value="{{$item->id}}">
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                            @include('layouts.script')
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="detail_report" role="tabpanel" aria-labelledby="detail_report-tab">
            <div class="row">
                <div class="col-xl mt-2">
                    <form id="form_month" class="form-inline" action="{{route('incident.reporting')}}" method="GET">
                        <div class="form-group mr-2">
                            <input title="" onchange="this.form.submit()" style="height: 40px" id="month" class="form-control date-picker mr-2" value="{{ date('M Y', strtotime($month)) }}" name="month">
                        </div>
                        <div class="form-group">
                            <a class="btn btn-info btn-sm mr-2" onclick="$('#dataTable1_wrapper .buttons-excel').click()" href="javascript:void(0)"><i class="ti-download"></i> EXCEL</a>
                            <a class="btn btn-info btn-sm" onclick=" $('#dataTable1_wrapper .buttons-pdf').click()" href="javascript:void(0)"><i class="ti-download"></i> PDF </a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-xl mt-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-success">Total: {{count($incident_reporting_report)}}</div>
                            <div class="single-table">
                                <div class="table-responsive">
                                    <table id="dataTable1" class="table table-hover progress-table text-center table-bordered align-middle"  style="font-size:small;">
                                        <thead class="text-uppercase">
                                        <tr class="bg-light">
                                            <th scope="col">#</th>
                                            <th scope="col">DATE</th>
                                            <th scope="col">TIME</th>
                                            <th scope="col">TYPE</th>
                                            <th scope="col">REPORT COMPLETED BY</th>
                                            <th scope="col">STATUS</th>
                                            <th scope="col">ACTION BY</th>
                                            <th scope="col">VIEW</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($incident_reporting_report as $key=>$item)
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td>{{ date('Y-m-d',strtotime($item->date))}}</td>
                                                <td>{{ date('H:i',strtotime($item->time))}}</td>
                                                <td>{{$item->type}}</td>
                                                <td>{{ $item->user_name }}</td>
                                                <td><span class="status-p bg-success">Checked</span></td>
                                                <td>{{ $item->ck_name }}<br>{{Date('Y-m-d',strtotime($item->checked_at))}}<br>{{date('H:i',strtotime($item->checked_at))}}</td>
                                                <td>
                                                    <button data-tip="tooltip" title="PDF" data-placement="top" onclick="show_print('{{route('incident.reporting.print',$item->id)}}')" class="btn btn-success btn-sm"><i class="ti-cloud-down"></i></button>
                                                    <button data-tip="tooltip" title="Show" data-placement="top" onclick="show_detail('{{ route('incident.reporting.detail',$item->id) }}')" class="btn btn-{{$item->images==''?'outline-':''}}warning btn-sm"><i class="ti-search"></i></button>
                                                    @if(\Sentinel::inRole('superadmin'))
                                                        <button data-tip="tooltip" title="Undo" data-placement="top" onclick="check_item(this,'{{$item->id}}','{{route('incident.reporting.check')}}','undo')" type="button" class="btn btn-lite btn-sm"><i class="ti-reload"></i></button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="summary" role="tabpanel" aria-labelledby="summary-tab">
            <div class="row">
                <div class="col-xl mt-2">
                    <form id="form_summary" class="form-inline" action="{{route('incident.reporting')}}" method="GET">
                        <div class="form-group mr-2">
                            <input onchange="set_year()" style="height: 40px" id="year" class="form-control date-picker mr-2 year" value="{{$year}}" name="year">
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-xl mt-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="single-table">
                                <div class="table-responsive">
                                    <table id="monthlyStateTable" class="table table-hover progress-table text-center table-bordered align-middle table-striped"  style="font-size:small;">
                                        <thead class="text-uppercase">
                                        <tr class="bg-light">
                                            <th scope="col">TYPE</th>
                                            @foreach($months as $m)
                                                <th scope="col">{{$m}}</th>
                                            @endforeach
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($summary_report as $item)
                                            <tr>
                                                <td>{{$item->type}}</td>
                                                @foreach($item->incident as $incident)
                                                    <td>{{$incident}}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 mt-3">
                    <div class="card p-2" style="width: 100%">
                        <canvas id="stats_graph" height="60"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="inspect_detail">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="inspect_title" class="modal-title">Modal Title</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div id="inspect_body" class="modal-body" style="min-height: 240px">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div id="print_body" style="display: none"></div>
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script>
        let uploaded = {};
        function show_item(date) {
            location.href = '{{route('incident.reporting')}}'+'?date='+date;
        }
        function show_detail(url){
            $.get(url, function (data) {
                $("#inspect_title").html($(".page-title").html());
                $("#inspect_body").html(data);
                $("#inspect_detail").modal('show');
            });
        }
        function show_approve(url){
            $.get(url, function (data) {
                $("#inspect_title").html($(".page-title").html()+" > Approve");
                $("#inspect_body").html(data);
                $(".modal-footer").hide();
                $("#inspect_detail").modal('show');
            });
        }
        function show_comments(url){
            $.get(url, function (data) {
                $("#inspect_title").html($(".page-title").html()+" > Comments");
                $("#inspect_body").html(data);
                $(".modal-footer").hide();
                $("#inspect_detail").modal('show');
            });
        }
        function show_print(url){
            $.get(url, function (data) {
                $("#print_body").html(data);
                $("#print_body").remove();
                $('<div>', {id: 'print_body',style:'display:none'}).appendTo('.main-content');
            });
        }

        $("#month").datepicker( {
            format: "M yyyy",
            viewMode: "months",
            minViewMode: "months"
        });

        $(".year").datepicker( {
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years"
        });

        function set_month() {
            $("#form_month").submit();
        }
        function set_year() {
            $("#form_summary").submit();
        }
        function state_excel() {
            $('#monthlyStateTable_wrapper .buttons-excel').click()
        }
        function state_pdf(){
            $('#monthlyStateTable_wrapper .buttons-pdf').click()
        }

        let pl = '{{\Session::get('p_loc_name')}}';
        $(document).ready(function(){
            exportPDF(
                'REPORTS \nIncident Report',
                'QC DASHBOARD > INCIDENT REPORT',
                [0,1,2,3,4,5,6],'','',true,'',"#dataTable1"
            );

            // Add event listener to the tab links
            $('.nav-link').on('click', function(evt){
                const tabId = $(this).attr('href');
                localStorage.setItem('qc_activeTab', tabId);
            });
            let activeTab = localStorage.getItem('qc_activeTab');
            if(activeTab) {
                $('.nav-link').removeClass('active');
                $('.tab-pane').removeClass('active');
                if($(activeTab).length < 1) activeTab = "#inspection";
                $(activeTab).addClass('active');
                const tabLink = $('a[href="'+activeTab+'"]');
                tabLink.addClass('active');
            }else{
                const tabLink = $('a[href="#inspection"]');
                tabLink.addClass('active');
                $("#inspection").addClass('active');
            }
        });

        if ($('#stats_graph').length) {
            let datasets = [];
            let obj = {};
            @foreach($summary_report as $item)
                obj = {
                    label: "",
                    data: [],
                    borderColor: '#FF9900',
                    borderRadius: 5,
                    borderSkipped: false,
                    fill: false,
                }
                obj.label = '{!! $item->type !!}'
                obj.borderColor = '{!! $item->color??'#FF9900' !!}'
                obj.data = JSON.parse('{!! json_encode($item->incident) !!}')
                datasets.push(obj)
            @endforeach
            const stats_chart_dp = new Chart(document.getElementById("stats_graph").getContext('2d'), {
                type: 'line',
                data: {
                    labels: JSON.parse('{!! json_encode($months) !!}'),
                    datasets: datasets
                },
                // Configuration options go here
                options: {
                    title: {
                        display: true,
                        text: 'SUMMARY OF INCIDENTS'
                    },
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {boxWidth: 12}
                    },
                    animation: {
                        easing: "easeInOutBack"
                    },
                    elements: {
                        line: {
                            tension: 0  // Set line tension to 0 for straight lines
                        }
                    },
                    responsive:true,
                    scales: {
                        yAxes: [{
                            display: true,
                            scaleLabel: {
                                display: false,
                                labelString: 'COUNT',
                            },
                            ticks: {
                                fontColor: "#616161",
                                beginAtZero: true,
                                padding: 0,
                                steps: 1,
                                max: 16
                            },
                            gridLines: {
                                zeroLineColor: "transparent"
                            }
                        }],
                        xAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'MONTHS',
                            },
                            gridLines: {
                                zeroLineColor: "transparent",
                                display: false
                            },
                            ticks: {
                                beginAtZero: true,
                                padding: 0,
                                fontColor: "#616161",
                                fontSize:12
                            }
                        }]
                    }
                }
            });
        }
    </script>
@stop
