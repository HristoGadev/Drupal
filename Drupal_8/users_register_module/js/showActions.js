$(document).ready(function () {



    $('#edit-submit').click(function () {
        let innerText = [];
        // get image ids order
        $('#sortable li').each(function () {
            let id = $(this).attr('id');
            let split_id = id.split("_");
            imageids_arr.push(split_id[1]);
        });
        $.ajax({
            url: 'http://localhost/FFW_Task/reorder.php',
            method: 'POST',
            data: {imageids: imageids_arr},
            success: function (response) {
                alert('Reorder successfully.');
            }

        });
    });
});