<table id="export_audit" class="table table-bordered" style="font-size:small;">
    <thead class="text-uppercase">
        <tr class="bg-light">
            <th scope="col">AUDIT ID</th>
            <th>{{$audit->id}}</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>DATE</td>
            <td>{{ date('Y-m-d',strtotime($audit->date)) }}</td>
        </tr>
        <tr>
            <td>TIME</td>
            <td>{{ date('H:i',strtotime($audit->time)) }}</td>
        </tr>
        <tr>
            <td>AIRLINE/CUSTOMER</td>
            <td>{{$audit->logo}}</td>
        </tr>
        <tr>
            <td>TYPE OF REFUELLED</td>
            <td>{{$audit->refuelled}}</td>
        </tr>
        <tr>
            <td>FLIGHT NUMBER OR AIRCRAFT REGISTRATION</td>
            <td>{{$audit->flight_number}}</td>
        </tr>
        <tr>
            <td>LOCATION, GATE</td>
            <td>{{$audit->location_gate}}</td>
        </tr>
        <tr>
            <td>FUEL EQUIPMENT UNIT#</td>
            <td>{{$audit->fe_unit?($audit->fe_unit.' - '.$audit->unit_type):''}}</td>
        </tr>
        <tr>
            <td>OPERATOR NAME</td>
            <td>{{$audit->o_operator??$audit->operator_name}}</td>
        </tr>
        <tr>
            <td>TYPE OF AUDIT</td>
            <td>{{$audit->title}}</td>
        </tr>
        <tr>
            <td>AUDITOR NAME</td>
            <td>{{$audit->user_name}}</td>
        </tr>
        <tr>
            <td>SIGNATURE</td>
            <td></td>
        </tr>
        <tr>
            <td>OVERALL RESULT</td>
            <td>{{$audit->gr_result}}</td>
        </tr>
        <tr>
            <td>COMMENTS</td>
            <td>{{$audit->comments}}</td>
        </tr>
    </tbody>
</table>
<div class="form-group">
    <table id="table5">
        <thead class="text-uppercase">
        <tr>
            <td></td>
            <td></td>
        </tr>
        </thead>
        <tbody>
        @for($i = 0; $i < count($audit->images); $i+=2)
            <tr>
                <td>{{$audit->images[$i]}}</td>
                <td>{{isset($audit->images[$i+1])?$audit->images[$i+1]:''}}</td>
            </tr>
        @endfor
        </tbody>
    </table>
