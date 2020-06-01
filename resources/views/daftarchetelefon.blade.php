<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>DaftarcheTelefon</title>
    <link rel="stylesheet" href="/css/DaftarcheTelefon.css">
</head>
<body class="container" onload="onLoad()">
    <!-- Header -->
    <header class="col-d-12 row-d-1 col-t-12 row-t-1 col-p-12 row-p-1 panel">
        <div class="header" style="text-align: center;">DaftarcheTelefon</div>
    </header>

    <!-- Setting -->
    <section id="setting" class="setting col-d-4 row-d-10 col-t-6 row-t-10 col-p-12 row-p-10 container">
        
        <ul class="col-d-12 row-d-12 col-t-12 row-t-12 col-p-12 row-p-12 panel button-list">
            <span class="header">Setting</span>
            <li>
            <ul>
                <span class="lable">Main</span>
                <li><button class="list-button" onclick="ajax({type:1},getContacts);showSetting('list');">contact</button></li>
                <li><button class="list-button" onclick="ajax({type:2},getGroups);showSetting('list');">group</button></li>
            </ul>
            </li>

            <li>
            <ul>
                <span class="lable">Export</span>
                <li><button class="list-button" onclick="exportCSV()">Export CSV</button></li>
                <li><button class="list-button" onclick="exportJSON()">Export JSON</button></li>
            </ul>
            </li>

            <li>
            <ul>
                <span class="lable">Import</span>
                <li><button class="list-button" onclick="importJSON()">Import JSON</button></li>
                <li><form><input type="file" name="import" id="import-json"></li></form>
            </ul>
            </li>
        </ul>
    </section>

    <!-- List -->
    <section id="list" class="list col-d-4 row-d-10 col-t-6 row-t-10 col-p-12 row-p-10 container">
        <section id="list-contact" class="list-contact col-d-12 row-d-12 col-t-12 row-t-12 col-p-12 row-p-12">
            @include('list-contact')
        </section>
        <section id="list-group" class="list-group col-d-12 row-d-12 col-t-12 row-t-12 col-p-12 row-p-12">
            @include('list-group')
        </section>
    </section>

    <!-- Detail -->
    <section id="detail" class="detail col-d-4 row-d-10 col-t-6 row-t-10 col-p-12 row-p-10 container">
        <section id="detail-contact" class="detail-contact col-d-12 row-d-12 col-t-12 row-t-12 col-p-12 row-p-12">
            @include('detail-contact')
        </section>
        <section id="detail-group" class="detail-group col-d-12 row-d-12 col-t-12 row-t-12 col-p-12 row-p-12">
            @include('detail-group')
        </section>
        <section id="edit-contact" class="edit-contact col-d-12 row-d-12 col-t-12 row-t-12 col-p-12 row-p-12">
            @include('edit-contact')
        </section>
        <section id="edit-group" class="edit-group col-d-12 row-d-12 col-t-12 row-t-12 col-p-12 row-p-12">
            @include('edit-group')
        </section>
    </section>

    <!-- Footer -->
    <footer class="footer col-d-12 row-d-1 col-t-12 row-t-1 col-p-12 row-p-1 panel">
        <button class="col-3" onclick="showSetting('setting')">Setting</button>
        <div class="col-9" id="footer-message-box"></div>
    </footer>

    <div id="loader-back" class="loader-back default-hidden"><div id="loader" class="progress-line"></div></div>

<script src="/js/daftarchetelefon.js"></script>
</body>
</html>