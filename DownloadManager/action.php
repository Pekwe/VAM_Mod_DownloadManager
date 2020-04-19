<?php
$dir = '../Downloads/';
if(isset($_POST["action"]))
{
    if($_POST["action"] == "fetch")
    {
        $folder = array_filter(glob($dir.'*'), 'is_dir');
        $output = '
            <table class="table table-bordered table-striped">
            <tr>
            <th>Folder Name</th>
            <th>Total File</th>
            <th>Update</th>
            <th>Delete</th>
            <th>Upload File</th>
            <th>View Uploaded File</th>
            </tr>
        ';
        if(count($folder) > 0){
          foreach($folder as $name)
          {
            $output .= '
                <tr>
                    <td>'.str_replace($dir,'', $name).'</td>
                    <td>'.(count(scandir($name)) - 2).'</td>
                    <td><button type="button" name="update" data-name="'.str_replace($dir,'', $name).'" class="update btn btn-warning btn-xs">Update</button></td>
                    <td><button type="button" name="delete" data-name="'.$name.'" class="delete btn btn-danger btn-xs">Delete</button></td>  
                    <td><button type="button" name="upload" data-name="'.$name.'" class="upload btn btn-primary btn-xs">Upload File</button></td>
                    <td><button type="button" name="view_files" data-name="'.$name.'" class="view_files btn btn-default btn-xs">View Files</button></td>
                </tr>
            ';
          }
        }
        else
        {
            $output .= '
                <tr>
                    <td colspan="6"><button type="button" name="create_folder" id="create_folder" class="btn btn-md btn-danger pull-center">No Folder Found: Create New Folder</button></td>
                </tr>
            ';
        }
        $output .= '</table>';
        echo $output;
    }

    if($_POST["action"] == "global_fetch")
    {
        $folder = array_filter(glob($dir.'*'), 'is_dir');
        $output = '
            <table class="table table-bordered table-striped">
                <tr>
                    <th>Folder Name</th>
                    <th>Total File</th>
                    <th>View File</th>
                </tr>
        ';
        if(count($folder) > 0) {
            foreach($folder as $name)
            {
                 $output .= ' <tr>
                              <td>'.str_replace($dir,'', $name).'</td>
                              <td>'.(count(scandir($name)) - 2).'</td>
                              <td><button type="button" name="view_files" data-name="'.$name.'" class="view_files btn btn-default btn-xs">View Files</button></td>								
							 </tr>';
            }
        } else {
            $output .= '
                <tr>
                    <td colspan="6">No Folder Found</td>
                </tr>
            ';
        }
        $output .= '</table>';
        echo $output;
    }
 
    if($_POST["action"] == "create")
    {
        if(!file_exists($_POST["folder_name"])){
            mkdir($dir.$_POST["folder_name"], 0755, true);
            echo 'Folder Created ';
        } else {
            echo 'Folder Already Created';
        }
    }

    if($_POST["action"] == "change")
    {
        if(!file_exists($_POST["folder_name"]))
        {
            rename($dir.$_POST["old_name"], $dir.$_POST["folder_name"]);
            echo 'Folder Name Change';
        }  else {
            echo 'Folder Already Created';
        }
    }
 
    if($_POST["action"] == "delete")
    {
        $files = scandir($_POST["folder_name"]);
        foreach($files as $file)
        {
            if($file === '.' or $file === '..')
            {
                continue;
            } else {
                unlink($_POST["folder_name"] . '/' . $file);
            }
        }
        if(rmdir($_POST["folder_name"]))
        {
            echo 'Folder Deleted';
        }
    }
 
    if($_POST["action"] == "fetch_files")
    {
        $file_data = scandir($_POST["folder_name"]);
        $output = '
        <div class="table-responsive">
          <table class="table table-bordered table-striped">
          <tr>
            <th>File Name</th>
            <th>Download</th>
            <th>Delete</th>
          </tr>
        ';

        foreach($file_data as $file)
        {
          if($file === '.' or $file === '..'){
              continue;
          }else{
                $path = $_POST["folder_name"] . '/' . $file;
                $output .= '
                    <tr>
                     <td contenteditable="true" data-folder_name="'.$_POST["folder_name"].'"  data-file_name = "'.$file.'" class="change_file_name">'.$file.'</td>
                     <td><a target="_blank" href=".'.str_replace('..','', $path).'">DOWNLOAD</a></td>
                     <td><button name="remove_file" class="remove_file btn btn-danger btn-xs" id="'.$path.'">Remove File</button></td>
                    </tr>
                ';
          }
        }
        $output .='</table></div>';
        echo $output;
    }

    if($_POST["action"] == "global_fetch_files")
    {
        $file_data = scandir($_POST["folder_name"]);
        $output = '
            <div class="table-responsive">
              <table class="table table-bordered table-striped">
              <tr>                    
                 <th>File Name</th>
                 <th>Download</th>
              </tr>
        ';

        foreach($file_data as $file)
        {
            if($file === '.' or $file === '..')
            {
                continue;
            } else {
                $path = $_POST["folder_name"] . '/' . $file;
                $output .= '
                    <tr>                     
                     <td contenteditable="false" data-folder_name="'.$_POST["folder_name"].'"  data-file_name = "'.$file.'" class="change_file_name">'.$file.'</td>
                     <td><a target="_blank" href=".'.str_replace('..','', $path).'">DOWNLOAD</a></td>
                    </tr>
                ';
            }
        }
        $output .='</table></div>';
        echo $output;
    }

    if($_POST["action"] == "remove_file")
    {
        if(file_exists($_POST["path"]))
        {
        unlink($_POST["path"]);
        echo 'File Deleted';
        }
    }
 
    if($_POST["action"] == "change_file_name")
    {
        $old_name = $_POST["folder_name"] . '/' . $_POST["old_file_name"];
        $new_name = $_POST["folder_name"] . '/' . $_POST["new_file_name"];
        if(rename($old_name, $new_name))
        {
            echo 'File name change successfully';
        } else {
            echo 'There is an error';
        }
    }
}
