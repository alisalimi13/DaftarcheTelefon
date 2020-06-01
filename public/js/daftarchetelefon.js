var CCID = null;
var CGID = null;
var C = null;
var G = null;

function ajax(json, cFunction) {
    var xhttp;
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            loading();
            cFunction(this);
        }
    };

    xhttp.open("POST", "/request", true);
    xhttp.setRequestHeader('X-CSRF-Token', document.querySelector('meta[name = "csrf-token"]').getAttribute('content'));
    if (json instanceof FormData) {
        xhttp.send(json);
    } else {
        xhttp.setRequestHeader("Content-type", "application/json");
        xhttp.send(JSON.stringify(json));
    }
    loading();
}

function searchAjax(json, cFunction) {
    var xhttp;
    xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            cFunction(this);
        }
    };

    xhttp.open("POST", "/request", true);
    xhttp.setRequestHeader('X-CSRF-Token', document.querySelector('meta[name = "csrf-token"]').getAttribute('content'));
    if (json instanceof FormData) {
        xhttp.send(json);
    } else {
        xhttp.setRequestHeader("Content-type", "application/json");
        xhttp.send(JSON.stringify(json));
    }
}


// Lists

function getContacts(xhttp) {
    //dispaly
    document.getElementById('list-group').style.display = "none";
    document.getElementById('list-contact').style.display = "block";

    //fill
    var myObj = JSON.parse(xhttp.responseText);
    C = myObj;
    var people = myObj[0];
    var notPeople = myObj[1];
    var onclick = "ajax({type:3, contact_id:this.id}, showContactDetail); showSetting('detail');";

    document.getElementById("list-contact-person").innerHTML = "";
    if (people) {
        for (var i = 0; i < people.length; i++) {
            document.getElementById("list-contact-person").innerHTML += '<li id="' + people[i][0] +
                '" onclick="' + onclick + '">' + people[i][1] + '&#160;' + people[i][2] + '</li>';
        }
    }

    document.getElementById("list-contact-notperson").innerHTML = "";
    if (notPeople) {
        for (var i = 0; i < notPeople.length; i++) {
            document.getElementById("list-contact-notperson").innerHTML += '<li id="' + notPeople[i][0] +
                '" onclick="' + onclick + '">' + notPeople[i][1] + '&#160;' + notPeople[i][2] + '</li>';
        }
    }
}

function getGroups(xhttp) {
    //display
    document.getElementById('list-contact').style.display = "none";
    document.getElementById('list-group').style.display = "block"

    //fill
    var groups = JSON.parse(xhttp.responseText);
    G = groups;
    var onclick = "ajax({type:4, group_id:this.id}, showGroupDetail); showSetting('detail');";

    document.getElementById("list-group-content").innerHTML = '';
    if (groups) {
        for (var i = 0; i < groups.length; i++) {
            document.getElementById("list-group-content").innerHTML += '<li id="' + groups[i][0] +
                '" onclick="' + onclick + '">' + groups[i][1] + '</li>';

        }
    }
}


// Details

