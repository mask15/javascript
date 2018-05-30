<?php
require_once 'gifLibraryController.php';
$library = new GifLibrary();
?>

<html>
<head>
    <title>Gif Library</title>
    <style type="text/css">
        body {
            padding: 10px;
        }
        select.gif_list, select.category_list {
            margin-left: 1em;
            padding-left: 10px;
            width: 20%;
            height: 40px;
            text-align-last: center;
        }
        table.result_table, table.tableGifs, table.tableCategories{
            padding: 2em;
            width: 100%;
            border-spacing: 5px;
        }
        .tableGifs input, .tableCategories input, .tableCategories textarea, .tableGifs textarea {
            width: 100%;
            padding: 5px;
        }
        table.result_table td {
            border: transparent;
            border-radius: 3px;
            border-spacing: 0px;
            padding: 10px;
            text-align: center;
            background: #FBF5AC;
        }
        table.result_table tr:first-child td {
            background: #D7DAD7;
            font-weight: bold;
            text-align: center;
        }
        input.link {
            padding: 5px;
            text-align: center;
        }
        button {
            width: 130px;
            height: 35px;
            font-weight: bold;
        }
        div.separator {
            margin-left: 15px;
            position: relative;
            display: inline-block;
        }
        div.categoriesForm, div.gifsForm, div.csvImport {
            display: none;
            margin-top: 5%;
        }

        .input__row{
          margin-top: 10px;
        }
        label:hover {
            cursor: pointer;
        }
        .upload {
            display: none;
        }
        .uploader {
            border: 1px solid #ccc;
            width: 60%;
            position: relative;
            height: 50px;
            display: flex;
        }
        .uploader .input-value{
            width: 100%;
            padding: 10px;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 25px;
            font-family: sans-serif;
            font-size: 16px;
        }
        .uploader label {
            cursor: pointer;
            margin: 0;
            width: 50px;
            height: 50px;
            position: absolute;
            right: 0;
            background: #c3e3fc url('https://cdn4.iconfinder.com/data/icons/ionicons/512/icon-folder-48.png') no-repeat center;
        }

    </style>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            $(".category_list").select2({
                placeholder: "Selecciona una categoría",
                allowClear: true
            }).change(function() {
                $(".categoriesForm").hide();
                $(".csvImport").hide();
                $(".gifsForm").hide();
                $(".gifContainer").show();
                var category_id = $(".category_list").val();
                $.ajax({
                    url: 'gifLibraryController.php',
                    data: {category:category_id},
                    type: 'post',
                    dataType: 'html',
                }).done(function(html) {
                    $(".gifContainer").html(html);
                });

                $.ajax({
                    url: 'gifLibraryController.php',
                    data: {category_select:category_id},
                    type: 'post',
                    dataType: 'json'
                }).done(function(res) {
                    var options = $("#gif_list");
                    options.html('<option selected></option>');
                    $.each(res, function() {
                        options.append(new Option(this.gif_name, this.gif_id));
                    });
                });
            });

            $("#gifCategory").select2({
                placeholder: "Selecciona una categoría",
                allowClear: true,
                width: '100%'
            });

            $(".gif_list").select2({
                placeholder: "Selecciona un gif",
                allowClear: true
            }).change(function() {
                $(".categoriesForm").hide();
                $(".csvImport").hide();
                $(".gifsForm").hide();
                $(".gifContainer").show();
                var gif_id = $(".gif_list").val();
                $.ajax({
                    url: 'gifLibraryController.php',
                    data: {gif_id:gif_id},
                    type: 'post',
                    dataType: 'html',
                }).done(function(html) {
                    $(".gifContainer").html(html);
                });
            });

            $("#addCategory").click(function() {
                $(".gifContainer").hide();
                $(".csvImport").hide();
                $(".gifsForm").hide();
                $(".categoriesForm").show();
                $(".saveCategory").click(function() {
                    var info = {catName:$("#catName").val(), catDesc:$("#catDesc").val()};
                    $.ajax({
                        url: 'gifLibraryController.php',
                        data: info,
                        type: 'post',
                        dataType: 'json'
                    }).done(function($res) {
                        alert('Categoría insertada correctamente');
                        location.reload();
                    });
                });

            });

            $("#importCsv").click(function() {
                $(".gifContainer").hide();
                $(".categoriesForm").hide();
                $(".gifsForm").hide();
                $(".csvImport").show();
                $('#fileImport').change(function(){
                    var inputValue = $(this).val().replace("C:\\fakepath\\", "");
                    $('#inputval').text(inputValue);
                 });
            });

            $("#addGif").click(function() {
                $(".gifContainer").hide();
                $(".csvImport").hide();
                $(".categoriesForm").hide();
                $(".gifsForm").show();
                $(".saveGif").click(function() {
                    var info = {gifName:$("#gifName").val(), gifLink:$("#gifLink").val(), gifCat:$("#gifCategory").val()};
                    $.ajax({
                        url: 'gifLibraryController.php',
                        data: info,
                        type: 'post',
                        dataType: 'json'
                    }).done(function($res) {
                        alert('Gif insertado correctamente');
                        location.reload();
                    });
                });
            });
        });

        function copyLink(e, linkId)
        {
            var id = 'link' + linkId;
            var field = document.getElementById(id)
            field.focus()
            field.setSelectionRange(0, field.value.length)
            link = document.getElementById(id)
            link.select();
            document.execCommand('copy')
        }


    </script>
