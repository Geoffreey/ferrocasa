<?php 
ob_start();
session_start();
include ("../_init.php");

// Comprobar si el usuario inició sesión o no
// Si el usuario no ha iniciado sesión, aparece un mensaje de alerta
if (!is_loggedin()) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_login')));
  exit();
}

// Comprobar, si el usuario tiene permiso de lectura o no
// Si el usuario no tiene permiso de lectura, aparece un mensaje de alerta
if (user_group_id() != 1 && !has_permission('access', 'read_expense_category')) {
  header('HTTP/1.1 422 Unprocessable Entity');
  header('Content-Type: application/json; charset=UTF-8');
  echo json_encode(array('errorMsg' => trans('error_read_permission')));
  exit();
}

// LOAD CATEGORY MODEL
$expense_category_model = registry()->get('loader')->model('expensecategory');

// Validar datos de publicación
function validate_request_data($request) 
{
  // Validate expense_category name
  if (!validateString($request->post['category_name'])) {
    throw new Exception(trans('error_category_name'));
  }

  // Validate expense_category designation
  if (!validateString($request->post['category_slug'])) {
    throw new Exception(trans('error_category_slug'));
  }

  // Sort order validation
  if (!is_numeric($request->post['status'])) {
    throw new Exception(trans('error_status'));
  }

  // Sort order validation
  if (!is_numeric($request->post['sort_order'])) {
    throw new Exception(trans('error_sort_order'));
  }
}

// Check expense_category existance by id
function validate_existance($request, $category_id = 0)
{
  

  // Comprobar la dirección de correo electrónico, ¿si existe o no?
  if (!empty($request->post['category_slug'])) {
    $statement = db()->prepare("SELECT * FROM `expense_categorys` WHERE `category_slug` = ? AND `category_id` != ?");
    $statement->execute(array($request->post['category_slug'], $category_id));
    if ($statement->rowCount() > 0) {
      throw new Exception(trans('error_expense_category_exist'));
    }
  }
}

// Create expense_category
if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'CREATE')
{
  try {

    // Check permission
    if (user_group_id() != 1 && !has_permission('access', 'create_expense_category')) {
      throw new Exception(trans('error_create_permission'));
    }

    // Validar datos de publicación
    validate_request_data($request);
    
    // validar existencia
    validate_existance($request);

    $Hooks->do_action('Before_Create_Expense_Category');

    // Insert new expense_category into databtase
    $category_id = $expense_category_model->addExpenseCategory($request->post);

    // Fetch expense_category info
    $expense_category = $expense_category_model->getExpenseCategory($category_id);

    $Hooks->do_action('After_Create_Expense_Category', $expense_category);

    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_create_success'), 'id' => $category_id, 'expense_category' => $expense_category));
    exit();

  } catch (Exception $e) { 

    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $e->getMessage()));
    exit();
  }
}

// Update expense_category
if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'UPDATE')
{
  try {

    // Comprobar permiso de actualización
    if (user_group_id() != 1 && !has_permission('access', 'update_expense_category')) {
      throw new Exception(trans('error_update_permission'));
    }

    // Validar identificación del producto
    if (empty($request->post['category_id'])) {
      throw new Exception(trans('error_id'));
    }

    $category_id = $request->post['category_id'];

    // Validar datos de publicación
    validate_request_data($request);

    // validar existencia
    validate_existance($request, $category_id);

    $Hooks->do_action('Before_Update_Expense_Category', $request);
    
    // Edit expense_category
    $expense_category = $expense_category_model->editExpenseCategory($category_id, $request->post);

    $Hooks->do_action('After_Update_Expense_Category', $expense_category);

    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_update_success'), 'id' => $category_id));
    exit();

  } catch (Exception $e) { 
    
    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $e->getMessage()));
    exit();
  }
} 