function showContactDetail(xhttp) {
    //display
    document.getElementById('detail-group').style.display = "none";
    document.getElementById('edit-contact').style.display = "none";
    document.getElementById('edit-group').style.display = "none";
    document.getElementById('detail-contact').style.display = "block";

    //fill
    var contact = JSON.parse(xhttp.responseText);

    CCID = contact.id;

    document.getElementById('img-contact').setAttribute('src', '/photos/contacts/' +
        CCID + '/img.jpg?' + new Date().getTime());

    if (contact.isPerson) {
        document.getElementById("isperson").innerHTML = "Person";
        document.getElementById("person-detail").style.display = "block";
        document.getElementById("notperson-detail").style.display = "none";

        document.getElementById("firstname").innerHTML = contact.detail.firstName;
        document.getElementById("lastname").innerHTML = contact.detail.lastName;
        document.getElementById("gender").innerHTML = (contact.detail.isMale ?
            "Male" : "Female");
    } else {
        document.getElementById("isperson").innerHTML = "NotPerson";
        document.getElementById("person-detail").style.display = "none";
        document.getElementById("notperson-detail").style.display = "block";

        document.getElementById("type").innerHTML = contact.detail.type;
        document.getElementById("title").innerHTML = contact.detail.title;
    }

    document.getElementById("groups").innerHTML = "";
    if (contact.groups) {
        for (var i = 0; i < contact.groups.length; i++) {
            document.getElementById("groups").innerHTML += "<li>" +
                contact.groups[i].title + "</li>";
        }
    }

    document.getElementById("numbers").innerHTML = "";
    if (contact.numbers) {
        for (var i = 0; i < contact.numbers.length; i++) {
            document.getElementById("numbers").innerHTML += "<li><span>" +
                contact.numbers[i].type + "</span>: <span>" +
                contact.numbers[i].number + "</span></li>";
        }
    }

    document.getElementById("emails").innerHTML = "";
    if (contact.emails) {
        for (var i = 0; i < contact.emails.length; i++) {
            document.getElementById("emails").innerHTML += "<li>" +
                contact.emails[i].email + "</li>";
        }
    }

    document.getElementById("addresses").innerHTML = "";
    if (contact.addresses) {
        for (var i = 0; i < contact.addresses.length; i++) {
            document.getElementById("addresses").innerHTML += "<li><span>" +
                contact.addresses[i].type + "</span>: <span>" +
                contact.addresses[i].address + "</span></li>";
        }
    }

    document.getElementById("comments").innerHTML = "";
    if (contact.comments) {
        for (var i = 0; i < contact.comments.length; i++) {
            document.getElementById("comments").innerHTML += "<li>" +
                contact.comments[i].comment + "</li>";
        }
    }
}

function showGroupDetail(xhttp) {
    //display
    document.getElementById('detail-contact').style.display = "none";
    document.getElementById('edit-contact').style.display = "none";
    document.getElementById('edit-group').style.display = "none";
    document.getElementById('detail-group').style.display = "block";

    //fill
    var group = JSON.parse(xhttp.responseText);
    CGID = group.id;

    document.getElementById('img-group').setAttribute('src', '/photos/groups/' + CGID +
        '/img.jpg?' + new Date().getTime());

    document.getElementById("title-group").innerHTML = group.title;
    document.getElementById("list-people-group").innerHTML = "";
    document.getElementById("list-notpeople-group").innerHTML = "";
    if (group.contacts) {
        for (var i = 0; i < group.contacts.length; i++) {
            if (group.contacts[i].isPerson)
                document.getElementById("list-people-group").innerHTML +=
                "<li>" + group.contacts[i].detail.firstName + '&#160;' +
                group.contacts[i].detail.lastName + "</li>";
            else
                document.getElementById("list-notpeople-group").innerHTML +=
                "<li>" + group.contacts[i].detail.type + '&#160;' +
                group.contacts[i].detail.title + "</li>";
        }
    }
}


// Deletes

function contactDeleteMessage(xhttp) {
    var isDelete = xhttp.responseText;
    if (isDelete == 'true') {
        window.alert("contact is deleted");
        document.getElementById('detail-contact').style.display = "none";
        ajax({ type: 1 }, getContacts);
    } else {
        window.alert("contact is not deleted");
    }
}

function groupDeleteMessage(xhttp) {
    var isDelete = xhttp.responseText;
    if (isDelete == 'true') {
        window.alert("group is deleted");
        document.getElementById('detail-group').style.display = "none";
        ajax({ type: 2 }, getGroups);
    } else {
        window.alert("group is not deleted");
    }
}


// Edits

