<?php
require_once "ProjectManagement.php";

$projectName = "StartTuts";
$projectManagement = new ProjectManagement();
$statusResult = $projectManagement->getAllStatus();
?>
<html>
<head>
<title>Trello Like Drag and Drop Cards for Project Management Software</title>
<link rel="stylesheet"
    href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<style>
body {
    font-family: arial;
}
.task-board {
    background: #2c7cbc;
    display: inline-block;
    padding: 12px;
    border-radius: 3px;
    width: 100%;
    white-space: nowrap;
    overflow-x: scroll;
    min-height: 300px;
}

.status-card {
    width: 250px;
    margin-right: 8px;
    background: #e2e4e6;
    border-radius: 3px;
    display: inline-block;
    vertical-align: top;
    font-size: 0.9em;
}

.status-card:last-child {
    margin-right: 0px;
}

.card-header {
    width: 100%;
    padding: 10px 10px 0px 10px;
    box-sizing: border-box;
    border-radius: 3px;
    display: block;
    font-weight: bold;
}

.card-header-text {
    display: block;
}

ul.sortable {
    padding-bottom: 10px;
}

ul.sortable li:last-child {
    margin-bottom: 0px;
}

ul {
    list-style: none;
    margin: 0;
    padding: 0px;
}

.text-row {
    padding: 15px 10px;
    margin: 10px;
    background: #fff;
    box-sizing: border-box;
    border-radius: 3px;
    border-bottom: 1px solid #ccc;
    cursor: pointer;
    font-size: 0.8em;
    white-space: normal;
    line-height: 20px;
}

.ui-sortable-placeholder {
    visibility: inherit !important;
    background: transparent;
    border: #666 2px dashed;
}
</style>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
        <div class="task-board">
            <?php
            foreach ($statusResult as $statusRow) {
                $taskResult = $projectManagement->getProjectTaskByStatus($statusRow["id"], $projectName);
                ?>
                <div class="status-card">
                    <div class="card-header" >
                        <span id="label_card_<?php echo $statusRow["id"]; ?>" name="label_card_<?php echo $statusRow["id"]; ?>" onclick = "run('<?php echo $statusRow['id']; ?>')" class="card-header-text" style="cursor:pointer"><?php echo $statusRow["status_name"]; ?></span>
                        <div id="edit_card_<?php echo $statusRow["id"]; ?>" style="display:none">
                            <input type="text" id="txt_card_<?php echo $statusRow["id"]; ?>" name="txt_card_<?php echo $statusRow["id"]; ?>" />
                            <button name="button"  onClick= "edit('<?php echo $statusRow['id']; ?>')">Salvar</button>
                        </div>
                    </div>
                    <ul class="sortable ui-sortable"
                        id="sort<?php echo $statusRow["id"]; ?>"
                        data-status-id="<?php echo $statusRow["id"]; ?>">
                <?php
                if (! empty($taskResult)) {
                    foreach ($taskResult as $taskRow) {
                        ?>
                
                     <li class="text-row ui-sortable-handle" data-toggle="modal" data-target="#exampleModal"
                            data-task-id="<?php echo $taskRow["id"]; ?>" onclick='setaDadosModal(<?php echo $taskRow["id"]; ?>)'><?php echo $taskRow["title"]; ?></li>
                <?php
                    }
                }
                ?>
                </ul>
                </div>
                <?php
            }
            ?>
        </div>
    <script>
              
        function run(value) { 
            var span = $('#label_card_'+value).text();
            $('#txt_card_'+value).val(span);
            document.getElementById("edit_card_"+value).style.display = "block";
        }  

        function setaDadosModal(valor) {
            var url = 'get-card.php';
            $.ajax({
                url: url+'?card_id='+valor,
                success: function(response){
                    var dados = JSON.parse(response);
                    document.getElementById('title').value = dados.title;
                    document.getElementById('descricao').value = dados.description;
                }
            });

        }

        function edit(value) { 
            var url = 'edit-status.php';
            var span = $('#txt_card_'+value).val();
            $.ajax({
                url: url+'?status_name='+span+'&status_id='+value+'&task_tipo=banner',
                success: function(response){
                    $('#label_card_'+value).text(span)
                    document.getElementById("edit_card_"+value).style.display = "none";
                }
            });
        }

        $( function() {
            var url = 'edit-status.php';
            $('ul[id^="sort"]').sortable({
                connectWith: ".sortable",
                receive: function (e, ui) {
                    var status_id = $(ui.item).parent(".sortable").data("status-id");
                    var task_id = $(ui.item).data("task-id");
                    $.ajax({
                        url: url+'?status_id='+status_id+'&task_id='+task_id+'&task_tipo=card',
                        success: function(response){
                            }
                    });
                    }

            }).disableSelection();
        } );
  </script>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Informações do card</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="cname">Title:</label>
                <input type="text" class="form-control" name="title" id="title" />
            </div>
            <div class="form-group">
                <label for="cname">Description:</label>
                <input type="text" class="form-control" name="descricao" id="descricao" />
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
        </div>
        </div>
    </div>
    </div>
</body>
</html>