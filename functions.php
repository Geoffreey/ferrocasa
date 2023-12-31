<?php
global $Hooks;

/*
 * Devolución de llamada automática para Hook
 *
 * *Configuración
 *
 * config.php HOOK = 1
 * config.php Log = 1
 */
function hook_action_auto_callback($args) 
{
	$for =  str_replace(array('After_','Before_', '_'), array('','',' '), $args['for']);
	unset($args['for']);
	$data = isset($args[0]) ? json_encode($args[0]) : '';
	$msg = date('Y-m-d G:i:s') . ' - ' . $for . ': ' . $data . ' by ' . get_the_user(user_id(),'username');

	$handle = fopen(DIR_STORAGE.'activity-logs/'.date('Y-m-d').'.txt', 'a');
	fwrite($handle, $msg . "\n");
}

/*
 * Conexión en una acción específica
 */
// $Hooks->add_action('After_Update_Product','after_update_product');
// function after_update_product($p_id) {
// 	write_file(DIR_STORAGE.'hook_test.txt', 'The Product ID: ' . $p_id . ' Updated.');
// }



/* 
Ganchos disponibles
------------------------------------------------
$Hooks->do_action('Before_Bank_Withdraw', $ref_no);
$Hooks->do_action('After_Bank_Withdraw', array('type' => 'bank_withdraw', 'id' => $ref_no, 'amount' => $withdraw_amount));
$Hooks->do_action('Before_Bank_Deposit', $ref_no);
$Hooks->do_action('After_Bank_Deposit', array('type' => 'bank_deposit'));
$Hooks->do_action('Before_Bank_Transfer', $ref_no);
$Hooks->do_action('After_Bank_Transfer', array('type' => 'bank_transfer', 'id' => $ref_no, 'amount' => $transfer_amount));
$Hooks->do_action('Before_Showing_Bank_Transaction_list');
$Hooks->do_action('After_Showing_Bank_Transaction_list');
$Hooks->do_action('Before_Create_Bank_Account');
$Hooks->do_action('After_Create_Bank_Account', $bank_account);
$Hooks->do_action('Before_Update_Bank_Account', $request);
$Hooks->do_action('After_Update_Bank_Account', $bank_account);
$Hooks->do_action('Before_Delete_Bank_Account', $request);
$Hooks->do_action('After_Delete_Bank_Account', $bank_account);
$Hooks->do_action('Before_Bank_Account_Delete_Form', $account);
$Hooks->do_action('After_Bank_Account_Delete_Form', $account);
$Hooks->do_action('Before_Showing_Bank_Account_List');
$Hooks->do_action('After_Showing_Bank_Account_List');
$Hooks->do_action('Before_Showing_Bank_AccountSheet');
$Hooks->do_action('After_Showing_Bank_AccountSheet');
$Hooks->do_action('Before_Showing_Bank_Transfer_list');
$Hooks->do_action('After_Showing_Bank_Transfer_list');
$Hooks->do_action('Before_Create_Box');
$Hooks->do_action('After_Create_Box', $box);
$Hooks->do_action('Before_Update_Box', $request);
$Hooks->do_action('After_Update_Box', $box);
$Hooks->do_action('Before_Delete_Box', $request);
$Hooks->do_action('After_Delete_Box', $box);
$Hooks->do_action('Before_Box_Delete_Form', $box);
$Hooks->do_action('After_Box_Delete_Form', $box);
$Hooks->do_action('Before_Showing_Box_List');
$Hooks->do_action('After_Showing_Box_List');
$Hooks->do_action('Before_Create_Brand', $request);
$Hooks->do_action('After_Create_Brand', $brand);
$Hooks->do_action('Before_Update_Brand', $request);
$Hooks->do_action('After_Update_Brand', $brand);
$Hooks->do_action('Before_Delete_Brand', $request);
$Hooks->do_action('After_Delete_Brand', $brand);
$Hooks->do_action('Before_Brand_Create_Form');
$Hooks->do_action('After_Brand_Create_Form');
$Hooks->do_action('Before_Brand_Edit_Form', $brand);
$Hooks->do_action('After_Brand_Edit_Form', $brand);
$Hooks->do_action('Before_Brand_Delete_Form');
$Hooks->do_action('Before_Brand_Delete_Form');
$Hooks->do_action('Before_Showing_Brand_List');
$Hooks->do_action('After_Showing_Brand_List');
$Hooks->do_action('Before_Create_Category', $request);
$Hooks->do_action('After_Create_Category', $category);
$Hooks->do_action('Before_Update_Category', $request);
$Hooks->do_action('After_Update_Category', $category);
$Hooks->do_action('Before_Delete_Category', $request);
$Hooks->do_action('After_Delete_Category', $category);
$Hooks->do_action('Before_Category_Delete_Form', $category);
$Hooks->do_action('After_Category_Delete_Form', $category);
$Hooks->do_action('Before_Showing_Category_List');
$Hooks->do_action('After_Showing_Category_List');
$Hooks->do_action('Before_Create_Currency', $request);
$Hooks->do_action('After_Create_Currency', $currency);
$Hooks->do_action('Before_Update_Currency', $request);
$Hooks->do_action('After_Update_Currency', $currency);
$Hooks->do_action('Before_Delete_Currency', $request);
$Hooks->do_action('After_Delete_Currency', $currency);
$Hooks->do_action('Before_Showing_Currency_List');
$Hooks->do_action('After_Showing_Currency_List');
$Hooks->do_action('Before_Create_Customer', $request);
$Hooks->do_action('After_Create_Customer', $customer);
$Hooks->do_action('Before_Update_Customer', $request);
$Hooks->do_action('After_Update_Customer', $customer_id);
$Hooks->do_action('Before_Delete_Customer', $request);
$Hooks->do_action('After_Delete_Customer', $customer);
$Hooks->do_action('Before_Customer_Delete_Form', $customer);
$Hooks->do_action('After_Customer_Delete_Form', $customer);
$Hooks->do_action('Before_Showing_Customer_List');
$Hooks->do_action('After_Showing_Customer_List');
$Hooks->do_action('Before_Showing_Customer_Profile');
$Hooks->do_action('After_Showing_Customer_Profile');
$Hooks->do_action('Before_Add_Expense');
$Hooks->do_action('After_Add_Expense', $id);
$Hooks->do_action('Before_Update_Expense', $request);
$Hooks->do_action('After_Update_Expense', $id);
$Hooks->do_action('Before_Delete_Expense', $request);
$Hooks->do_action('After_Delete_Expense', $id);
$Hooks->do_action('Before_Showing_Expense_List');
$Hooks->do_action('After_Showing_Expense_List');
$Hooks->do_action('Before_Create_Expense_Category');
$Hooks->do_action('After_Create_Expense_Category', $expense_category);
$Hooks->do_action('Before_Update_Expense_Category', $request);
$Hooks->do_action('After_Update_Expense_Category', $expense_category);
$Hooks->do_action('Before_Delete_Expense_Category', $request);
$Hooks->do_action('After_Delete_Expense_Category', $expense_category);
$Hooks->do_action('Before_ExpenseCategory_Delete_Form', $expense_category);
$Hooks->do_action('After_ExpenseCategory_Delete_Form', $expense_category);
$Hooks->do_action('Before_Showing_Expense_Category_List');
$Hooks->do_action('After_Showing_Expense_Category_List');
$Hooks->do_action('Before_Create_Giftcard', $request);
$Hooks->do_action('After_Create_Giftcard', $giftcard);
$Hooks->do_action('Before_Update_Giftcard', $request);
$Hooks->do_action('After_Update_Giftcard', $giftcard);
$Hooks->do_action('Before_Delete_Giftcard', $request);
$Hooks->do_action('After_Delete_Giftcard', $giftcard);
$Hooks->do_action('Before_Giftcard_Topup', $request);
$Hooks->do_action('After_Delete_Giftcard', $id);
$Hooks->do_action('Before_Giftcard_Delete_Form', $giftcard);
$Hooks->do_action('After_Giftcard_Delete_Form', $giftcard);
$Hooks->do_action('Before_Showing_Giftcard_List');
$Hooks->do_action('After_Showing_Giftcard_List');
$Hooks->do_action('Before_Delete_Giftcard_Topup', $giftcard);
$Hooks->do_action('After_Delete_Giftcard_Topup');
$Hooks->do_action('Before_Showing_Giftcard_Topup_List');
$Hooks->do_action('After_Showing_Giftcard_Topup_List');
$Hooks->do_action('Before_Delete_Holding_Order', $request);
$Hooks->do_action('After_Delete_Holding_Order', $ref_no);
$Hooks->do_action('Before_Add_Order_On_Hold', $request);
$Hooks->do_action('After_Add_Order_On_Hold', $id);
$Hooks->do_action('Before_Edit_Hold_order', $request);
$Hooks->do_action('After_Edit_Hold_order', $order);
$Hooks->do_action('Before_Create_Income_Source');
$Hooks->do_action('After_Create_Income_Source', $income_source);
$Hooks->do_action('Before_Update_Income_Source', $request);
$Hooks->do_action('After_Update_Income_Source', $income_source);
$Hooks->do_action('Before_Delete_Income_Source', $request);
$Hooks->do_action('After_Delete_Income_Source', $income_source);
$Hooks->do_action('Before_Showing_Income_Source_List');
$Hooks->do_action('After_Showing_Income_Source_List');
$Hooks->do_action('Before_Installment_payment', $request);
$Hooks->do_action('After_Installment_payment', $id);
$Hooks->do_action('Before_Delete_Installment', $request);
$Hooks->do_action('After_Delete_Installment', $request);
$Hooks->do_action('Before_Showing_Installment_List');
$Hooks->do_action('After_Showing_Installment_List');
$Hooks->do_action('Before_Delete_Invoice', $request);
$Hooks->do_action('Before_Delete_Invoice', $request);
$Hooks->do_action('Before_Update_Invoice_Info', $invoice_id);
$Hooks->do_action('After_Update_Invoice_Info', $invoice_id);
$Hooks->do_action('Before_Showing_Invoice_List');
$Hooks->do_action('After_Showing_Invoice_List');
$Hooks->do_action('Before_Take_Loan', $request);
$Hooks->do_action('After_Take_Loan', $loan);
$Hooks->do_action('Before_Update_Loan', $request);
$Hooks->do_action('After_Update_Loan', $loan);
$Hooks->do_action('Before_Delete_Loan', $request);
$Hooks->do_action('After_Delete_Loan', $loan);
$Hooks->do_action('Before_Loan_Pay');
$Hooks->do_action('After_Loan_Paid', $loan);
$Hooks->do_action('Before_Loan_Delete_Form', $loan);
$Hooks->do_action('After_Loan_Delete_Form', $loan);
$Hooks->do_action('Before_Showing_Loan_List');
$Hooks->do_action('After_Showing_Loan_List');
$Hooks->do_action('Before_Showing_Loan_Payment_List');
$Hooks->do_action('After_Showing_Loan_Payment_List');
$Hooks->do_action('Before_Payment', $request);
$Hooks->do_action('After_Payment', $request);
$Hooks->do_action('Before_Place_POS_Order', $request);
$Hooks->do_action('Before_Place_POS_Order', $invoice_info);
$Hooks->do_action('Before_Create_PMethod', $request);
$Hooks->do_action('After_Create_PMethod', $pmethod);
$Hooks->do_action('Before_Update_PMethod', $request);
$Hooks->do_action('After_Update_PMethod', $pmethod);
$Hooks->do_action('Before_Delete_PMethod', $request);
$Hooks->do_action('After_Delete_PMethod', $pmethod);
$Hooks->do_action('Before_PMethod_Delete_Form', $pmethod);
$Hooks->do_action('After_PMethod_Delete_Form', $pmethod);
$Hooks->do_action('Before_Showing_PMethod_List');
$Hooks->do_action('After_Showing_PMethod_List');
$Hooks->do_action('Before_Add_Printer');
$Hooks->do_action('After_Add_Printer', $printer);
$Hooks->do_action('Before_Update_Printer', $request);
$Hooks->do_action('After_Update_Printer', $printer);
$Hooks->do_action('Before_Delete_printer', $request);
$Hooks->do_action('After_Delete_printer', $printer);
$Hooks->do_action('Before_Showing_printer_List');
$Hooks->do_action('After_Showing_printer_List');
$Hooks->do_action('Before_Create_Product', $request);
$Hooks->do_action('After_Create_Product', $product);
$Hooks->do_action('Before_Update_Product', $p_id);
$Hooks->do_action('After_Update_Product', $p_id);
$Hooks->do_action('Before_Delete_Product', $request);
$Hooks->do_action('After_Delete_Product', $product);
$Hooks->do_action('Before_Showing_Product_List');
$Hooks->do_action('After_Showing_Product_List');
$Hooks->do_action('After_Product_Bulk_Action', $action);
$Hooks->do_action('After_Product_Bulk_Action', $action);
$Hooks->do_action('Before_Create_Purchase_Invoice', $request);
$Hooks->do_action('After_Create_Purchase_Invoice', $invoice_id);
$Hooks->do_action('Before_Delete_Purchase_Invoice', $request);
$Hooks->do_action('After_Delete_Purchase_Invoice', $invoice_id);
$Hooks->do_action('Before_Update_Purchase_Invoice', $invoice_id);
$Hooks->do_action('After_Update_Purchase_Invoice', $invoice_id);
$Hooks->do_action('Before_Showing_Purchase_Invoice_List');
$Hooks->do_action('After_Showing_Purchase_Invoice_List');
$Hooks->do_action('Before_Purchase_Payment', $request);
$Hooks->do_action('After_Purchase_Payment', $request);
$Hooks->do_action('Before_Purchase_Return', $request);
$Hooks->do_action('After_Purchase_Return', $id);
$Hooks->do_action('Before_Showing_Purchase_Return_List');
$Hooks->do_action('Before_Showing_Purchase_Return_List');
$Hooks->do_action('Before_Showing_Purchase_Transactions_List');
$Hooks->do_action('After_Showing_Purchase_Transaction_List');
$Hooks->do_action('Before_Create_Quotation', $request);
$Hooks->do_action('After_Create_Quotation', $quotation_info);
$Hooks->do_action('Before_Update_Quotation', $request);
$Hooks->do_action('After_Update_Quotation', $quotation_info);
$Hooks->do_action('Before_Delete_Quotation', $request);
$Hooks->do_action('After_Delete_Quotation', $request);
$Hooks->do_action('Before_Showing_Quotation_List');
$Hooks->do_action('After_Showing_Quotation_List');
$Hooks->do_action('Before_Showing_Collection_Report');
$Hooks->do_action('Before_Showing_Loss_List');
$Hooks->do_action('After_Showing_Loss_List');
$Hooks->do_action('Before_Showing_purchase_Tax_Report');
$Hooks->do_action('After_Showing_purchase_Tax_Report');
$Hooks->do_action('Before_Database_Restore');
$Hooks->do_action('After_Database_Restore');
$Hooks->do_action('Before_Sell_Return', $request);
$Hooks->do_action('After_Sell_Return', $request);
$Hooks->do_action('Before_Showing_Sell_Return_List');
$Hooks->do_action('After_Showing_Sell_Return_List');
$Hooks->do_action('Before_Showing_Transactions_List');
$Hooks->do_action('After_Showing_Transactions_List');
$Hooks->do_action('Before_Send_Email', $request);
$Hooks->do_action('After_Send_Email', $request);
$Hooks->do_action('Before_Showinig_SMS_Report');
$Hooks->do_action('After_Showinig_SMS_Report');
$Hooks->do_action('Before_Update_SMS_Setting', $request);
$Hooks->do_action('After_Update_SMS_Setting', $request);
$Hooks->do_action('Before_Create_Store', $request);
$Hooks->do_action('After_Create_Store', $store_id);
$Hooks->do_action('Before_Update_Store', $request);
$Hooks->do_action('After_Update_Store', $the_store);
$Hooks->do_action('Before_Delete_Store', $request);
$Hooks->do_action('After_Delete_Store', $the_store);
$Hooks->do_action('Before_Store_Delete_Form', $store_info);
$Hooks->do_action('After_Store_Delete_Form', $store_info);
$Hooks->do_action('Before_Showing_Store_List');
$Hooks->do_action('After_Showing_Store_List');
$Hooks->do_action('Before_Create_Supplier', $request);
$Hooks->do_action('After_Create_Supplier', $supplier);
$Hooks->do_action('Before_Update_Supplier', $request);
$Hooks->do_action('After_Update_Supplier', $supplier);
$Hooks->do_action('Before_Delete_Supplier', $request);
$Hooks->do_action('After_Delete_Supplier', $supplier);
$Hooks->do_action('Before_Showing_Supplier_List');
$Hooks->do_action('After_Showing_Supplier_List');
$Hooks->do_action('Before_Showing_Supplier_Profile');
$Hooks->do_action('After_Showing_Supplier_Profile');
$Hooks->do_action('Before_Create_Taxrate', $request);
$Hooks->do_action('After_Create_Taxrate', $taxrate);
$Hooks->do_action('Before_Update_Taxrate', $request);
$Hooks->do_action('After_Update_Taxrate', $taxrate);
$Hooks->do_action('Before_Delete_Taxrate', $request);
$Hooks->do_action('After_Delete_Taxrate', $taxrate);
$Hooks->do_action('Before_Showing_Taxrate_List');
$Hooks->do_action('After_Showing_Taxrate_List');
$Hooks->do_action('Before_Stock_Transfer', $request);
$Hooks->do_action('After_Stock_Transfer', $invoice_id);
$Hooks->do_action('Before_Update_Stock_Transfer', $id);
$Hooks->do_action('After_Update_Stock_Transfer', $id);
$Hooks->do_action('Before_Showing_Transfer_List');
$Hooks->do_action('Aftere_Showing_Transfer_List');
$Hooks->do_action('Before_Create_Unit', $request);
$Hooks->do_action('After_Create_Unit', $unit);
$Hooks->do_action('Before_Update_Unit', $request);
$Hooks->do_action('After_Update_Unit', $unit);
$Hooks->do_action('Before_Delete_Unit', $request);
$Hooks->do_action('After_Delete_Unit', $unit);
$Hooks->do_action('Before_Showing_Unit_List');
$Hooks->do_action('After_Showing_Unit_List');
$Hooks->do_action('Before_Upload_Favicon', $request);
$Hooks->do_action('After_Upload_Favicon', $request);
$Hooks->do_action('Before_Upload_Logo', $request);
$Hooks->do_action('After_Upload_Logo', $request);
$Hooks->do_action('Before_Create_User', $request);
$Hooks->do_action('After_Create_User', $the_user);
$Hooks->do_action('Before_Update_User', $request);
$Hooks->do_action('After_Update_User', $the_user);
$Hooks->do_action('Before_Delete_User', $request);
$Hooks->do_action('After_Delete_User', $the_user);
$Hooks->do_action('Before_Showing_User_List');
$Hooks->do_action('After_Showing_User_List');
$Hooks->do_action('Before_Create_Usergroup', $request);
$Hooks->do_action('After_Create_Usergroup', $usergroup);
$Hooks->do_action('Before_Update_Usergroup', $request);
$Hooks->do_action('After_Update_Usergroup', $usergroup);
$Hooks->do_action('Before_Delete_Usergroup', $request);
$Hooks->do_action('After_Delete_Usergroup', $usergroup);
$Hooks->do_action('Before_Showing_Usergroup_List');
$Hooks->do_action('After_Showing_Usergroup_List');
$Hooks->do_action('After_Hooks_Setup', $Hooks);
$Hooks->do_action('Before_Showing_Barcode_List');
$Hooks->do_action('After_Showing_Barcode_List');
$Hooks->do_action('Before_Create_purchase_Invoice', $request);
$Hooks->do_action('After_Create_purchase_Invoice', $invoice_id);
$Hooks->do_action('Before_Delete_purchase_Invoice', $request);
$Hooks->do_action('After_Delete_purchase_Invoice', $invoice_id);
$Hooks->do_action('Before_purchase_Invoice_Create_Form', $sup_id);
$Hooks->do_action('After_purchase_Invoice_Create_Form', $sup_id);
$Hooks->do_action('Before_View_purchase_Invoice', $invoice_id);
$Hooks->do_action('After_View_purchase_Invoice', $invoice_id);
$Hooks->do_action('Before_View_Quotation', $reference_no);
$Hooks->do_action('After_View_Quotation', $reference_no);
$Hooks->do_action('Before_Showing_Quotation_List');
$Hooks->do_action('After_Showing_Quotation_List');
$Hooks->do_action('Before_Create_purchase_Invoice', $request);
$Hooks->do_action('After_Create_purchase_Invoice', $invoice_id);
$Hooks->do_action('Before_Delete_purchase_Invoice', $request);
$Hooks->do_action('After_Delete_purchase_Invoice', $invoice_id);
$Hooks->do_action('Before_purchase_Invoice_Create_Form', $sup_id);
$Hooks->do_action('After_purchase_Invoice_Create_Form', $sup_id);
$Hooks->do_action('Before_View_purchase_Invoice', $invoice_id);
$Hooks->do_action('After_View_purchase_Invoice', $invoice_id);
*/