<?php 
ob_start();
session_start();
include ("../_init.php");

// Comprobar si el usuario inició sesión o no
// If user is not logged in then return an alert message
if (!is_loggedin()) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_login')));
  exit();
}

// Comprobar, si el usuario tiene permiso de lectura o no
// If user have not reading permission return an alert message
if (user_group_id() != 1 && !has_permission('access', 'restore')) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_read_permission')));
  exit();
}

if (isset($request->get['action_type']) && $request->get['action_type'] == 'IMPORTFILE')
{
	$Hooks->do_action('Before_Database_Restore');

	$json = array();

	// Check permission
	if (user_group_id() != 1 && !has_permission('access', 'restore') || DEMO) {
	  $json['error'] = trans('error_restore_permission');
	}

	if (isset($_FILES['restore']['tmp_name']) && is_uploaded_file($_FILES['restore']['tmp_name'])) {
		// Check for file .sql
		$file_parts = pathinfo($_FILES['restore']['name']);
		if (!in_array($file_parts['extension'], array('sql'))) {
			$json['error'] = trans('error_invalid_file');
		}
		array_map('unlink', glob(DIR_STORAGE.DIRECTORY_SEPARATOR.'backups'.DIRECTORY_SEPARATOR.'bac*.*')); // Delete all temp files.
		$filename = tempnam(DIR_STORAGE.DIRECTORY_SEPARATOR.'backups', 'bac');
		move_uploaded_file($_FILES['restore']['tmp_name'], $filename);
	} elseif (isset($request->get['restore'])) {
		$filename = html_entity_decode($request->get['restore'], ENT_QUOTES, 'UTF-8');
	} else {
		$filename = '';
	}

	if (!is_file($filename)) {
		$json['error'] = trans('error_file');
	}	

	if (isset($request->get['position'])) {
		$position = $request->get['position'];
	} else {
		$position = 0; 	
	}
			
	if (!$json) {

		// We set $i so we can batch execute the queries rather than do them all at once.
		$i = 0;
		$start = false;
		
		$handle = fopen($filename, 'r');

		fseek($handle, $position, SEEK_SET);
		
		while (!feof($handle) && ($i < 100)) {
			$position = ftell($handle);

			$line = ltrim(fgets($handle, 1000000));
			
			if (substr($line, 0, 14) == 'TRUNCATE TABLE' || substr($line, 0, 11) == 'INSERT INTO') {
				$sql = '';
				
				$start = true;
			}

			if ($start) {
				$sql .= $line;
			}
			
			if ($start && substr($line, -2) == ";\n") {
				$statement = db()->prepare(substr($sql, 0, strlen($sql) -2));
				$statement->execute();
				
				$start = false;
			}
				
			$i++;
		}

		$position = ftell($handle);

		$size = filesize($filename);

		$json['total'] = round(($position / $size) * 100);

		if ($position && !feof($handle)) {
			
			$json['next'] = '../_inc/restore.php?restore=' . $filename . '&position=' . $position . '&action_type=IMPORTFILE';
		
			fclose($handle);

		} else {

			fclose($handle);
			
			unlink($filename);

			$json['success'] = trans('text_restore_completed');
		}
	}

	$Hooks->do_action('After_Database_Restore');

    header('Content-Type: application/json');
    echo json_encode($json);
}