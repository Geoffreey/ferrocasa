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
if (user_group_id() != 1 && !has_permission('access', 'read_sell_tax_report')) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_read_permission')));
  exit();
}

// LOAD INVOICE MODEL
$invoice_model = registry()->get('loader')->model('invoice');


/**
 *===================
 * INICIO DE TABLA DE DATOS
 *===================
 */

$where_query = "(selling_price.item_tax > 0 OR selling_price.order_tax > 0) AND selling_info.inv_type != 'due_paid' AND selling_info.status = 1 AND selling_info.store_id = " . store_id();

$from = from();
$to = to();
$where_query .= date_range_filter($from, $to);

// tabla de base de datos a utilizar
$table = "(SELECT selling_info.*, selling_price.item_tax, selling_price.order_tax FROM `selling_info` 
  LEFT JOIN `selling_price` ON (selling_info.invoice_id = selling_price.invoice_id) 
  WHERE $where_query) as selling_info";

// Llave principal de la tabla
$primaryKey = 'info_id';

$columns = array(
    array(
      'db' => 'info_id',
      'dt' => 'DT_RowId',
      'formatter' => function( $d, $row ) {
          return 'row_'.$d;
      }
    ),
    array( 
      'db' => 'created_at',   
      'dt' => 'created_at' ,
      'formatter' => function($d, $row) {
        return $row['created_at'];
      }
    ),
    array(
        'db' => 'invoice_id',
        'dt' => 'invoice_id',
        'formatter' => function( $d, $row) {
            $o = $row['invoice_id'];         
            return $o;
        }
    ),
    array(
        'db' => 'item_tax',
        'dt' => 'product_tax',
        'formatter' => function($d, $row) {
            return currency_format($row['item_tax']);
        }
    ),
    array(
        'db' => 'order_tax',
        'dt' => 'order_tax',
        'formatter' => function($d, $row) {
            return currency_format($row['order_tax']);
        }
    ),
    array(
        'db' => 'invoice_id',
        'dt' => 'total_amount',
        'formatter' => function($d, $row) {
            return currency_format($row['item_tax'] + $row['order_tax']);
        }
    ),
);

echo json_encode(
    SSP::simple($request->get, $sql_details, $table, $primaryKey, $columns)
);

/**
 *===================
 * FIN TABLA DE DATOS
 *===================
 */