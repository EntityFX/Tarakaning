function updateDefectItem(inputSelector, hidableSelector) {
    if ($(inputSelector).val()=="Task") $(hidableSelector).hide();

    $(inputSelector).change(function() {
        switch ($(this).val()) {
            case "Task":
                $(hidableSelector).hide();
                break;
            case "Defect":
                $(hidableSelector).show();
                break;
        }
    });
}

function updateProjectUsers(selector, projectID, url)
{
    $.getJSON(
        url, {
            project_id: projectID
        },
        function(dataResult) {
            var usersList='<option value=" ">-</option>';

            $.each(dataResult, function(key,val){
                usersList+='<option value="' + val.UserID + '">' + val + '</option>';
            });

            $(selector).empty().append(usersList);
        }
    );
}