function showContactEdit(xhttp) {
    //display
    document.getElementById('detail-group').style.display = "none";
    document.getElementById('edit-contact').style.display = "block";
    document.getElementById('edit-group').style.display = "none";
    document.getElementById('detail-contact').style.display = "none";

    //fill
    var contact = JSON.parse(xhttp.responseText);

    document.getElementById('img-contact-edit').setAttribute('src', '/photos/contacts/' + CCID +
        '/img.jpg?' + new Date().getTime());

    document.getElementById("contact-photo").form.reset();

    if (contact.isPerson) {
        document.getElementById('isperson-edit').checked = true;

        document.getElementById("person-detail-edit").style.display = "block";
        document.getElementById("notperson-detail-edit").style.display = "none";

        document.getElementById("firstname-edit").value = contact.detail.firstName;
        document.getElementById("lastname-edit").value = contact.detail.lastName;

        if (contact.detail.isMale) {
            document.getElementById('gender-male').checked = true;
            document.getElementById('gender-female').checked = false;
        } else {
            document.getElementById('gender-female').checked = true;
            document.getElementById('gender-male').checked = false;
        }

        document.getElementById("type-edit").value = "";
        document.getElementById("title-edit").value = "";
    } else {
        document.getElementById('isperson-edit').checked = false;

        document.getElementById("person-detail-edit").style.display = "none";
        document.getElementById("notperson-detail-edit").style.display = "block";

        document.getElementById("type-edit").value = contact.detail.type;
        document.getElementById("title-edit").value = contact.detail.title;

        document.getElementById("firstname-edit").value = "";
        document.getElementById("lastname-edit").value = "";
    }

    document.getElementById("groups-edit").innerHTML = "";
    if (G) {
        for (var i = 0; i < G.length; i++) {
            document.getElementById("groups-edit").innerHTML +=
                '<li><input type="checkbox" name="groups" id="group-' +
                G[i][0] + '" data-group-id="' + G[i][0] + '">' + G[i][1] + "</li>";
        }
        if (contact.groups) {
            for (var i = 0; i < contact.groups.length; i++) {
                document.getElementById('group-' + contact.groups[i].id).setAttribute("checked", "checked");
            }
        }
    }

    document.getElementById('numbers-edit').innerHTML = "";
    if (contact.numbers) {
        for (var i = 0; i < contact.numbers.length; i++) {
            document.getElementById('numbers-edit').innerHTML +=
                '<li><input type="text" value="' + contact.numbers[i].type +
                '"><input type="text" value="' + contact.numbers[i].number +
                '"><button onclick=this.parentNode.remove()>Delete</button></li>';
        }
    }

    document.getElementById('emails-edit').innerHTML = "";
    if (contact.emails) {
        for (var i = 0; i < contact.emails.length; i++) {
            document.getElementById('emails-edit').innerHTML +=
                '<li><input type="text" value="' + contact.emails[i].email +
                '"><button onclick=this.parentNode.remove()>Delete</button></li>';
        }
    }

    document.getElementById('addresses-edit').innerHTML = "";
    if (contact.addresses) {
        for (var i = 0; i < contact.addresses.length; i++) {
            document.getElementById('addresses-edit').innerHTML +=
                '<li><input type="text" value="' + contact.addresses[i].type +
                '"><input type="text" value="' + contact.addresses[i].address +
                '"><button onclick=this.parentNode.remove()>Delete</button></li>';
        }
    }

    document.getElementById('comments-edit').innerHTML = "";
    if (contact.comments) {
        for (var i = 0; i < contact.comments.length; i++) {
            document.getElementById('comments-edit').innerHTML +=
                '<li><input type="text" value="' + contact.comments[i].comment +
                '"><button onclick=this.parentNode.remove()>Delete</button></li>';
        }
    }
}

function add(type) {
    maintain(type);

    if (type == 'number') {
        document.getElementById('numbers-edit').innerHTML +=
            '<li><input type="text" value="" placeholder="Number Type"><input type="text" value="" placeholder="Number">' +
            '<button onclick=this.parentNode.remove()>Delete</button></li>';
    } else if (type == 'email') {
        document.getElementById('emails-edit').innerHTML +=
            '<li><input type="text" value="">' +
            '<button onclick=this.parentNode.remove()>Delete</button></li>';
    } else if (type == 'address') {
        document.getElementById('addresses-edit').innerHTML +=
            '<li><input type="text" value="" placeholder="Address Type"><input type="text" value="" placeholder="Address">' +
            '<button onclick=this.parentNode.remove()>Delete</button></li>';
    } else if (type == 'comment') {
        document.getElementById('comments-edit').innerHTML +=
            '<li><input type="text" value="">' +
            '<button onclick=this.parentNode.remove()>Delete</button></li>';
    }

    return;
}

function maintain(type) {
    var childs;

    if (type == 'number') {
        childs = document.getElementById('numbers-edit').children;
        for (var child of childs) {
            child.children[0].setAttribute('value', child.children[0].value);
            child.children[1].setAttribute('value', child.children[1].value);
        }
    } else if (type == 'email') {
        childs = document.getElementById('emails-edit').children;
        for (var child of childs) {
            child.children[0].setAttribute('value', child.children[0].value);
        }
    } else if (type == 'address') {
        childs = document.getElementById('addresses-edit').children;
        for (var child of childs) {
            child.children[0].setAttribute('value', child.children[0].value);
            child.children[1].setAttribute('value', child.children[1].value);
        }
    } else if (type == 'comment') {
        childs = document.getElementById('comments-edit').children;
        for (var child of childs) {
            child.children[0].setAttribute('value', child.children[0].value);
        }
    }

    return;
}

