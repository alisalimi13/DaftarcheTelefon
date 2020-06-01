<div class="col-12 row-12">
    <header class="col-12 panel">
       <div class="col-10 row-12 header">Contact Detail</div>
        <button onclick="showSetting('list')" class="col-2 row-12 danger-button close-button">&#10005</button>
    </header>

    <section class="col-12">

        <div class="col-12 panel">
            <img id="img-contact" src="" alt="Image" class="col-6" > 
         </div>

        <div class="col-12 panel">
            <span class="lable">Contact Type:</span>
            <span id="isperson" class="value"></span>
        </div>

        <div class="col-12 panel">

        <div id="person-detail" class="col-12">
            <div class="col-12">
               <span class="lable">First Name:</span>
               <span id="firstname" class="value"></span>
            </div>
            <div class="col-12"> 
                <span class="lable">Last Name:</span>
                <span id="lastname" class="value"></span>
            </div>
            <div class="col-12">
                <span class="lable">Gender:</span>
                <span id="gender" class="value"></span>
            </div>
        </div> 
    

        
        <div id="notperson-detail" class="col-12">
            <div class="col-12">
                <span>Type:</span> <span id="type"></span>
            </div>
            <div class="col-12"> 
                <span>Title:</span> <span id="title"></span>
            </div>
        </div>

        </div>
        

    <div id="groups-panel" class="col-12 panel cursor-point">
    <span class="lable" onclick="display('groups', 'groups-panel')">Groups</span>
    <ol id="groups" class="default-hidden dynamic-list notclickable"></ol>
    </div>

    <div id="numbers-panel" class="col-12 panel cursor-point">    
    <span class="lable" onclick="display('numbers', 'numbers-panel')">Numbers</span>
        <ul id="numbers" class="default-hidden dynamic-list notclickable">
        </ul>
    </div>

    <div id="emails-panel" class="col-12 panel cursor-point">
    <span class="lable" onclick="display('emails', 'emails-panel')">Emails</span>
        <ul id="emails" class="default-hidden dynamic-list notclickable">
        </ul>
    </div>

    <div id="addresses-panel" class="col-12 panel cursor-point">
    <span class="lable" onclick="display('addresses', 'addresses-panel')">Addresses</span>
        <ul id="addresses" class="default-hidden dynamic-list notclickable">
        </ul>
    </div>
    

    <div id="comments-panel" class="col-12 panel cursor-point">
    <span class="lable" onclick="display('comments', 'comments-panel')">Comments</span>
        <ul id="comments" class="default-hidden dynamic-list notclickable">
        </ul>
    </div>

    </section>

    <footer class="col-12 panel">
         <button onclick="ajax({type:3, contact_id:CCID}, showContactEdit)">Edit</button>
         <button class="danger-button" onclick="ajax({type:5, contact_id:CCID}, contactDeleteMessage)">Delete</button>
    </footer>

</div>