<div class="col-12 row-12">
    <header class="col-12 panel">
       <div class="col-10 row-12 header">Group Detail</div>
        <button onclick="showSetting('list')" class="col-2 row-12 danger-button close-button">&#10005</button>
    </header>

    <section class="col-12">
    <div class="col-12 panel">
      <img id="img-group" src="" alt="group image" class="col-6" >  
    </div>

    <div class="col-12 panel">
      <span class="lable">Title: </span> <span id="title-group"></span>
    </div>

    <div class="col-12 panel">
    <span class="lable">People: </span>
      <ol id="list-people-group" class="dynamic-list notclickable">
      </ol>
    <span class="lable">NotPeople: </span> 
      <ol id="list-notpeople-group" class="dynamic-list notclickable">
      </ol>
    </div>
    </section>

    <footer class="col-12 panel">
      <button onclick="ajax({type:4, group_id:CGID}, showGroupEdit)">Edit</button>
      <button class="danger-button" onclick="ajax({type:6, group_id:CGID}, groupDeleteMessage)">Delete</button>
    </footer>

</div>