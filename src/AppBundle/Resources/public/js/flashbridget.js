function getPreniumUsers(){
    var url_source = '/api/users';

    var response = $.ajax({
        url: url_source,
        data: {filters: "outstanding=1"},
        dataType: "json",
        contentType: "application/json",
        async: false
    });

    if(response.status == 200){
        var resources = response.responseJSON.resources;
        var usernames = [];

        for(i=0; i < resources.length; i++){
            usernames.push(resources[i].username);
        }

        return usernames;
    }

    return [];
}

function getProfileUser(nick){
    var url_source = '/api/users/'+nick+'?format=xml';

    var response = $.ajax({
        url: url_source,
        async: false
    });

    if(response.status == 200){
        return response.responseText;
    }

    return '';
}