function showGroupEdit(xhttp) {
    //display
    document.getElementById('detail-group').style.display = "none";
    document.getElementById('edit-contact').style.display = "none";
    document.getElementById('edit-group').style.display = "block";
    document.getElementById('detail-contact').style.display = "none";

    //fill
    var group = JSON.parse(xhttp.responseText);

    document.getElementById('img-group-edit').setAttribute('src', '/photos/groups/' + CGID +
        '/img.jpg?' + new Date().getTime());

    document.getElementById("group-photo").form.reset();

    document.getElementById('title-group-edit').value = group.title;

    document.getElementById('list-people-group-edit').innerHTML = "";
    document.getElementById('list-notpeople-group-edit').innerHTML = "";
    if (C) {
        var people = C[0];
        var notPeople = C[1];

        if (people) {
            for (var i = 0; i < people.length; i++) {
                document.getElementById('list-people-group-edit').innerHTML +=
                    '<li><input type="checkbox" name="people" id="contact-' + people[i][0] +
                    '" data-contact-id="' + people[i][0] + '">' + people[i][1] + '&#160;' + people[i][2] + '</li>';
            }
        }

        if (notPeople) {
            for (var i = 0; i < notPeople.length; i++) {
                document.getElementById('list-notpeople-group-edit').innerHTML +=
                    '<li><input type="checkbox" name="notpeople" id="contact-' + notPeople[i][0] +
                    '" data-contact-id="' + notPeople[i][0] + '">' + notPeople[i][1] + '&#160;' + notPeople[i][2] + '</li>';
            }
        }

        if (group.contacts) {
            for (var i = 0; i < group.contacts.length; i++) {
                document.getElementById('contact-' + group.contacts[i].id).checked = true;
            }
        }
    }

    return;
}

//dispaly

function display(list, panel) {

    var listElement = document.getElementById(list);
    var panelElement = document.getElementById(panel);
    var currentStyle = window.getComputedStyle(listElement);

    if (currentStyle.getPropertyValue('display') == 'none') {

        listElement.style.display = 'block';
        panelElement.style.height = 'auto';
    } else {
        listElement.style.display = 'none';

    }

    return;
}


// Search

function Search() {
    var text = document.getElementById("searchText").value;
    var selectedItem = document.getElementById("searchBy").value;

    if (selectedItem == "1") {
        //ajax('type=7&str=' + str + '&field=' + selectedItem, showHintName);
        searchAjax({ type: 7, str: text, field: selectedItem }, showHintName);
    } else if (selectedItem == "2") {
        //ajax('type=7&str=' + str + '&field=' + selectedItem, showHintNumber);
        searchAjax({ type: 7, str: text, field: selectedItem }, showHintNumber);
    }

}


function showHintName(xhttp) {
    var myObj = JSON.parse(xhttp.responseText);
    var people = myObj[0];
    var notPeople = myObj[1];


    //var onclick = "ajax('type=3&contact_id='+this.id, showContactDetail)";
    var onclick = "ajax({type:3, contact_id:this.id}, showContactDetail)";


    document.getElementById("list-contact-notperson").innerHTML = "";
    document.getElementById("list-contact-person").innerHTML = "";

    if (people) {
        for (var i in people) {
            document.getElementById("list-contact-person").innerHTML += '<li id="' + people[i]['contact_id'] +
                '" onclick="' + onclick + '">' + people[i]['firstName'] + '&#160;' + people[i]['lastName'] + '</li>';
        }
    }

    if (notPeople) {
        for (var i in notPeople) {
            document.getElementById("list-contact-notperson").innerHTML += '<li id="' + notPeople[i]['contact_id'] +
                '" onclick="' + onclick + '">' + notPeople[i]['type'] + '&#160;' + notPeople[i]['title'] + '</li>';
        }
    }

}