</div>
<script>

        if ($("#export_audit").length) {
            let today = new Date();
            let pageType = 'LETTER';
            let align = 'left';
            let loc_name = '{{\Session::get('p_loc_name')}}';
            let audit_questions = {!! json_encode($questions)!!};
            let images = [];
            @if($audit->images)
                @if(json_encode($audit->images))
                images = {!! json_encode($audit->images)!!};
            @else
            images.push({!! $audit->images!!});
            @endif
            @endif

            $("#export_audit").DataTable({
                bDestroy: true,
                responsive: true,
                filter: false,
                bPaginate: false,
                info: false,
                dom: 'Bfrtip',
                order: false,
                buttons: [
                    {
                        extend: 'pdfHtml5',
                        orientation: 'portrait',
                        pageSize: pageType,
                        messageTop: ' ',
                        title: loc_name.toUpperCase() + '\nINTERNAL AUDIT REPORT',
                        customize: function (doc) {
                            doc.styles.title = {
                                alignment: 'right',
                                fontSize: 16,
                                bold: true
                            };
                            doc.defaultStyle = {
                                fontSize: 10
                            };
                            let table = doc.content[2].table.body;
                            for (let i = 0; i < table.length; i++) // skip table header row (i = 0)
                            {
                                for (let j = 0; j < table[i].length; j++) {
                                    table[i][j].text = table[i][j].text
                                        .replaceAll("<br>", "\n")
                                        .replaceAll("<p>", "")
                                        .replaceAll("</p>", "\n")
                                        .replaceAll("&nbsp;", " ");
                                }
                                table[i][0].style = {fillColor: '#f2f2f2'};

                                if (i === 3) {
                                    let logo = '{!! $audit->logo !!}';
                                    if (logo)
                                        table[i][1] = {
                                            image: logo,
                                            maxWidth: 100,
                                            maxHeight: 30,
                                            alignment: 'left'
                                        };
                                }

                                let gr_image = '';
                                if (i === 12) {
                                    if ('{!! $audit->gr_value !!}' === 'condition_1') gr_image = '{!! $audit->satisfied!!}';
                                    else if ('{!! $audit->gr_value !!}' === 'condition_3') gr_image = '{!! $audit->notsatisfied!!}';
                                    else if ('{!! $audit->gr_value !!}' === 'condition_4') gr_image = '{!! $audit->na!!}';
                                }

                                if (gr_image !== '')
                                    table[i][1] = {
                                        image: gr_image,
                                        width: 80,
                                        alignment: 'left'
                                    };
                            }
                            const sign = '{{$audit->signature}}'
                            if (sign !== '')
                                table[11][1] = {
                                    image: sign,
                                    width: 40,
                                    alignment: 'left'
                                };
                            doc.content[2].layout = {
                                border: "borders",
                                hLineColor: '#cdcdcd',
                                vLineColor: '#cdcdcd'
                            };
                            doc.styles.tableHeader = {fillColor: '#ffffff', alignment: 'left'};
                            doc.styles.tableBodyOdd = {alignment: align};
                            doc.styles.tableBodyEven = {alignment: align};
                            doc.pageMargins = [50, 20, 50, 50];
                            doc.content[2].table.widths = [160, '*'];

                            doc.content.splice(1, 0, {
                                margin: [-20, -50, 0, 30],
                                alignment: 'left',
                                width: 120,
                                image: '{{\Utils::logo()}}'
                            });

                            doc.content.splice(2, 0, {
                                margin: [90, -64, 0, 30],
                                text: 'Report Generated By ' + username + ' \non ' + today.toLocaleDateString("en-US", {
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric',
                                    hour: 'numeric',
                                    minute: 'numeric'
                                })
                            });
                            let h = [10, 10, 10, 10];
                            let w = [5, 5, 5, 5];


                            let img_cnt = 0;
                            let xPos = 5;
                            audit_questions.forEach(function (value, index) {
                                if (value.indexOf("data:image") > -1) {
                                    if (value.indexOf("line-") > -1) {
                                        img_cnt = 0;
                                        let image = value.replaceAll("line-", "");
                                        if(image)
                                            doc.content.splice(5 + index, 0, {
                                                marginTop: 10,
                                                alignment: 'left',
                                                width: 510,
                                                height: 1,
                                                image: image
                                            });
                                    } else {

                                        let image = value.replaceAll("files-","");
                                        if(image)
                                            doc.content.splice(5 + index, 0, {
                                                marginLeft:5,// w[img_cnt],
                                                marginTop: 10,//h[img_cnt],
                                                alignment: 'left',
                                                maxWidth: 120,
                                                height: 120,
                                                image: image
                                            });

                                        img_cnt++;
                                    }
                                } else {
                                    if (value.indexOf("<b>") > -1) {
                                        doc.content.splice(5 + index, 0, {
                                            marginTop: 15,
                                            alignment: 'left',
                                            text: value.replaceAll("<b>", ""),
                                            bold: true,
                                            fontSize: 11
                                        });
                                    } else {
                                        doc.content.splice(5 + index, 0, {
                                            marginTop: 0,
                                            alignment: 'left',
                                            text: value
                                        });
                                    }
                                }
                            });

                            let len = audit_questions.length + 6;
                            if (images.length > 0)
                                doc.content.splice(len, 0, {
                                    marginTop: 10,
                                    alignment: 'left',
                                    bold: true,
                                    fontSize: 10,
                                    text: "IMAGES"
                                });
                            len = len + 1;

                            if ($('#table5').length) {
                                let table1 = $('#table5').DataTable({
                                    bDestroy: true,
                                    bPaginate: false,
                                    info: false,
                                    bFilter: false,
                                    order: false,
                                });
                                let headings = table1.columns().header().to$().map(function (i, e) {
                                    return e.innerHTML;
                                }).get();
                                let data = table1.rows().data();
                                let tbl1_rows = []; // the data from the first table

                                // PDF header row for the first table:
                                tbl1_rows.push($.map(headings, function (d) {
                                    return {text: '',};
                                }));

                                // PDF body rows for the first table:
                                for (let i = 0, ien = data.length; i < ien; i++) {
                                    tbl1_rows.push($.map(data[i], function (d) {
                                        if(d !== '')
                                            return {
                                                marginTop:10,
                                                marginLeft:10,
                                                maxHeight: 160,
                                                maxWidth: 160,
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
                                doc.content.splice(len+2, 1, clone);
                            }


                            doc['footer'] = (function (page, pages) {
                                return {
                                    columns: [
                                        {
                                            text: 'QC DASHBOARD > INTERNAL AUDIT',
                                            fontSize: 8
                                        },
                                        {
                                            alignment: 'right',
                                            text: 'Page:' + page.toString() + '/' + pages.toString(),
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
            $('#export_audit_wrapper .buttons-pdf').click();
        }


    function getImageDimensions(base64Data) {
        return new Promise((resolve, reject) => {
            let img = new Image();
            img.src = base64Data;
            img.onload = function() {
                resolve({ width: this.naturalWidth, height: this.naturalHeight });
            }
            img.onerror = function() {
                reject('Invalid image');
            }
        });
    }
</script>
