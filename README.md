# VAM Module - Download Manager
##### Simple File downloader module for Virtual Airlines Manager System

! Only administrators can have access to file upload !

-----------------

##### Installation

* Rename original file `download.php` to `backup_download.php`
* Upload `download.php` files to host
* Upload `DownloadManager` folder to host
* Give permission 755 `DownloadManager` folder and file `download.php`

-----------------

##### Configure default downloads folder (Optional)
Edit default directory on `DownloadManager/action.php`

`$dir = '../Downloads/';`

Downloads folder is automatically created 

Example structure folder:
* _Downloads/*_

_Personal sub-drirectory_
* _My-folder-acars_
* _My-folder-help_
* _My-folder-planes_

##### Modify files that can be downloaded (Optional)
Edit default Extension permit on `DownloadManager/upload.php`

`$allowed_extension = array("zip", "rar", "tar.gz", "txt", "doc", "pdf");`

-----------------

##### Enjoy !
Created By [Freedomsim-Virtual-Airlines](https://freedomsim.tk)

-----------------

#### Example on my website:

##### Admin Manage Folder - Create, Rename, Delete, Uploads File
![](https://i.ibb.co/xHFVVvM/page-download-Admin.png)

##### Admin Manage Files - Rename, Download, Delete
![](https://i.ibb.co/h9gLcXL/page-download-Admin-View.png)

##### Users - interface Download Page
![](https://i.ibb.co/N1W0sk2/page-download-Public.png)

##### Users - download File(s)
![](https://i.ibb.co/Mn4Hbqf/page-download-Public-List-Files.png)



