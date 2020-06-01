<div class="col-12 row-12">{{--main div--}}
    <header class="col-12 panel">
       <div class="col-10 row-12 header">Contact Edit</div>
        <button onclick="showSetting('list')" class="col-2 row-12 danger-button close-button">&#10005</button>
    </header>
    
                <div class="col-12 panel">
                    <img id="img-contact-edit" src="" alt="contact image" class="col-6" > 
                    <form class="col-12"><input type="file" id="contact-photo" name="contact-photo"></form>
                </div>

                <div class="col-12 panel">
                    <input id="isperson-edit" type="checkbox" name="isPerson" onchange="isPersonCheck()"> person
                </div>

                <div class="col-12 panel">

                    <div id="person-detail-edit" class="col-12">
                        <div class="col-12">{{--name , ...--}}
                            <span class="lable">Firstname: </span>
                            <input id="firstname-edit" type="text" name="FirstName" value="">
                        </div>
                        <div class="col-12"> 
                            <span class="lable">Lastname: </span>
                            <input id="lastname-edit" type="text" name="LastName" value="">
                        </div>
                        <div class="col-12">
                            <span class="lable">Gender: </span>
                            <input id="gender-male" type="radio" name="Gender" value="male" checked="checked"> Male
                            <input id="gender-female" type="radio" name="Gender" value="female"> Female
                        </div>
                    </div> 
                

                    
                    <div id="notperson-detail-edit" class="col-12">
                        <div class="col-12">
                            <span class="lable">Type: </span>
                            <input id="type-edit" type="text" name="type" value=""> 
                        </div>
                        <div class="col-12"> 
                            <span class="lable">Title: </span>
                            <input id="title-edit" type="text" name="type" value="">
                        </div>
                    </div>

                </div>  

    <div class="col-12 panel">
    <span class="lable">Groups: </span>
        <ol id="groups-edit"></ol>
    </div>

    <div class="col-12 panel">
    <span class="lable">Numbers: </span>
        <ul id="numbers-edit">
        </ul>
        <button type="button" onclick="add('number')">add</button>
    </div>

    <div class="col-12 panel">
    <span class="lable">Emails: </span>
        <ul id="emails-edit">
        </ul>
        <button type="button" onclick="add('email')">add</button>
    </div>

    <div class="col-12 panel">
    <span class="lable">Addresses: </span>
        <ul id="addresses-edit">
        </ul>
        <button type="button" onclick="add('address')">add</button>
    </div>
    
    <div class="col-12 panel">
    <span class="lable">Comments: </span>
        <ul id="comments-edit">
        </ul>
        <button type="button" onclick="add('comment')">add</button>
    </div>

    <footer class="col-12 panel">
      <button class="success-button" onclick="saveContact()">Save</button>
    </footer>
    
</div>