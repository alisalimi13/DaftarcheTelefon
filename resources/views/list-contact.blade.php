<div class="col-12 row-12">
    <header class="col-12 panel">
       <span class="header">Contacts List</span>
    </header>
    

    <div class="col-12 panel">
        <div class="col-6">
            <input class="col-12" type="text" id="searchText" name="search" placeholder="Search" onkeyup="Search()">
        </div>
        <div class="col-6">
            <span class="lable">Search By</span> 
            <select id="searchBy" name='searchBy' > 
                <option value="1">name</option>
                <option value="2">number</option>
            </select>
        </div>
    </div>

    <div class="col-12 panel">
    <span class="lable">People</span>
        <ol id="list-contact-person" class="dynamic-list">
        </ol>
    <span class="lable">NotPeople</span>
        <ol id="list-contact-notperson" class="dynamic-list">
        </ol>
    </div>

    <footer class="col-12 panel">
       <button onclick="addContact(); showSetting('detail');">Add contact</button>
    </footer>

</div>