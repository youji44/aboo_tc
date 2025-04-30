<ul class="nav nav-tabs mt-3" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link {{$mode=='task'?'active':''}}" onclick="show_tab('task')" id="task-tab" data-toggle="tab" href="#task" role="tab" aria-controls="task" aria-selected="true">Point of Inspections - Task</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{$mode=='fleet'?'active':''}}" onclick="show_tab('fleet')" id="fleet-tab" data-toggle="tab" href="#fleet" role="tab" aria-controls="fleet" aria-selected="true">Point of Inspections - Assign Fleet</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{$mode=='cat'?'active':''}}" onclick="show_tab('cat')" id="cat-tab" data-toggle="tab" href="#cat" role="tab" aria-controls="cat" aria-selected="true">Point of Inspections - Category</a>
    </li>
</ul>
<script>
    function show_tab(mode) {
        if (mode === 'task') location.href = '{{route('qcf.settings.pointof.task')}}?mode='+mode;
        if (mode === 'fleet') location.href = '{{route('qcf.settings.pointof.fleet')}}?mode='+mode;
        if (mode === 'cat') location.href = '{{route('qcf.settings.pointof.category')}}?mode='+mode;
    }
</script>
