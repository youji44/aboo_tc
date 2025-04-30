
<table id="export_pdf" class="table table-bordered" style="font-size:small;">
    <thead class="text-uppercase">
        <tr class="bg-light">
            <th scope="col">INCIDENT REPORTING ID</th>
            <th scope="col">{{$incident_reporting->id}}</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>DATE</td>
            <td>{{ date('Y-m-d',strtotime($incident_reporting->date)) }}</td>
        </tr>
        <tr>
            <td>TIME</td>
            <td>{{ date('H:i',strtotime($incident_reporting->time)) }}</td>
        </tr>
    </tbody>
</table>

<table id="export_pdf2">
    <thead class="text-uppercase">
    <tr class="bg-light">
        <th scope="col">LOCATION</th>
        <th scope="col">{{$incident_reporting->location_name}}</th>
    </tr>
    </thead>
    <tbody>
        <tr>
            <td>DEPARTMENT</td>
            <td>{{$incident_reporting->department_name??''}}</td>
        </tr>
        <tr>
            <td>TITLE OR DESCRIPTION</td>
            <td>{{$incident_reporting->incident_title??''}}</td>
        </tr>
        <tr>
            <td>INCIDENT NOTIFICATIONS</td>
            <td>{{$incident_reporting->notifications??''}}</td>
        </tr>
        <tr>
            <td>TYPE OF INCIDENT</td>
            <td>{{$incident_reporting->type??''}}</td>
        </tr>
    </tbody>
</table>

@foreach($form_details as $group)
        <table id="export_pdf_{{$group['fid']}}">
            <thead class="text-uppercase">
            <tr class="bg-light">
                <th scope="col">FIELDS</th>
                <th scope="col">VALUES</th>
            </tr>
            </thead>
            <tbody>
            @foreach($group['rows'] as $row)
            @if($row->input_type == '0')
                <tr>
                    <td>{{$row->item}}</td>
                    <td>{{$row->date_time??''}}</td>
                </tr>
            @endif
            @if($row->input_type == '1')
                <tr>
                    <td>{{$row->item}}</td>
                    <td>{{$row->number_field??''}}</td>
                </tr>
            @endif
            @if($row->input_type == '2')
                <tr>
                    <td>{{$row->item}}</td>
                    <td>{{$row->text_field??''}}</td>
                </tr>
            @endif
            @if($row->input_type == '3')
                <tr>
                    <td>{{$row->item}}</td>
                    <td>{!! $row->textarea_field??'' !!}</td>
                </tr>
            @endif
            @if($row->input_type == '4')
                <tr>
                    <td>{{$row->item}}</td>
                    <td>{{$row->selection_field??''}}</td>
                </tr>
            @endif
{{--            @if($row->input_type == '5')--}}
{{--                <div class="form-group1">--}}
{{--                    @if($row->image_field != null)--}}
{{--                        @if(json_decode($row->image_field))--}}
{{--                            <div class="row">--}}
{{--                                <label class="col-2 col-form-label">{{$row->item}}:</label>--}}
{{--                                <label class="col-10 col-form-label">--}}
{{--                                    @foreach(json_decode($row->image_field) as $image)--}}
{{--                                        <a class="gallery" data-fancybox="gallery" href="{{asset('/uploads/'.$image)}}">--}}
{{--                                            <img alt="Img" style="height:80px;padding: 4px" src="{{asset('/uploads/'.$image)}}"></a>--}}
{{--                                    @endforeach--}}
{{--                                </label>--}}
{{--                            </div>--}}
{{--                        @endif--}}
{{--                    @endif--}}
{{--                </div>--}}
{{--            @endif--}}
            @if($row->input_type == '6')
                <tr>
                    <td>{{$row->item}}</td>
                    <td>{{$row->gr_result??''}}</td>
                </tr>
            @endif
            @endforeach
            </tbody>
        </table>

@endforeach

{{--<table id="export_damage_images">--}}
{{--    <thead class="text-uppercase">--}}
{{--    <tr>--}}
{{--        <td>DAMAGE IMAGES</td>--}}
{{--        <td></td>--}}
{{--    </tr>--}}
{{--    </thead>--}}
{{--    <tbody>--}}
{{--    @for($i = 0; $i < count($incident_reporting->image_field); $i+=2)--}}
{{--        <tr>--}}
{{--            <td>{{$incident_reporting->image_field[$i]}}</td>--}}
{{--            <td>{{isset($incident_reporting->image_field[$i+1])?$incident_reporting->image_field[$i+1]:''}}</td>--}}
{{--        </tr>--}}
{{--    @endfor--}}
{{--    </tbody>--}}
{{--</table>--}}
<table id="export_images">
    <thead class="text-uppercase">
    <tr>
        <td>IMAGES</td>
        <td></td>
    </tr>
    </thead>
    <tbody>
    @for($i = 0; $i < count($incident_reporting->images); $i+=2)
        <tr>
            <td>{{$incident_reporting->images[$i]}}</td>
            <td>{{isset($incident_reporting->images[$i+1])?$incident_reporting->images[$i+1]:''}}</td>
        </tr>
    @endfor
    </tbody>
