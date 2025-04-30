@extends('layouts.layout')

{{-- Page title --}}
@section('title')
    Grading Result
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
                                    <h4 class="page-title pull-left">{{\Session::get('p_loc_name')}} > Settings > Grading Result</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl mt-2">
            <a class="btn btn-success btn-sm" href="{{route('settings.grading.add')}}"><i class="ti-plus"></i> Add New</a>
            <a class="btn btn-info btn-sm" href="{{route('settings.regulations','grading')}}"><i class="ti-plus"></i> Regulations</a>
        </div>
    </div>
    <div class="row">
        <div class="col-xl mt-2">
            <div class="card">
                <div class="card-body">
                    @include('notifications')
                    <div class="single-table">
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover progress-table text-center table-bordered align-middle"  style="font-size:small;">
                                <thead class="text-uppercase">
                                <tr class="bg-light">
                                    <th scope="col">#</th>
                                    <th scope="col">Grade</th>
                                    <th scope="col">Result</th>
                                    <th scope="col">Grading Type</th>
                                    <th scope="col">COLOR</th>
                                    @if(\Sentinel::inRole('superadmin'))
                                    <th scope="col">VALUE</th>
                                    @endif
                                    <th scope="col">REQUIRED COMMENTS</th>
                                    <th scope="col">ACTION</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $no = 1 ?>
                                @foreach($grading as $item)
                                <tr>
                                    <td>{{$no++}}</td>
                                    <td>{{$item->grade}}</td>
                                    <td>{{$item->result}}</td>
                                    <td>
                                        @if($item->grading_type == 'percentage')Percent
                                        @elseif($item->grading_type == 'rating')Rating
                                        @elseif($item->grading_type == 'condition')Condition
                                        @elseif($item->grading_type == 'level')Level
                                        @elseif($item->grading_type == 'leaking')Leaking
                                        @elseif($item->grading_type == 'operation')Operation
                                        @elseif($item->grading_type == 'certificate')Certificate
                                        @elseif($item->grading_type == 'auditor')Auditor
                                        @elseif($item->grading_type == 'dryrating')Dry Rating
                                        @elseif($item->grading_type == 'wetrating')Wet Rating
                                        @endif
                                    </td>
                                    <td class="alert alert-{{$item->color}}">{{$item->color}}</td>
                                    @if(\Sentinel::inRole('superadmin'))
                                    <td>{{$item->value}}</td>
                                    @endif
                                    <td>{{$item->status==0?'NO':'YES'}}</td>
                                    <td>
                                        <a href="{{ route('settings.grading.edit',$item->id) }}" type="button" class="btn btn-info btn-sm"><i class="ti-pencil-alt"></i> Edit</a>
                                        <button data-tip="tooltip" title="Delete" data-placement="left" onclick="delete_id({{$item->id}})" data-toggle="modal" data-target="#delete_form" type="button" class="btn btn-danger btn-sm"><i class="ti-trash"></i> Remove</button>
                                        <form id="form_{{$item->id}}" hidden action="{{route('settings.grading.delete')}}" method="post">
                                            @csrf <input hidden name="id" value="{{$item->id}}">
                                        </form>
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
@stop

{{-- page level scripts --}}
@section('footer_scripts')
    <script>
        function delete_item(id){
            $("#form_"+id).submit();
        }
    </script>
@stop