// Delete expense_category
if ($request->server['REQUEST_METHOD'] == 'POST' && isset($request->post['action_type']) && $request->post['action_type'] == 'DELETE') 
{
  try {

    // Comprobar permiso de eliminación
    if (user_group_id() != 1 && !has_permission('access', 'delete_expense_category')) {
      throw new Exception(trans('error_delete_permission'));
    }

    // Validate expense_category id
    if (empty($request->post['category_id'])) {
      throw new Exception(trans('error_id'));
    }

    $category_id = $request->post['category_id'];
    $the_expense_category = $expense_category_model->getExpenseCategory($category_id);

    if (DEMO && $category_id == 1) {
      throw new Exception(trans('error_delete_permission'));
    }

    if (!$the_expense_category) {
      throw new Exception(trans('error_id'));
    }

    $new_category_id = $request->post['new_category_id'];

    // validar la acción de eliminación
    if (empty($request->post['delete_action'])) {
      throw new Exception(trans('error_delete_action'));
    }

    if ($request->post['delete_action'] == 'insert_to' && empty($new_category_id)) {
      throw new Exception(trans('error_new_category_name'));
    }

    $Hooks->do_action('Before_Delete_Expense_Category', $request);

    if ($request->post['delete_action'] == 'insert_to') {
      $expense_category_model->replaceWith($new_category_id, $category_id);
    } 

    // Delete expense_category
    $expense_category = $expense_category_model->deleteExpenseCategory($category_id);

    $Hooks->do_action('After_Delete_Expense_Category', $expense_category);

    header('Content-Type: application/json');
    echo json_encode(array('msg' => trans('text_delete_success'), 'id' => $category_id));
    exit();

  } catch (Exception $e) {

    header('HTTP/1.1 422 Unprocessable Entity');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode(array('errorMsg' => $e->getMessage()));
    exit();
  }
}

// View invoice details
if (isset($request->get['action_type']) AND $request->get['action_type'] == 'INVOICEDETAILS') {

    try {

      $category_id = isset($request->get['category_id']) ? $request->get['category_id'] : null;
      $where_query = "((`selling_info`.`invoice_type` = 'sell' AND `selling_info`.`edit_count` < 1) OR `selling_info`.`invoice_type` = 'sell_edit')  AND `selling_item`.`id` = ?  AND `invoice_status` = ?";
      $from = from() ? from() : date('Y-m-d');
      $to = to() ? to() : date('Y-m-d');
      $where_query .= date_range_filter($from, $to);

      $statement = db()->prepare("SELECT `selling_info`.*, `selling_item`.`id`, SUM(`selling_item`.`item_total_price`) AS `item_total_price`, SUM(`selling_item`.`item_discount`) AS `item_discount` FROM `selling_item` 
          LEFT JOIN `selling_info` ON (`selling_item`.`invoice_id` = `selling_info`.`invoice_id`)
          WHERE $where_query GROUP BY `selling_item`.`invoice_id`");
      $statement->execute(array($category_id, 1));
      $the_invoices = $statement->fetchAll(PDO::FETCH_ASSOC);
      if (!$statement->rowCount() > 0) {
          throw new Exception(trans('error_not_found'));
      }

      $invoices = array();
      $from = date('Y-m-d H:i:s', strtotime($from.' '.'00:00:00')); 
      $to = date('Y-m-d H:i:s', strtotime($to.' '.'23:59:59'));
      foreach ($the_invoices as $invoice) {
        if (!$invoice['ref_invoice_id']) {
          $invoices[$invoice['invoice_id']] = $invoice;
          continue;
        }
        $ref_invoice = get_the_invoice($invoice['ref_invoice_id']);
        if ($from == $to) {
            if (date('Y-m-d', strtotime($ref_invoice['created_at'])) == date('Y-m-d')) {
                $invoices[$ref_invoice['invoice_id']] = $invoice;
            }
        } elseif ((date('Y-m-d H:i:s', strtotime($ref_invoice['created_at'])) >= $from) && (date('Y-m-d H:i:s', strtotime($ref_invoice['created_at'])) <= $to)) {
            $invoices[$ref_invoice['invoice_id']] = $invoice;
        }
      }

      include('template/expense_category_invoice_details.php');
      exit();
        
    } catch (Exception $e) { 

      header('HTTP/1.1 422 Unprocessable Entity');
      header('Content-Type: application/json; charset=UTF-8');
      echo json_encode(array('errorMsg' => $e->getMessage()));
      exit();
    }
}

// Expense_category create form
if (isset($request->get['action_type']) && $request->get['action_type'] == 'CREATE') 
{
  include 'template/expense_category_create_form.php';
  exit();
}

// Expense_category edit form
if (isset($request->get['category_id']) AND isset($request->get['action_type']) && $request->get['action_type'] == 'EDIT') {
  
  // OBTENER INFORMACIÓN DEL PROVEEDOR
  $expense_category = $expense_category_model->getExpenseCategory($request->get['category_id']);
  include 'template/expense_category_edit_form.php';
  exit();
}

// Expense_category delete form
if (isset($request->get['category_id']) AND isset($request->get['action_type']) && $request->get['action_type'] == 'DELETE') {
  
  // OBTENER INFORMACIÓN DEL PROVEEDOR
  $expense_category = $expense_category_model->getExpenseCategory($request->get['category_id']);
  $Hooks->do_action('Before_ExpenseCategory_Delete_Form', $expense_category);
  include 'template/expense_category_delete_form.php';
  $Hooks->do_action('After_ExpenseCategory_Delete_Form', $expense_category);
  exit();
}

/**
 *===================
 * INICIO DE TABLA DE DATOS
 *===================
 */

$Hooks->do_action('Before_Showing_Expense_Category_List');

$where_query = 'is_hide != 1';

// tabla de base de datos a utilizar
$table = "(SELECT * FROM expense_categorys 
  WHERE $where_query GROUP by category_id
  ) as expense_categorys";
 