</head>
<body>
    <span class="text_category_list">Buscar por categoria: </span>
    <select class="category_list" id="category_list">
        <option selected></option>
        <?php
        $categories = $library->getCategories();

        foreach ($categories as $category) {
            echo "<option value='" . $category['category_id'] . "'>" . $category['category_name'] . "</option>";
        }
        ?>
    </select>

    <div class="separator"></div>
    <span class="text_category_list">Buscar gif: </span>
    <select class="gif_list" id="gif_list">
        <option selected></option>
        <?php
        $gifs = $library->getGifs();

        foreach ($gifs as $gif) {
            echo "<option value='" . $gif['gif_id'] . "'>" . $gif['gif_name'] . "</option>";
        }
        ?>
    </select>
    <div class="separator"></div>
    <button id="addCategory">Añadir Categoría</button>
    <div class="separator"></div>
    <button id="addGif">Añadir Gif</button>
    <div class="separator"></div>
    <button id="importCsv">Importar CSV</button>

    <br>

    <div class="gifContainer"></div>

    <div class="categoriesForm">
        <table class="tableCategories">
            <tr>
                <td><span>Nombre: </span></td>
                <td><input type="text" id="catName" placeholder="Nombre de la Categoría"></td>
            </tr>
            <tr>
                <td><span>Descripción: </span></td>
                <td><textarea id="catDesc" rows="10" cols="100"></textarea></td>
            </tr>
            <tr>
                <td colspan="2" align="center"><button class="saveCategory">Guardar</button></td>
            </tr>
        </table>
    </div>

    <div class="gifsForm">
        <table class="tableGifs">
            <tr>
                <td><span>Nombre: </span></td>
                <td><input type="text" id="gifName" placeholder="Nombre del gif"></td>
            </tr>
            <tr>
                <td><span>Url: </span></td>
                <td><input type="text" id="gifLink" placeholder="Url del gif"></td>
            </tr>
            <tr>
                <td><span>Categoría: </span></td>
                <td><select id="gifCategory">
                    <option selected></option>
                    <?php
                    foreach ($categories as $category) {
                        echo "<option value='" . $category['category_id'] . "'>" . $category['category_name'] . "</option>";
                    }
                    ?>
                </select></td>
            </tr>
            <tr>
                <td colspan="2" align="center"><button class="saveGif">Guardar</button></td>
            </tr>
        </table>
    </div>
    <div class="csvImport">
        <div class="input__row">
            <form action="gifLibraryController.php" method="post" enctype="multipart/form-data">
            <span>Importar Fichero CSV</span><br>
            <div class="input__row uploader">
                <div id="inputval" class="input-value"></div>
                <label for="fileImport"></label>
                <input id="fileImport" name="fileCsv" id="fileCsv" class="upload" type="file" accept=".csv">
            </div>
            <button type="submit" name="cargaCsv">Cargar CSV</button>
            </form>
            <fieldset><legend> Formato del CSV </legend><code>gifName;gifLink;categoryName;categoryDesc</code></fieldset>
        </div>
    </div>

</body>
</html>
