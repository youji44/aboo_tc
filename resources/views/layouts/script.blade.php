<script>
    unchecked_list.push('{{$item->id}}');
    if(document.getElementById('check_{{$item->id}}')){
        document.getElementById('check_{{$item->id}}').addEventListener('change', function(event) {
            if (event.target.checked) {
                checked_list.push('{{$item->id}}');
            } else {
                checked_list = checked_list.filter(num => num !== '{{$item->id}}');
            }
            count_checked(checked_list.length);
            const check_all = document.getElementById('checkAll1');
            const allCheckboxes = document.querySelectorAll('.checked_inspection');
            let allChecked = true;
            allCheckboxes.forEach(checkbox => {
                if (!checkbox.checked) {
                    allChecked = false;
                }
            });
            check_all.checked = allChecked;
        });
    }
</script>
