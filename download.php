<?php
	/**
	 * @Project: Virtual Airlines Manager (VAM)
	 * @Author: Alejandro Garcia
	 * @Web http://virtualairlinesmanager.net
	 * Copyright (c) 2013 - 2016 Alejandro Garcia
	 * VAM is licensed under the following license:
	 *   Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International (CC BY-NC-SA 4.0)
	 *   View license.txt in the root, or visit http://creativecommons.org/licenses/by-nc-sa/4.0/
	 */


require('check_login.php');
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="panel-title">
					<?php
						if ($_SESSION["access_administration_panel"] === '1' || $_SESSION["access_administration_panel"] === '1'){
							echo '<button type="button" name="create_folder" id="create_folder" class="btn btn-xs btn-danger pull-right">Create New Folder</button> VA DOWNLOAD MANAGER ';
						}
						else{
							echo 'VA DOWNLOAD';
						}
					?>
				</div>
			</div>
			<div class="ibox-content">
				<div class="table-responsive" id="folder_table">

				</div>
			</div>
		</div>
	</div>
</div>

<div id="folderModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><span id="change_title">Create Folder</span></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Enter Folder Name
                    <input type="text" name="folder_name" id="folder_name" class="form-control"/></p>
                <br/>
                <input type="hidden" name="action" id="action"/>
                <input type="hidden" name="old_name" id="old_name"/>
                <input type="button" name="folder_button" id="folder_button" class="btn btn-info" value="Create"/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="uploadModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"> Upload File</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="post" id="upload_form" enctype='multipart/form-data'>
                    <p>Select File
                        <input type="file" name="upload_file"/></p>
                    <br/>
                    <input type="hidden" name="hidden_folder_name" id="hidden_folder_name"/>
                    <input type="submit" name="upload_button" class="btn btn-info" value="Upload"/>
                </form>
            </div>
            <div class="label-warning text-center">
                <?= 'PHP .INI SERVER CONFIGURATION:<br> post_max_size = ' . ini_get('post_max_size') .'<br>'.' upload_max_filesize = ' . ini_get('upload_max_filesize') .'<br>'.' max_execution_time = ' . ini_get('max_execution_time').' seconds'; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="filelistModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">File List</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" id="file_list">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php if ($_SESSION["access_administration_panel"] === '1' || $_SESSION["access_administration_panel"] === '1'){ ?>
<script>
    $(document).ready(function(){

        load_folder_list();

        function load_folder_list(){
            var action = "fetch";
            $.ajax({
                url:"DownloadManager/action.php",
                method:"POST",
                data:{action:action},
                success:function(data)
                {
                    $('#folder_table').html(data);
                }
            });
        }

        $(document).on('click', '#create_folder', function(){
            $('#action').val("create");
            $('#folder_name').val('');
            $('#folder_button').val('Create');
            $('#folderModal').modal('show');
            $('#old_name').val('');
            $('#change_title').text("Create Folder");
        });

        $(document).on('click', '#folder_button', function(){
            var folder_name = $('#folder_name').val();
            var old_name = $('#old_name').val();
            var action = $('#action').val();
            if(folder_name != '')
            {
                $.ajax({
                    url:"DownloadManager/action.php",
                    method:"POST",
                    data:{folder_name:folder_name, old_name:old_name, action:action},
                    success:function(data)
                    {
                        $('#folderModal').modal('hide');
                        load_folder_list();
                        alert(data);
                    }
                });
            }
            else
            {
                alert("Enter Folder Name");
            }
        });

        $(document).on("click", ".update", function(){
            var folder_name = $(this).data("name");
            $('#old_name').val(folder_name);
            $('#folder_name').val(folder_name);
            $('#action').val("change");
            $('#folderModal').modal("show");
            $('#folder_button').val('Update');
            $('#change_title').text("Change Folder Name");
        });

        $(document).on("click", ".delete", function(){
            var folder_name = $(this).data("name");
            var action = "delete";
            if(confirm("Are you sure you want to remove folder ?"))
            {
                $.ajax({
                    url:"DownloadManager/action.php",
                    method:"POST",
                    data:{folder_name:folder_name, action:action},
                    success:function(data)
                    {
                        load_folder_list();
                        alert(data);
                    }
                });
            }
        });

        $(document).on('click', '.upload', function(){
            var folder_name = $(this).data("name");
            $('#hidden_folder_name').val(folder_name);
            $('#uploadModal').modal('show');
        });

        $('#upload_form').on('submit', function(){
            $.ajax({
                url:"DownloadManager/upload.php",
                method:"POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData:false,
                success: function(data)
                {
                    load_folder_list();
                    alert(data);
                }
            });
        });

        $(document).on('click', '.view_files', function(){
            var folder_name = $(this).data("name");
            var action = "fetch_files";
            $.ajax({
                url:"DownloadManager/action.php",
                method:"POST",
                data:{action:action, folder_name:folder_name},
                success:function(data)
                {
                    $('#file_list').html(data);
                    $('#filelistModal').modal('show');
                }
            });
        });

        $(document).on('click', '.remove_file', function(){
            var path = $(this).attr("id");
            var action = "remove_file";
            if(confirm("Are you sure you want to remove this file?"))
            {
                $.ajax({
                    url:"DownloadManager/action.php",
                    method:"POST",
                    data:{path:path, action:action},
                    success:function(data)
                    {
                        alert(data);
                        $('#filelistModal').modal('hide');
                        load_folder_list();
                    }
                });
            }
        });

        $(document).on('blur', '.change_file_name', function(){
            var folder_name = $(this).data("folder_name");
            var old_file_name = $(this).data("file_name");
            var new_file_name = $(this).text();
            var action = "change_file_name";
            $.ajax({
                url:"DownloadManager/action.php",
                method:"POST",
                data:{folder_name:folder_name, old_file_name:old_file_name, new_file_name:new_file_name, action:action},
                success:function(data)
                {
                    alert(data);
                }
            });
        });

    });
</script>
<?php
}else{
?>
<script>
    $(document).ready(function(){

        load_folder_list();

        function load_folder_list(){
            var action = "global_fetch";
            $.ajax({
                url:"DownloadManager/action.php",
                method:"POST",
                data:{action:action},
                success:function(data)
                {
                    $('#folder_table').html(data);
                }
            });
        }

        $(document).on('click', '.view_files', function(){
            var folder_name = $(this).data("name");
            var action = "global_fetch_files";
            $.ajax({
                url:"DownloadManager/action.php",
                method:"POST",
                data:{action:action, folder_name:folder_name},
                success:function(data)
                {
                    $('#file_list').html(data);
                    $('#filelistModal').modal('show');
                }
            });
        });
    });
</script>
<?php
}
?>