function showHintNumber(xhttp) {
    var myObj = JSON.parse(xhttp.responseText);
    var people = myObj['people'];
    var notPeople = myObj['notPeople'];

    //var onclick = "ajax('type=3&contact_id='+this.id, showContactDetail)";
    var onclick = "ajax({type:3, contact_id:this.id}, showContactDetail)";

    document.getElementById("list-contact-notperson").innerHTML = '';
    document.getElementById("list-contact-person").innerHTML = '';

    if (people) {

        for (var i in people) {
            document.getElementById("list-contact-person").innerHTML += '<li id="' + people[i]['contact_id'] +
                '" onclick="' + onclick + '">' + people[i]['number'] + '&#160;' + people[i]['firstName'] + '&#160;' + people[i]['lastName'] + '</li>';
        }
    }

    if (notPeople) {

        for (var i in notPeople) {
            document.getElementById("list-contact-notperson").innerHTML += '<li id="' + notPeople[i]['contact_id'] +
                '" onclick="' + onclick + '">' + notPeople[i]['number'] + '&#160;' + notPeople[i]['type'] + '&#160;' + notPeople[i]['title'] + '</li>';
        }
    }
}


// Saves

function saveContact() {

    var fd = new FormData();

    var arr, n, childs, photoFile;
    var child = [];

    fd.append('contactID', CCID);

    if (document.getElementById('isperson-edit').checked == true) {
        fd.append('isPerson', true);
        if (document.getElementById('firstname-edit').value == "") {
            alert('Firstname should be filled!!!');
            return;
        }
        fd.append('firstName', document.getElementById('firstname-edit').value);
        if (document.getElementById('lastname-edit').value == "") {
            alert('Lastname should be filled!!!');
            return;
        }
        fd.append('lastName', document.getElementById('lastname-edit').value);
        if (document.getElementById('gender-male').checked == true)
            fd.append('isMale', true);
        else
            fd.append('isMale', false);
    } else {
        fd.append('isPerson', false);
        if (document.getElementById('type-edit').value == "") {
            alert('Type should be filled!!!');
            return;
        }
        fd.append('type-contact', document.getElementById('type-edit').value);
        if (document.getElementById('title-edit').value == "") {
            alert('Title should be filled!!!');
            return;
        }
        fd.append('title', document.getElementById('title-edit').value);
    }

    //groups
    arr = [];
    n = 0;
    childs = document.getElementById('groups-edit').children;
    for (var i = 0; i < childs.length; i++) {
        child = childs[i].children[0];
        if (child.checked == true) {
            arr[n] = child.getAttribute('data-group-id');
            fd.append('groups[' + n + ']', arr[n]);
            n++;
        }
    }
    if (n == 0)
        fd.append('groups', '');


    //numbers
    arr = [];
    n = 0;
    childs = document.getElementById('numbers-edit').children;
    for (var i = 0; i < childs.length; i++) {
        arr[n] = [];
        child[0] = childs[i].children[0];
        child[1] = childs[i].children[1];
        if (child[0].value == "") {
            alert('Number type should be filled!!!');
            return;
        }
        arr[n][0] = child[0].value;
        if (child[1].value == "") {
            alert('Number should be filled!!!');
            return;
        }
        arr[n][1] = child[1].value;
        fd.append('numbers[' + n + '][0]', arr[n][0]);
        fd.append('numbers[' + n + '][1]', arr[n][1]);
        n++;
    }
    if (n == 0)
        fd.append('numbers', '');

    //emails
    arr = [];
    n = 0;
    childs = document.getElementById('emails-edit').children;
    for (var i = 0; i < childs.length; i++) {
        child = childs[i].children[0];
        if (child.value == "") {
            alert('Email should be filled!!!');
            return;
        }
        arr[n] = child.value;
        fd.append('emails[' + n + ']', arr[n]);
        n++;
    }
    if (n == 0)
        fd.append('emails', '');

    //addresses
    arr = [];
    n = 0;
    childs = document.getElementById('addresses-edit').children;
    for (var i = 0; i < childs.length; i++) {
        arr[n] = [];
        child[0] = childs[i].children[0];
        child[1] = childs[i].children[1];
        if (child[0].value == "") {
            alert('Address type should be filled!!!');
            return;
        }
        arr[n][0] = child[0].value;
        if (child[1].value == "") {
            alert('Address should be filled!!!');
            return;
        }
        arr[n][1] = child[1].value;
        fd.append('addresses[' + n + '][0]', arr[n][0]);
        fd.append('addresses[' + n + '][1]', arr[n][1]);
        n++;
    }
    if (n == 0)
        fd.append('addresses', '');

    //comments
    arr = [];
    n = 0;
    childs = document.getElementById('comments-edit').children;
    for (var i = 0; i < childs.length; i++) {
        child = childs[i].children[0];
        if (child.value == "") {
            alert('Comment should be filled!!!');
            return;
        }
        arr[n] = child.value;
        fd.append('comments[' + n + ']', arr[n]);
        n++;
    }
    if (n == 0)
        fd.append('comments', '');

    photoFile = document.getElementById('contact-photo').files[0];
    fd.append('photo', photoFile);

    // document.getElementById('consol').innerHTML += "<p> FormData Content:<br />";
    // for (var e of fd.entries())
    //     document.getElementById('consol').innerHTML += ' ' + e[0] + ": " + e[1] + ' ' + typeof(e[1]) + '<br />';
    // document.getElementById('consol').innerHTML += "</p>";

    fd.append('type', 9);

    ajax(fd, saveContactMessage);

    return;
}