</table>
<table id="export_title">
    <thead>
    <tr><th>GENERAL INFORMATION</th></tr>
    </thead>
</table>
<script>
    if ($("#export_pdf").length) {
        let today = new Date();
        let loc_name = '{{\Session::get('p_loc_name')}}';
        $("#export_pdf").DataTable({
            bDestroy: true,
            responsive: true,
            filter:false,
            bPaginate:false,
            info:false,
            dom: 'Bfrtip',
            order: false,
            buttons: [
                {
                    extend: 'pdfHtml5',
                    orientation: 'portrait',
                    pageSize: 'letter',
                    messageTop:' ',
                    title:loc_name.toUpperCase()+' \nINCIDENT REPORTING',
                    customize: function (doc) {
                        doc.styles.title = {
                            alignment: 'right',
                            fontSize:16,
                            bold:true
                        };
                        doc.defaultStyle = {
                            fontSize:8
                        };
                        let table = doc.content[2].table.body;
                        for (let i = 0; i < table.length; i++) // skip table header row (i = 0)
                        {
                            for(let j = 0; j < table[i].length;j++){
                                table[i][j].alignment = 'left';
                            }
                            table[i][0].style = {fillColor: '#f2f2f2'};
                            table[i][1].style = {fillColor: '#ffffff'};
                        }
                        doc.content[2].layout = {
                            border: "borders",
                            hLineColor:'#cdcdcd',
                            vLineColor:'#cdcdcd'
                        };
                        doc.pageMargins = [50,20,50,50];

                        doc.content[2].table.widths = ['*','*'];
                        doc.content.splice( 1, 0, {
                            margin: [ -20, -50, 0, 30 ],
                            alignment: 'left',
                            width:120,
                            image:'{{\Utils::logo()}}'} );

                        doc.content.splice( 2, 0, {
                            margin: [ 90, -64, 0, 0 ],
                            text:'Report Generated By '+username+' \non '+today.toLocaleDateString("en-US", { year: 'numeric', month: 'long', day: 'numeric',hour:'numeric',minute:'numeric'})
                        } );

                        let index = 4;
                        table2(doc, "#export_pdf", index++, ['*','*'],0);
                        table2(doc, "#export_pdf2", index++, ['*','*'],2);
                        table2(doc, "#export_pdf3", index++,['*','*'],10)

                        @foreach($form_details as $group)
                            table_title(doc, "#export_title", index++,'{{$group['form_name']}}');
                            table2(doc, "#export_pdf_{{$group['fid']}}", index++,['*','*'],2);
                        @endforeach

                        @if(count($incident_reporting->images)>0)
                        doc.content.splice(index++, 0, {
                            marginLeft: 0,
                            marginTop: 10,
                            bold:true,
                            fontSize:10,
                            alignment: 'left',
                            text: "IMAGES\n"
                        });
                        table_image(doc, "#export_images", index++);
                        @endif
                        doc['footer']=(function(page, pages) {
                            return {
                                columns: [
                                    {
                                        text:'QC DASHBOARD > INCIDENT REPORTING',
                                        fontSize:8
                                    },
                                    {
                                        alignment: 'right',
                                        text: 'Page:'+ page.toString()+'/'+pages.toString(),
                                        fontSize: 8
                                    }
                                ],
                                margin: [50, 0, 50]
                            }
                        });
                    }
                }]
        });
        $('.dt-buttons').hide();
        $('#export_pdf_wrapper .buttons-pdf').click();
    }

    function table2(doc, id, slice_index, widths,top ,isBreak){
        if ($(id).length) {
            let table1 = $(id).DataTable({"ordering": false, "searching": false, "paging": false, "info": false, "destroy": true});
            let headings = table1.columns().header().to$().map(function(i,e) { return e.innerHTML;}).get();
            let data = table1.rows().data();
            let tbl1_rows = []; // the data from the first table
            // PDF header row for the first table:
            if(headings){
                tbl1_rows.push( $.map( headings, function ( d ) {
                    return {
                        text: typeof d === 'string' ? d : d+'',
                        style: 'tableHeader',
                        alignment:'center'
                    };
                } ) );
            }
            // PDF body rows for the first table:
            for ( let i = 0, ien = data.length ; i < ien ; i++ ) {
                tbl1_rows.push( $.map( data[i], function ( d ) {
                    return {
                        text: d===''?'-':d,
                        style: i % 2 ? 'tableBodyEven' : 'tableBodyOdd',
                        alignment:'left'
                    };
                } ) );
            }
            let clone = structuredClone(doc.content[4]);
            for (let i = 0; i < tbl1_rows.length; i++) // skip table header row (i = 0)
            {
                for(let j = 0; j < tbl1_rows[i].length;j++){
                    tbl1_rows[i][j].text = tbl1_rows[i][j].text
                        .replaceAll("&amp;","&")
                        .replaceAll("<br>","\n")
                        .replaceAll("<p>","")
                        .replaceAll("</p>","\n");
                    tbl1_rows[i][j].alignment = 'left';
                }
                tbl1_rows[i][0].style = {fillColor: '#f2f2f2'};
                tbl1_rows[i][1].style = {fillColor: '#ffffff'};
            }

            clone.table.body = tbl1_rows;
            clone.margin = [ 0, top, 0, 0 ];
            clone.layout = {
                border: "borders",
                hLineColor:'#cdcdcd',
                vLineColor:'#cdcdcd'
            };
            clone.table.widths =  widths;
            if(isBreak) clone.pageBreak = 'after';
            doc.content.splice(slice_index, 1, clone);
        }
    }

    function table3(doc, id, slice_index, widths,top ,isBreak){
        if ($(id).length) {
            let table1 = $(id).DataTable({"ordering": false, "searching": false, "paging": false, "info": false, "destroy": true});
            let headings = table1.columns().header().to$().map(function(i,e) { return e.innerHTML;}).get();
            let data = table1.rows().data();
            let tbl1_rows = []; // the data from the first table
            // PDF header row for the first table:
            tbl1_rows.push( $.map( headings, function ( d ) {
                return {
                    text: typeof d === 'string' ? d : d+'',
                    style: 'tableHeader',
                    alignment:'center'
                };
            } ) );

            // PDF body rows for the first table:
            for ( let i = 0, ien = data.length ; i < ien ; i++ ) {
                tbl1_rows.push( $.map( data[i], function ( d ) {
                    return {
                        text: d===''?'-':d,
                        style: i % 2 ? 'tableBodyEven' : 'tableBodyOdd',
                        alignment:'center'
                    };
                } ) );
            }
            let clone = structuredClone(doc.content[4]);
            for (let i = 0; i < tbl1_rows.length; i++) // skip table header row (i = 0)
            {
                for(let j = 0; j < tbl1_rows[i].length;j++){
                    tbl1_rows[i][j].text = tbl1_rows[i][j].text
                        .replaceAll("&amp;","&")
                        .replaceAll("<br>","\n")
                        .replaceAll("<p>","")
                        .replaceAll("</p>","\n");
                    tbl1_rows[i][j].alignment = 'center';
                    tbl1_rows[0][j].style = {fillColor: '#f2f2f2'};
                    tbl1_rows[1][j].style = {fillColor: '#ffffff'};
                }
            }

            clone.table.body = tbl1_rows;
            clone.margin = [ 0, top, 0, 0 ];
            clone.layout = {
                border: "borders",
                hLineColor:'#cdcdcd',
                vLineColor:'#cdcdcd'
            };
            clone.table.widths =  widths;
            if(isBreak) clone.pageBreak = 'after';
            doc.content.splice(slice_index, 1, clone);
        }
    }

    function table_title(doc, id, slice_index,text){
        if ($(id).length) {
            let table1 = $(id).DataTable({"ordering": false, "searching": false, "paging": false, "info": false, "destroy": true});
            let headings = table1.columns().header().to$().map(function(i,e) { return e.innerHTML;}).get();
            let data = table1.rows().data();
            let tbl1_rows = []; // the data from the first table
            // PDF header row for the first table:
            tbl1_rows.push( $.map( headings, function ( d ) {
                return {
                    text: typeof d === 'string' ? d : d+'',
                    style: 'tableHeader',
                    alignment:'center'
                };
            } ) );
            // PDF body rows for the first table:
            for ( let i = 0, ien = data.length ; i < ien ; i++ ) {
                tbl1_rows.push( $.map( data[i], function ( d ) {
                    return {
                        text: d===''?'-':d,
                        style: i % 2 ? 'tableBodyEven' : 'tableBodyOdd',
                        alignment:'left'
                    };
                } ) );
            }
            let clone = structuredClone(doc.content[4]);
            for (let i = 0; i < tbl1_rows.length; i++) // skip table header row (i = 0)
            {
                for(let j = 0; j < tbl1_rows[i].length;j++){
                    tbl1_rows[i][j].text = text;
                    tbl1_rows[i][j].alignment = 'center';
                }
                tbl1_rows[i][0].style = {fillColor: '#f2f2f2',bold:true};
            }
            clone.table.body = tbl1_rows;
            clone.margin = [ 0, 10, 0, 0 ];
            clone.layout = {
                border: "borders",
                hLineColor:'#cdcdcd',
                vLineColor:'#cdcdcd'
            };
            clone.table.widths =  ['*'];
            doc.content.splice(slice_index, 1, clone);
        }
    }

    function table_detail(doc, id, slice_index, page_break = false){
        if ($(id).length) {
            let table1 = $(id).DataTable({
                "ordering": false,
                "searching": false,
                "paging": false,
                "info": false,
                "destroy": true
            });
            let headings = table1.columns().header().to$().map(function (i, e) {
                return e.innerHTML;
            }).get();
            let data = table1.rows().data();
            let tbl1_rows = []; // the data from the first table

            // PDF header row for the first table:
            tbl1_rows.push($.map(headings, function (d) {
                return {
                    text: typeof d === 'string' ? d : d + '',
                    style: 'tableHeader',
                    alignment: 'center',
                    bold:true
                };
            }));

            // PDF body rows for the first table:
            for (let i = 0, ien = data.length; i < ien; i++) {
                tbl1_rows.push($.map(data[i], function (d) {
                    if (d === null || d === undefined) {
                        d = '';
                    }
                    let txt = typeof d === 'string' ? d : d + '';
                    txt = txt.replaceAll("&lt;p&gt;", "")
                        .replaceAll("&amp;nbsp;", "\n")
                        .replaceAll("&lt;/p&gt;", "\n")
                        .replaceAll("&lt;h2&gt;", "")
                        .replaceAll("&lt;/h2&gt;", "\n")
                        .replaceAll("&lt;h3&gt;", "")
                        .replaceAll("&lt;/h3&gt;", "\n")
                        .replaceAll("&lt;h4&gt;", "")
                        .replaceAll("&lt;/h4&gt;", "\n");
                    return {
                        text: txt,
                        style: i % 2 ? 'tableBodyEven' : 'tableBodyOdd',
                        alignment: 'left'
                    };
                }));
            }

            for (let i = 0; i < tbl1_rows.length; i++) // skip table header row (i = 0)
            {
                for (let j = 0; j < tbl1_rows[i].length; j++) {
                    tbl1_rows[i][j].text = tbl1_rows[i][j].text
                        .replaceAll("&amp;","&")
                        .replaceAll("<br>", "\n")
                        .replaceAll("<p>", "")
                        .replaceAll("</p>", "\n");
                    if(id === "#export_pdf7"){
                        if(i < 2) tbl1_rows[i][j].style = {fillColor: '#f2f2f2'};
                        else tbl1_rows[i][j].style = {fillColor: '#ffffff'};
                    }else{
                        if(i === 0) tbl1_rows[i][j].style = {fillColor: '#f2f2f2'};
                        else tbl1_rows[i][j].style = {fillColor: '#ffffff'};
                    }
                }
            }
            let clone = structuredClone(doc.content[4]);
            clone.table.body = tbl1_rows;
            clone.margin = [0, 10, 0, 0];
            clone.layout = {
                border: "borders",
                hLineColor:'#cdcdcd',
                vLineColor:'#cdcdcd'
            };
            clone.table.widths = ['*'];
            if(page_break) clone.pageBreak = 'after';
            doc.content.splice(slice_index, 1, clone);
        }
    }


    function table_image(doc, id, slice_index){
        if ($(id).length) {
            let table1 = $(id).DataTable({"destroy": true});
            let headings = table1.columns().header().to$().map(function (i, e) {return e.innerHTML;}).get();
            let data = table1.rows().data();
            let tbl1_rows = []; // the data from the first table
            // PDF header row for the first table:
            tbl1_rows.push($.map(headings, function (d) {return {text: '',};}));

            // PDF body rows for the first table:
            for (let i = 0, ien = data.length; i < ien; i++) {
                tbl1_rows.push($.map(data[i], function (d) {
                    if(d !== '')
                        return {
                            marginTop:10,
                            marginLeft:10,
                            maxHeight: 160,
                            maxWidth: 180,
                            alignment:'left',
                            image:d
                        };
                    else return {text: ''};
                }));
            }
            let clone = structuredClone(doc.content[4]);
            clone.table.body = tbl1_rows;
            clone.margin = [0, 5, 0, 0];
            clone.layout = {
                border: "borders",
                hLineColor: '#ffffff',
                vLineColor: '#ffffff'
            };
            clone.table.widths = Array("*","*");
            doc.content.splice(slice_index, 1, clone);
        }
    }

</script>

