<?php
function module_custom_date_settings_form(){
$form['my_module_enable_date'] = array(

'#type' => 'checkbox',
'#title' => t('Enables/disables date.'),
'#default_value' => variable_get('my_module_enable_date',0),

);
$form['module_custom_date_select']=array(
'#type'=>'select',
'#title'=>'Select options',
'#options'=>array(
'status'=>'Status',
'warning'=>'Warning',
'error'=>'Error',
),
'#default_value' => variable_get('module_custom_date_select',''),
);
    return system_settings_form($form);
}