function saveGroup() {
    var fd = new FormData();

    var arr, n, childs, child, photoFile;

    fd.append('groupID', CGID);

    if (document.getElementById('title-group-edit').value == "") {
        alert('Title should be filled!!!');
        return;
    }
    fd.append('title', document.getElementById('title-group-edit').value);

    //contacts
    arr = [];
    n = 0;

    childs = document.getElementById('list-people-group-edit').children;
    for (var i = 0; i < childs.length; i++) {
        child = childs[i].children[0];
        if (child.checked == true) {
            arr[n] = child.getAttribute('data-contact-id');
            fd.append('contacts[' + n + ']', arr[n]);
            n++;
        }
    }
    childs = document.getElementById('list-notpeople-group-edit').children;
    for (var i = 0; i < childs.length; i++) {
        child = childs[i].children[0];
        if (child.checked == true) {
            arr[n] = child.getAttribute('data-contact-id');
            fd.append('contacts[' + n + ']', arr[n]);
            n++;
        }
    }

    if (n == 0)
        fd.append('contacts', '');

    //photo
    photoFile = document.getElementById('group-photo').files[0];
    fd.append('photo', photoFile);


    // document.getElementById('consol').innerHTML += "<p> FormData Content:<br />";
    // for (var e of fd.entries())
    //     document.getElementById('consol').innerHTML += ' ' + e[0] + ": " + e[1] + ' ' + typeof(e[1]) + '<br />';
    // document.getElementById('consol').innerHTML += "</p>";

    fd.append('type', 10);

    ajax(fd, saveGroupMessage);

    return;
}

function saveContactMessage(xhttp) {
    showSetting('list');
    showMessage('footer-message-box', 'Save is done :)', 'success');
    document.getElementById('edit-contact').style.display = "none";
    ajax({ type: 1 }, getContacts);
    return;
}

function saveGroupMessage(xhttp) {
    showSetting('list');
    alert('Save is done :)');
    document.getElementById('edit-group').style.display = "none";
    ajax({ type: 2 }, getGroups);
    return;
}

// isPerson Check
function isPersonCheck() {
    if (document.getElementById('isperson-edit').checked == true) {
        document.getElementById('notperson-detail-edit').style.display = 'none';
        document.getElementById('person-detail-edit').style.display = 'block';
    } else {
        document.getElementById('notperson-detail-edit').style.display = 'block';
        document.getElementById('person-detail-edit').style.display = 'none';
    }

    return;
}

//Amin
// Import And Export
function exportCSV() {
    window.open("/contacts.csv", "_blank");
}

function exportJSON() {
    window.open("/contacts.json", "_blank");
}

function importJSON() {
    var myFile = document.getElementById('import-json').files[0];
    var fd = new FormData();
    fd.append('contactsFile', myFile);
    fd.append('type', 8);
    ajax(fd, importMessage);
}

function importMessage(xhttp) {
    alert('Import is done :)');
    document.getElementById("import-json").form.reset();
    ajax({ type: 1 }, getContacts);
    return;
}

