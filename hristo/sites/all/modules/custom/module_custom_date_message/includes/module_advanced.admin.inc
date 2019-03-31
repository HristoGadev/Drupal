<?php
function module_advanced_settings_form() {
  $form['my_module_enable_user'] = [

    '#type' => 'checkbox',
    '#title' => t('Show for anonymous users'),
    '#default_value' => variable_get('my_module_enable_user', 0),

  ];
  return system_settings_form($form);
}
