@extends('layouts.layout')

{{-- Page title --}}
@section('title')
    Internal Audit
@stop

{{-- page level styles --}}
@section('header_styles')
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
                                    <h4 class="page-title pull-left">{{\Session::get('p_loc_name')}} > Internal Audit</h4>
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
            <a class="nav-link active alert-info" id="inspection-tab" data-toggle="tab" href="#inspection" role="tab" aria-controls="inspection-tab" aria-selected="true">
                Perform Audit @if(count($audit) > 0) <span class="badge badge-danger ml-1">{{count($audit)}}</span> @endif</a>
        </li>
        <li class="nav-item">
            <a class="nav-link alert-secondary" id="detail_report-tab" data-toggle="tab" href="#detail_report" role="tab" aria-controls="detail_report-tab" aria-selected="true">Detailed Reports</a>
        </li>
        <li class="nav-item">
            <a class="nav-link alert-secondary" id="not-tab" data-toggle="tab" href="#not" role="tab" aria-controls="report-tab" aria-selected="true">Audit Task Not Satisfied</a>
        </li>
        <li class="nav-item">
            <a class="nav-link alert-secondary" id="not_stats-tab" data-toggle="tab" href="#not_stats" role="tab" aria-controls="report-tab" aria-selected="true">Statistics Not Satisfied</a>
        </li>
        <li class="nav-item">
            <a class="nav-link alert-secondary" id="summary-tab" data-toggle="tab" href="#summary" role="tab" aria-controls="report-tab" aria-selected="true">Summary</a>
        </li>
    </ul>
    <div class="tab-content mt-3" id="myTabContent">
        <div class="tab-pane active" id="inspection" role="tabpanel" aria-labelledby="inspection-tab">
            <div class="row">
                <div class="col-xl mt-2">
                    <a class="btn btn-success btn-sm" href="{{ route('audit.add') }}"><i class="ti-plus"></i> Add New</a>
                    @if(\Sentinel::inRole('admin') || \Sentinel::inRole('superadmin') || \Sentinel::inRole('supervisor'))
                        <a id="approve_all" class="btn btn-warning btn-sm" onclick="approve_item('')"><i class="ti-check-box"></i> Approve All</a>
                        <div class="form-group mr-2" style="display: inline-block;">
                            <select id="date" name="date" class="custom-select" onchange="show_item(this.value)">
                                <option value="" {{$date==""?'selected':''}}>All</option>
                                @foreach($pending as $item)
                                    <option value="{{$item}}" {{$date==$item?'selected':''}}>{{$item}}</option>
                                @endforeach
                            </select>
                        </div>
                        <form id="form_check_" hidden action="{{route('audit.check')}}" method="post">@csrf<input hidden name="date" value="{{$date}}"></form>@endif
                </div>
            </div>
            <div class="row">
                <div class="col-xl mt-2">
                    <div class="card">
                        <div class="card-body">
                            @include('notifications')
                            <div class="text-success">Total: {{count($audit)}}</div>
                            <div class="single-table">
                                <div class="table-responsive">
                                    <table id="dataTable" class="table table-hover progress-table text-center table-bordered align-middle"  style="font-size:small;">
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
                                            <th scope="col">AIRLINE/CUSTOMER</th>
                                            <th scope="col">UNIT#</th>
                                            <th scope="col">AUDIT ID</th>
                                            <th scope="col">AUDIT TYPE</th>
                                            <th scope="col">OPERATOR NAME</th>
                                            <th scope="col">OVERALL RESULT</th>
                                            <th scope="col">AUDITOR NAME</th>
                                            <th scope="col">STATUS</th>
                                            <th scope="col">ACTION</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($audit as $key=>$item)
                                            <tr>
                                                <td><div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input checked_inspection" id="check_{{$item->id}}">
                                                        <label class="custom-control-label" for="check_{{$item->id}}"> </label>
                                                    </div></td>
                                                <td>{{ $key+1 }}</td>
                                                <td>{{ date('Y-m-d',strtotime($item->date))}}</td>
                                                <td>{{ date('H:i',strtotime($item->time))}}</td>
                                                <td><span style="display: none">{{$item->airline_name}}</span>
                                                    @if(isset($item->logo) && $item->logo)
                                                        <img alt="{{$item->airline_name}}" class="thumb" src="{{asset('/uploads/settings/'.$item->logo)}}">
                                                    @endif
                                                </td>
                                                <td>{{ $item->fe_unit }}</td>
                                                <td>{{ $item->id }}</td>
                                                <td>{{ $item->title }}</td>
                                                <td>{{ $item->operator??$item->operator_name }}</td>
                                                <td class="alert alert-{{$item->gr_color?$item->gr_color:'secondary'}}">{{ $item->gr_result }}</td>
                                                <td>{{ $item->user_name }}</td>
                                                <td>
                                                    @if($item->status == '0' )
                                                        <span class="status-p bg-warning">Pending</span>
                                                    @else
                                                        <span class="status-p bg-success">Checked</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button data-tip="tooltip" title="Show" data-placement="top" onclick="show_detail('{{route('audit.detail',$item->id)}}')" class="btn btn-{{$item->images?'':'outline-'}}warning btn-sm"><i class="ti-search"></i></button>

                                                    <button class="btn btn-outline-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"><i class="ti-menu"></i></button>
                                                    <div class="dropdown-menu p-2">
                                                        <button data-tip="tooltip" title="PDF" data-placement="top" onclick="show_print('{{route('audit.print',$item->id)}}')" class="btn btn-success btn-sm pdf"><i class="ti-cloud-down"></i></button>
                                                        <a data-tip="tooltip" title="Edit" data-placement="top" href="{{ route('audit.edit',$item->id) }}" type="button" class="btn btn-info btn-sm"><i class="ti-pencil-alt"></i></a>
                                                        @if(\Sentinel::inRole('admin') || \Sentinel::inRole('superadmin') || \Sentinel::inRole('supervisor'))
                                                            <button data-tip="tooltip" title="Check" data-placement="top" onclick="check_item(this,'{{$item->id}}','{{route('audit.check')}}')" type="button" class="btn btn-success btn-sm"><i class="ti-check-box"></i></button>
                                                            <form id="form_check_{{$item->id}}" hidden action="{{route('audit.check')}}" method="post">
                                                                @csrf <input hidden name="id" value="{{$item->id}}">
                                                            </form>
                                                            <button data-tip="tooltip" title="Delete" data-placement="left" onclick="delete_item(this,'{{$item->id}}','{{route('audit.delete')}}')"  data-target="#delete_form" type="button" class="btn btn-danger btn-sm"><i class="ti-trash"></i></button>
                                                            <form id="form_{{$item->id}}" hidden action="{{route('audit.delete')}}" method="post">
                                                                @csrf <input hidden name="id" value="{{$item->id}}">
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
                    <form id="form_date" class="form-inline" action="{{route('audit')}}" method="GET">
                        <div class="form-group mr-2">
                            <select id="period" name="period" class="custom-select" onchange="load_data(true)">
                                <option value="0" {{$period=="0"?'selected':''}}>Today</option>
                                <option value="1" {{$period=="1"?'selected':''}}>Yesterday</option>
                                <option value="7" {{$period=="7"?'selected':''}}>Last 7 Days</option>
                                <option value="15" {{$period=="15"?'selected':''}}>Last 15 Days</option>
                                <option value="30" {{$period=="30"?'selected':''}}>Last 30 Days</option>
                                <option value="" {{$period==""?'selected':''}}>Choose Specific Date</option>
                                <option value="m" {{$period=="m"?'selected':''}}>Choose Specific Month</option>
                                <option value="r" {{$period=="r"?'selected':''}}>Choose Range Date</option>
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <select id="au" name="au" class="custom-select select2" onchange="this.form.submit()">
                                <option value="all" {{$auditor=="all"?'selected':''}}>All Auditors</option>
                                @foreach($auditors as $item)
                                    <option value="{{$item->id}}" {{$auditor==$item->id?'selected':''}}>{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <select id="op" name="op" class="custom-select select2" onchange="this.form.submit()">
                                <option value="all" {{$operator=="all"?'selected':''}}>All Operators</option>
                                @foreach($operators as $item)
                                    <option value="{{$item->id}}" {{$operator==$item->id?'selected':''}}>{{$item->operator}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <select id="airline" name="airline" class="custom-select select2" onchange="this.form.submit()">
                                <option value="all" {{$airline=="all"?'selected':''}}>All Airline/Customer</option>
                                @foreach($settings_airline as $item)
                                    <option value="{{$item->id}}" {{$airline==$item->id?'selected':''}}>{{$item->airline_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mr-2">
                            <select id="unit" name="unit" class="custom-select select2" onchange="this.form.submit()">
                                <option value="all" {{$unit=="all"?'selected':''}}>All Units</option>
                                @foreach($fuel_equipment as $item)
                                    <option value="{{$item->id}}" {{$unit==$item->id?'selected':''}}>{{$item->unit.' - '.$item->unit_type}}</option>
                                @endforeach
                            </select>
                        </div>
                        @if($period=='')
                            <div class="form-group">
                                <input onchange="this.form.submit()" id="date2" class="form-control mr-2" style="width: 100px" type="date" value="{{ $date_report }}" name="date2">
                            </div>
                        @endif
                        @if($period=="m")
                            <div class="form-group">
                                <input title="" onchange="this.form.submit()" id="month" class="form-control mr-2" style="width: 100px" value="{{ $month }}" name="month">
                            </div>
                        @endif
                        @if($period=="r")
                            <div class="form-group">
                                <label class="col-form-label mr-1">DATE START: </label>
                                <input title="" id="s_date" class="form-control mr-2" style="width: 100px" type="date" value="{{ $s_date }}" name="s_date">
                            </div>
                            <div class="form-group">
                                <label class="col-form-label mr-1">DATE END: </label>
                                <input title="" id="e_date" class="form-control mr-2" style="width: 100px" type="date" value="{{ $e_date }}" name="e_date">
                            </div>
                            <div class="form-group">
                                <a class="btn btn-outline-primary btn-sm mr-2" onclick="$('#form_date').submit()" href="javascript:"><i class="ti-search"></i></a>
                            </div>
                        @endif
                        <a class="btn btn-info btn-sm" onclick="$('#dataTable1_wrapper .buttons-excel').click()" href="javascript:void(0)"><i class="ti-download"></i> EXCEL</a>
                        <a class="btn btn-info btn-sm" onclick="$('#dataTable1_wrapper .buttons-pdf').click()" href="javascript:void(0)"><i class="ti-download"></i> PDF </a>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-xl mt-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-success">Total: {{count($audit_report)}}</div>
                            <div class="single-table">
                                <div class="table-responsive">
                                    <table id="dataTable1" class="table table-hover progress-table text-center table-bordered align-middle"  style="font-size:small;">
                                        <thead class="text-uppercase">
                                        <tr class="bg-light">
                                            <th scope="col">#</th>
                                            <th scope="col">DATE</th>
                                            <th scope="col">TIME</th>
                                            <th scope="col">AIRLINE/CUSTOMER</th>
                                            <th scope="col">UNIT#</th>
                                            <th scope="col">AUDIT TYPE</th>
                                            <th scope="col">OPERATOR NAME</th>
                                            <th scope="col">OVERALL RESULT</th>
                                            <th scope="col">AUDITOR NAME</th>
                                            <th scope="col">STATUS</th>
                                            <th scope="col">ACTION BY</th>
                                            <th scope="col">VIEW</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($audit_report as $key=>$item)
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td>{{ date('Y-m-d',strtotime($item->date))}}</td>
                                                <td>{{ date('H:i',strtotime($item->time))}}</td>
                                                <td><span style="display: none">{{$item->airline_name}}</span>
                                                    @if(isset($item->logo) && $item->logo)<img alt="{{$item->airline_name}}" class="thumb" src="{{asset('/uploads/settings/'.$item->logo)}}"> @endif
                                                </td>
                                                <td>{{ $item->fe_unit }}</td>
                                                <td style="text-align: left;">{{ $item->title }}</td>
                                                <td>{{ $item->operator??$item->operator_name }}</td>
                                                <td class="alert alert-{{$item->gr_color?$item->gr_color:'secondary'}}">{{ $item->gr_result }}</td>
                                                <td>{{ $item->user_name }}</td>
                                                <td>
                                                    @if($item->status == '0' )
                                                        <span class="status-p bg-warning">Pending</span>
                                                    @else
                                                        <span class="status-p bg-success">Checked</span>
                                                    @endif
                                                </td>
                                                <td>{{ $item->ck_name }}<br>{{Date('Y-m-d',strtotime($item->checked_at))}}<br>{{date('H:i',strtotime($item->checked_at))}}</td>
                                                <td>
                                                    <button data-tip="tooltip" title="PDF" data-placement="top" onclick="show_print('{{route('audit.print',$item->id)}}')" class="btn btn-success btn-sm"><i class="ti-cloud-down"></i></button>
                                                    <button data-tip="tooltip" title="Show" data-placement="top" onclick="show_detail('{{route('audit.detail',$item->id)}}')" class="btn btn-{{$item->images==''?'outline-':''}}warning btn-sm"><i class="ti-search"></i></button>
                                                    @if(\Sentinel::inRole('superadmin'))
                                                        <button data-tip="tooltip" title="Undo" data-placement="top" onclick="check_item(this,'{{$item->id}}','{{route('audit.check')}}','undo')" type="button" class="btn btn-lite btn-sm"><i class="ti-reload"></i></button>
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
        <div class="tab-pane" id="not" role="tabpanel" aria-labelledby="not-tab">
            <div class="row">
                <div class="col-xl mt-2">
                    <form id="form_not" class="form-inline" action="{{route('audit')}}" method="GET">
                        <div class="form-group mr-2">
                            <select id="audit_type" name="type" class="custom-select select2" onchange="load_not_data()">
                                <option value="all" {{$audit_type=="all"?'selected':''}}>All Type of Audit</option>
                                @foreach($settings_audit as $item)
                                    <option value="{{$item->id}}" {{$audit_type==$item->id?'selected':''}}>{{$item->title}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <input onchange="load_not_data()" id="month" class="form-control mr-2" style="width: 100px" value="{{ $month }}" name="month">
                        </div>
                        <a class="btn btn-info btn-sm" onclick="not_pdf()" href="javascript:void(0)"><i class="ti-download"></i> PDF </a>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-xl mt-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-success">Total: {{count($not_audit)}}</div>
                            <div class="single-table">
                                <div class="table-responsive">
                                    <table id="notdataTable" class="table table-hover progress-table text-center table-bordered align-middle"  style="font-size:small;">
                                        <thead class="text-uppercase">
                                        <tr class="bg-light">
                                            <th scope="col">#</th>
                                            <th scope="col">DATE</th>
                                            <th scope="col">TIME</th>
                                            <th scope="col">AIRLINE</th>
                                            <th scope="col">FUEL EQUIPMENT UNIT#</th>
                                            <th scope="col">AUDIT TASK</th>
                                            <th scope="col">RESULT</th>
                                            <th scope="col">COMMENTS</th>
                                            <th scope="col">OPERATOR NAME</th>
                                            <th scope="col">AUDITOR NAME</th>
                                            <th scope="col">STATUS</th>
                                            <th scope="col">ACTION BY</th>
                                            <th scope="col">VIEW</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $no=1;?>
                                        @foreach($not_audit as $item)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ date('Y-m-d',strtotime($item->date))}}</td>
                                                <td>{{ date('H:i',strtotime($item->time))}}</td>
                                                <td>{{ $item->airline_name }}</td>
                                                <td>{{ $item->unit }}</td>
                                                <td style="text-align: left;">{{ $item->question }}</td>
                                                <td class="alert alert-{{$item->gr_color?$item->gr_color:'secondary'}}">{{ $item->gr_result }}</td>
                                                <td>{!! $item->comment !!}</td>
                                                <td>{{ $item->operator??$item->operator_name }}</td>
                                                <td>{{ $item->user_name }}</td>
                                                <td>
                                                    @if($item->status == '0' )
                                                        <span class="status-p bg-warning">Pending</span>
                                                    @else
                                                        <span class="status-p bg-success">Checked</span>
                                                    @endif
                                                </td>
                                                <td>{{ $item->ck_name }}<br>{{Date('Y-m-d',strtotime($item->checked_at))}}<br>{{date('H:i',strtotime($item->checked_at))}}</td>
                                                <td>
                                                    <button data-tip="tooltip" title="PDF" data-placement="top" onclick="show_print('{{route('audit.print',$item->id)}}')" class="btn btn-success btn-sm"><i class="ti-cloud-down"></i></button>
                                                    <button data-tip="tooltip" title="Show" data-placement="top" onclick="show_detail('{{route('audit.detail',$item->id)}}')" class="btn btn-{{$item->files==''?'outline-':''}}warning btn-sm"><i class="ti-search"></i></button>
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
        <div class="tab-pane" id="not_stats" role="tabpanel" aria-labelledby="not_stats-tab">
            <div class="row">
                <div class="col-xl mt-2">
                    <form id="form_stats" class="form-inline" action="{{route('audit')}}" method="GET">
                        <div class="form-group mr-2">
                            <select id="period2" name="period2" class="custom-select" onchange="this.form.submit()">
                                <option value="0" {{$period2=="0"?'selected':''}}>Select a Year</option>
                                <option value="1" {{$period2=="1"?'selected':''}}>Select a Month</option>
                            </select>
                        </div>
                        @if($period2=="0")
                            <div class="form-group">
                                <input onchange="this.form.submit()" id="year" class="form-control mr-2" style="width: 100px" value="{{ $year }}" name="year">
                            </div>
                        @endif
                        @if($period2=="1")
                            <div class="form-group">
                                <input onchange="this.form.submit()" id="month2" class="form-control mr-2" style="width: 100px" value="{{ $month2 }}" name="month2">
                            </div>
                        @endif
                        <a class="btn btn-info btn-sm mr-2" onclick="$('#statsdataTable_wrapper .buttons-pdf').click()" href="javascript:void(0)"><i class="ti-download"></i> PDF </a>
                        <a id="send_email" class="btn btn-info btn-sm" target="_blank" href="{{url('/assets/manual/audit.php')}}?email={{Sentinel::getUser()?Sentinel::getUser()->email:''}}">@ EMAIL</a>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="col-xl mt-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="text-success">Total: {{count($stats_audit)}}</div>
                            <div class="single-table">
                                <div class="table-responsive">
                                    <table id="statsdataTable" class="table table-hover progress-table text-center table-bordered align-middle"  style="font-size:small;">
                                        <thead class="text-uppercase">
                                        <tr class="bg-light">
                                            <th scope="col">#</th>
                                            <th scope="col">AUDIT TASK</th>
                                            <th scope="col">RESULT</th>
                                            <th scope="col">{{$period2=="0"?"YEAR":"MONTH"}} TO DATE TOTAL</th>
                                            <th scope="col">{{$period2=="0"?"YEAR":"MONTH"}} TO DATE PERCENT</th>
                                            <th scope="col">VIEW</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $no=1;?>
                                        @foreach($stats_audit as $item)
                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td style="text-align: left;">{{ $item->question }}</td>
                                                <td class="alert alert-danger">Not Satisfied</td>
                                                <td>{{ $item->total }}</td>
                                                <td>{{ $item->percent.' %' }}</td>
                                                <td>
                                                    <button data-tip="tooltip" title="Show" data-placement="top" onclick="show_op('{{json_encode($item->details)}}','{{$item->question}}')" data-toggle="modal" data-target="#detail" type="button" class="btn btn-outline-warning btn-sm"><i class="ti-search"></i></button>
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
            <div class="row">
                <div class="col-xl" style="width: 100%;">
                    <div class="card">
                        <div class="card-body">
                            <canvas id="audit_pie" height="80"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="summary" role="tabpanel" aria-labelledby="summary-tab">
            <div class="row">
                <div class="col-xl mt-2">
                    <form id="form_summary" class="form-inline" action="{{route('audit')}}" method="GET">
                        <div class="form-group">
                            <input onchange="load_summary_data()" id="month1" class="form-control mr-2" style="width: 100px" value="{{ $month }}" name="month">
                        </div>
                        <div class="form-group">
                            <label class="col-form-label mr-2">Total Audits: </label>
                            <input value="{{array_sum($daily_count)}}" readonly class="form-control" style="width: 120px">
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
                                    <table class="table table-hover progress-table text-center table-bordered align-middle"  style="font-size:small;">
                                        <thead class="text-uppercase">
                                        <tr class="bg-light">
                                            <th scope="col">DAY</th>
                                            @foreach($daily as $day)
                                                <th scope="col">{{$day}}</th>
                                            @endforeach
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @for($i = 0; $i < count($depths); $i++)
                                            <tr>
                                                @foreach($record_data as $records)
                                                     <td class="alert alert-{{$records[$i]==0?'danger':''}}">{{ $records[$i] }}</td>
                                                @endforeach
                                            </tr>
                                        @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl" style="width: 100%;">
                    <div class="card">
                        <div class="card-body">
                            <canvas id="total" height="80"></canvas>
                        </div>
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
        function show_item(date) {
            location.href = '{{route('audit')}}'+'?date='+date;
        }

        function show_detail(url){
            $.get(url, function (data,status) {
                $("#inspect_title").html($(".page-title").html());
                $("#inspect_body").html(data);
                $("#inspect_detail").modal('show');
            });
        }

        flatpickr("#date2",{
            defaultDate:JSON.parse('{!! json_encode($report_date) !!}')
        });
        $("#date2").val('{{$date_report}}')
        flatpickr("#s_date");
        flatpickr("#e_date");

        let load_not_data = function () {
            $("#form_not").submit();
        };

        let load_stats_data = function () {
            $("#form_stats").submit();
        };

        let load_summary_data = function () {
            $("#form_summary").submit();
        };

        $("#month").datepicker( {
            format: "M yyyy",
            viewMode: "months",
            minViewMode: "months"
        });
        $("#month1").datepicker( {
            format: "M yyyy",
            viewMode: "months",
            minViewMode: "months"
        });
        $("#month2").datepicker( {
            format: "M yyyy",
            viewMode: "months",
            minViewMode: "months"
        });

        $("#year").datepicker( {
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years"
        });

        if ($('#statsdataTable').length) {
            $('#statsdataTable').DataTable({
                bDestroy: true,
                responsive: true,
                pageLength: 100,
                info: false,
                "columnDefs": [{
                    "targets":[0],
                    "searchable":false
                }],
                dom: 'Bfrtip',
                buttons: ['excel','pdfHtml5']
            });
            $('.dt-buttons').hide();
        }

        $(document).ready(function(){
            exportPDF(
                'REPORTS \nINTERNAL AUDIT',
                'QC DASHBOARD > INTERNAL AUDIT REPORTS',
                [0,1,2,3,4,5,6,7,8,9,10],'',false,false,'', '#dataTable1'
            );

            exportPDF(
                'REPORTS \nINTERNAL AUDIT NOT SATISFIED',
                'QC DASHBOARD > INTERNAL AUDIT NOT SATISFIED',
                [0,1,2,3,4,5,6,7,8,9,10,11],'',false,false,false,"#notdataTable"
            );

            exportPDF(
                'REPORTS \nINTERNAL AUDIT NOT SATISFIED',
                'QC DASHBOARD > INTERNAL AUDIT NOT SATISFIED',
                [0,1,2,3,4],'',false,true,false,"#statsdataTable"
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

        function not_pdf() {
            $('#notdataTable_wrapper .buttons-pdf').click()
        }

        function stats_pdf() {
            $('#statsdataTable_wrapper .buttons-pdf').click()
        }

        function show_print(url){
            $.get(url, function (data,status) {
                $("#print_body").html(data);
                $("#print_body").remove();
                $('<div>', {id: 'print_body',style:'display:none'}).appendTo('.main-content');
            });
        }

        function show_op1(details, title) {
            $("#title_body").html("AUDIT TASK: "+title);
            let tb_head = '<table class="table table-hover text-center table-bordered align-middle">' +
                '<thead class="text-uppercase"><tr><th>#</th><th>OPERATOR NAME</th><th>YEAR TO DATE TOTAL</th></tr></thead>';
            let tb_end = '</tbody></table>';
            let tb_body = '<tbody>';
            JSON.parse(details).forEach(function (value, index) {
                let no = index + 1;
                tb_body += '<tr><td>'+no+'</td><td>'+(value.operator==null?value.operator_name:value.operator)+'</td><td>'+(value.operator?value.total:value.name_total)+'</td></tr>';
            });
            $("#detail_body").html(tb_head + tb_body + tb_end);
        }
        function show_op(details, title) {
            $("#title_body").html("AUDIT TASK: "+title);
            let tb_head = '<table class="table table-hover text-center table-bordered align-middle">' +
                '<thead class="text-uppercase"><tr><th>#</th><th>OPERATOR NAME</th><th>YEAR TO DATE TOTAL</th></tr></thead>';
            let tb_end = '</tbody></table>';
            let tb_body = '<tbody>';
            JSON.parse(details).forEach(function (value, index) {
                let no = index + 1;
                let op = value.operator!==null?value.operator:value.operator_name;
                tb_body += '<tr><td>'+no+'</td><td>'+op+'</td><td>'+value.total+'</td></tr>';
            });
            $("#detail_body").html(tb_head + tb_body + tb_end);
        }

        if ($('#audit_pie').length) {
            let ctx = document.getElementById("audit_pie").getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: {!! json_encode($audit_task) !!},
                    datasets: [{
                        data: {!! json_encode($percents) !!},
                        backgroundColor: {!! json_encode($pie_colors) !!},
                    }]
                },
                options: {
                    title: {
                        display: true,
                        text: 'YEAR TO DATE TOTAL',
                        padding:30
                    },
                    legend: {
                        display: true,
                        position: 'right'
                    },
                    plugins: {
                        animation: {
                            easing: "easeInOutBack"
                        },
                        datalabels: {
                            color: 'white',
                            anchor: 'end',
                            align: 'start',
                            formatter: (value, ctx) => {
                                return ctx.chart.data.labels[ctx.dataIndex] + ': ' + value + '%';
                            }
                        }
                    }
                }
            });
        }

        if ($('#total').length) {
            const ctx = document.getElementById("total");
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($daily) !!},
                    datasets: [{
                        label: "INTERNAL AUDITS",
                        data: {!! json_encode($daily_count) !!},
                        backgroundColor:'#0072ff'
                    }]
                },
                options: {
                    title: {
                        display: true,
                        text: 'DAILY SUMMARY',
                        alignment:'left'
                    },
                    legend: {
                        display: false
                    },
                    animation: {
                        easing: "easeInOutBack"
                    },
                    responsive:true,
                    // maintainAspectRatio:false,
                    scales: {
                        yAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'INTERNAL AUDITS',
                            },
                            ticks: {
                                fontColor: "#bfbfbf",
                                beginAtZero: true,
                                padding: 0,
                                steps: 1,
                                max: 40
                            },
                            gridLines: {
                                zeroLineColor: "transparent"
                            }
                        }],
                        xAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'DAYS',
                            },
                            gridLines: {
                                zeroLineColor: "transparent",
                                display: false
                            },
                            ticks: {
                                beginAtZero: true,
                                padding: 0,
                                fontColor: "#8f8f8f",
                                fontSize:12
                            },
                            barThickness: 20,  // Width of the bars in pixels
                            barPercentage: 0.5, // Relative to the x-axis length
                            categoryPercentage: 0.5
                        }]
                    }
                }
            });
        }
    </script>
@stop
