<?php 
ob_start();
session_start();
include ("../_init.php");

// Check, if your logged in or not
// If user is not logged in then return an alert message
if (!is_loggedin()) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_login')));
  exit();
}

// Comprobar, si el usuario tiene permiso de lectura o no
// Si el usuario no tiene permiso de lectura, devuelva el error
if (user_group_id() != 1 && !has_permission('access', 'read_bank_transfer')) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_read_permission')));
  exit();
}

$store_id = store_id();
$banking_model = registry()->get('loader')->model('banking');


/**
 *===================
 * INICIO DE TABLA DE DATOS
 *===================
 */

$Hooks->do_action('Before_Showing_Bank_Transfer_list');

$where_query = "bank_transaction_info.store_id = $store_id";

// Filtering
$from = from();
$to = to();
$from = $from ? $from : date('Y-m-d');
$to = $to ? $to : date('Y-m-d');
if (($from && ($to == false)) || ($from == $to)) {
  $day = date('d', strtotime($from));
  $month = date('m', strtotime($from));
  $year = date('Y', strtotime($from));
  $where_query .= " AND DAY(`bank_transaction_info`.`created_at`) = '{$day}'";
  $where_query .= " AND MONTH(`bank_transaction_info`.`created_at`) = '{$month}'";
  $where_query .= " AND YEAR(`bank_transaction_info`.`created_at`) = '{$year}'";
} else {
  $from = date('Y-m-d H:i:s', strtotime($from.' '. '00:00:00')); 
  $to = date('Y-m-d H:i:s', strtotime($to.' '. '23:59:59'));
  $where_query .= " AND bank_transaction_info.created_at >= '{$from}' AND bank_transaction_info.created_at <= '{$to}'";
}

$where_query .= " AND bank_transaction_info.transaction_type IN ('transfer')";

// tabla de base de datos a utilizar
$table = "(SELECT bank_transaction_info.*, bank_transaction_price.amount 
  FROM bank_transaction_info 
  JOIN bank_transaction_price ON bank_transaction_info.info_id = bank_transaction_price.info_id
  WHERE $where_query) as bank_transaction_info";
 
// Llave principal de la tabla
$primaryKey = 'info_id';

// Indexes
$columns = array(
    array(
        'db' => 'ref_no',
        'dt' => 'DT_RowId',
        'formatter' => function( $d, $row ) {
            return 'row_'.$d;
        }
    ),
    array( 'db' => 'ref_no', 'dt' => 'id' ),
    array( 
      'db' => 'created_at',   
      'dt' => 'created_at' ,
      'formatter' => function($d, $row) {
          return $row['created_at'];
      }
    ),
    array( 
      'db' => 'from_account_id',   
      'dt' => 'from_account' ,
      'formatter' => function($d, $row) {
          return get_the_bank_account($row['from_account_id'], 'account_name');
      }
    ),
    array( 
      'db' => 'account_id',   
      'dt' => 'account' ,
      'formatter' => function($d, $row) {
          return get_the_bank_account($row['account_id'], 'account_name');
      }
    ),
    array( 
      'db' => 'amount',   
      'dt' => 'amount' ,
      'formatter' => function($d, $row) {
          return currency_format($row['amount']);
      }
    ),
);

echo json_encode(
    SSP::simple($request->get, $sql_details, $table, $primaryKey, $columns)
);

$Hooks->do_action('After_Showing_Bank_Transfer_list');

/**
 *===================
 * FIN TABLA DE DATOS
 *===================
 */
 