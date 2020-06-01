<div class="col-12 row-12">
    <header class="col-12 panel">
       <div class="col-10 row-12 header">Group Edit</div>
        <button onclick="showSetting('list')" class="col-2 row-12 danger-button close-button">&#10005</button>
    </header>

    <section class="col-12">
    
    <div class="col-12 panel">
        <img id="img-group-edit" src="" alt="group image" class="col-6"> 
        <form class="col-12"><input type="file" id="group-photo" name="groupe-image"></form> 
    </div>

    <div class="col-12 panel">
    <span class="lable">Title: </span>
        <input id="title-group-edit" type="text" name="groupName" value="">
    </div>

    <div class="col-12 panel">
    <span class="lable">People: </span>
        <ol id="list-people-group-edit">
        </ol>
    <span class="lable">NotPeople: </span>
        <ol id="list-notpeople-group-edit">
        </ol>
    </div>
    </section>

    <footer class="col-12 panel">
             <button class="success-button" onclick="saveGroup()">save</button>
    </footer>

</div>