function showSetting(type) {
    var es = ['setting', 'list', 'detail'];
    for (var e of es) {
        document.getElementById(e).classList.remove('show-p');
        if (e != 'detail' && type != 'detail')
            document.getElementById(e).classList.remove('show-t');
    }
    document.getElementById(type).classList.add('show-p');
    document.getElementById(type).classList.add('show-t');
}


// function startLoading() {
//     document.getElementById('loader').classList.remove('hide');
//     document.getElementById('loader-back').classList.remove('hide');
// }

// function endLoading() {
//     document.getElementById('loader').classList.add('hide');
//     document.getElementById('loader-back').classList.add('hide');
// }

function loading() {
    document.getElementById('loader-back').classList.toggle('default-hidden');
}

//
function addContact() {
    //display   
    document.getElementById('edit-contact').style.display = "block";
    document.getElementById('edit-group').style.display = "none";
    document.getElementById('detail-contact').style.display = "none";
    document.getElementById('detail-contact').style.display = "none";

    CCID = -1;

    document.getElementById('img-contact-edit').setAttribute('src', '/photos/contacts/' + CCID +
        '/img.jpg?' + new Date().getTime());

    document.getElementById("contact-photo").form.reset();

    document.getElementById('isperson-edit').checked = true;

    document.getElementById("person-detail-edit").style.display = "block";
    document.getElementById("notperson-detail-edit").style.display = "none";

    document.getElementById("firstname-edit").value = "";
    document.getElementById("lastname-edit").value = "";

    document.getElementById("type-edit").value = "";
    document.getElementById("title-edit").value = "";

    document.getElementById("groups-edit").innerHTML = "";
    if (G) {
        for (var i = 0; i < G.length; i++) {
            document.getElementById("groups-edit").innerHTML +=
                '<li><input type="checkbox" name="groups" id="group-' +
                G[i][0] + '" data-group-id="' + G[i][0] + '">' + G[i][1] + "</li>";
        }
    }

    document.getElementById('numbers-edit').innerHTML = "";

    document.getElementById('emails-edit').innerHTML = "";

    document.getElementById('addresses-edit').innerHTML = "";

    document.getElementById('comments-edit').innerHTML = "";

    return;

}

function addGroup() {
    //display   
    document.getElementById('edit-group').style.display = "block";
    document.getElementById('edit-contact').style.display = "none";
    document.getElementById('detail-contact').style.display = "none";
    document.getElementById('detail-group').style.display = "none";

    CGID = -1;

    document.getElementById('img-group-edit').setAttribute('src', '/photos/groups/' + CGID +
        '/img.jpg?' + new Date().getTime());

    document.getElementById("group-photo").form.reset();

    document.getElementById('title-group-edit').value = "";

    document.getElementById('list-people-group-edit').innerHTML = "";
    document.getElementById('list-notpeople-group-edit').innerHTML = "";
    if (C) {
        var people = C[0];
        var notPeople = C[1];

        if (people) {
            for (var i = 0; i < people.length; i++) {
                document.getElementById('list-people-group-edit').innerHTML +=
                    '<li><input type="checkbox" name="people" id="contact-' + people[i][0] +
                    '" data-contact-id="' + people[i][0] + '">' + people[i][1] + '&#160;' + people[i][2] + '</li>';
            }
        }

        if (notPeople) {
            for (var i = 0; i < notPeople.length; i++) {
                document.getElementById('list-notpeople-group-edit').innerHTML +=
                    '<li><input type="checkbox" name="notpeople" id="contact-' + notPeople[i][0] +
                    '" data-contact-id="' + notPeople[i][0] + '">' + notPeople[i][1] + '&#160;' + notPeople[i][2] + '</li>';
            }
        }
    }

    return;
}

function onLoad() {
    ajax({ type: 2 }, getGroups);
    ajax({ type: 1 }, getContacts);
    showSetting('list');
}

function showMessage(divID, message, type) {
    document.getElementById(divID).style.display = "block";
    document.getElementById(divID).innerHTML = message;
    if (type == 'danger') {
        document.getElementById(divID).classList.add('message-danger');
    }
    if (type == 'success') {
        document.getElementById(divID).classList.add('message-success');
    }
    setTimeout(function() {
        document.getElementById(divID).style.display = "none";
    }, 10000);
}

document.querySelector('#footer-message-box').addEventListener('click', function() {
    this.style.display = 'none';
});