// Llave principal de la tabla
$primaryKey = 'category_id';

$columns = array(
  array(
      'db' => 'category_id',
      'dt' => 'DT_RowId',
      'formatter' => function( $d, $row ) {
          return 'row_'.$d;
      }
  ),
  array( 'db' => 'category_id', 'dt' => 'category_id' ),
  array( 'db' => 'parent_id', 'dt' => 'parent_id' ),
  array( 
    'db' => 'category_name',   
    'dt' => 'category_name',
    'formatter' => function($d, $row) {
      $name = '';
      $parent = get_the_expense_category($row['parent_id']);
      if (isset($parent['category_name'])) {
        $name = $parent['category_name'] .  ' > ';
      }
      return $name . $row['category_name'];
    }
  ),
  array( 'db' => 'category_slug', 'dt' => 'category_slug' ),
  array( 
    'db' => 'category_id',   
    'dt' => 'total_item',
    'formatter' => function($d, $row) use($expense_category_model) {
      return $expense_category_model->totalItem($row['category_id']);
    }
  ),
  array( 
    'db' => 'sort_order',   
    'dt' => 'sort_order',
    'formatter' => function($d, $row) {
      return $row['sort_order'];
    }
  ),
  array( 
    'db' => 'status',   
    'dt' => 'status',
    'formatter' => function($d, $row) {
      return $row['status'] 
        ? '<span class="label label-success">'.trans('text_active').'</span>' 
        : '<span class="label label-warning">' .trans('text_inactive').'</span>';
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
    'db'        => 'category_id',
    'dt'        => 'btn_edit',
    'formatter' => function( $d, $row ) {
      return '<button id="edit-expense_category" class="btn btn-sm btn-block btn-primary" type="button" title="'.trans('button_edit').'"><i class="fa fa-fw fa-pencil"></i></button>';
    }
  ),
  array(
    'db'        => 'category_id',
    'dt'        => 'btn_delete',
    'formatter' => function( $d, $row ) {
      return '<button id="delete-expense_category" class="btn btn-sm btn-block btn-danger" type="button" title="'.trans('button_delete').'"><i class="fa fa-fw fa-trash"></i></button>';
    }
  )
); 

echo json_encode(
    SSP::simple($request->get, $sql_details, $table, $primaryKey, $columns)
);

$Hooks->do_action('After_Showing_Expense_Category_List');

/**
 *===================
 * FIN TABLA DE DATOS
 *===================
 */