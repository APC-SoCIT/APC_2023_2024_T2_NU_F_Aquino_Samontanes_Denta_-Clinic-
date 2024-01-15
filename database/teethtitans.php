<?php
/**
 * Export to PHP Array plugin for PHPMyAdmin
 * @version 5.2.0
 */

/**
 * Database `teethtitans`
 */

/* `teethtitans`.`appointments` */
$appointments = array(
  array('id' => '1','patient_id' => '1001','first_name' => 'Diandre Dawson','middle_name' => 'Brioso','last_name' => 'Gomez','Contact_Number' => '09205368951','email_address' => 'drei@gmail.com','weight' => '50','date_of_appointment' => '01/16/24 11:59','gender' => 'M','concerns' => 'Toothache','allergies' => 'yes','specified_allergies' => 'Peanuts','hypertension' => 'no','diabetes' => 'no','uric_acid' => 'no','cholesterol' => 'no','asthma' => 'no','medically_compromised' => 'no','appointment_condition' => 'approved','created_at' => '2024-01-14 20:13:13'),
  array('id' => '2','patient_id' => '1001','first_name' => 'Diandre Dawson','middle_name' => 'Brioso','last_name' => 'Gomez','Contact_Number' => '09205368951','email_address' => 'drei@gmail.com','weight' => '50','date_of_appointment' => '01/17/24 11:20','gender' => 'M','concerns' => 'None','allergies' => 'no','specified_allergies' => '','hypertension' => 'no','diabetes' => 'no','uric_acid' => 'no','cholesterol' => 'no','asthma' => 'no','medically_compromised' => 'no','appointment_condition' => 'approved','created_at' => '2024-01-14 20:18:46')
);

/* `teethtitans`.`patients` */
$patients = array(
  array('id' => '2','patient_id' => '1001','first_name' => 'Diandre Dawson','middle_name' => 'Brioso','last_name' => 'Gomez','gender' => 'M','contact_number' => '09205368951','email_address' => 'drei@gmail.com','treatment_plan' => NULL,'progress' => NULL,'created_at' => '2024-01-14 20:19:03','is_archived' => 'yes')
);

/* `teethtitans`.`users` */
$users = array(
  array('id' => '1','email_address' => 'aquinosamontanesadmin@gmail.com','password' => 'admin123','first_name' => 'admin','last_name' => 'admin','user_role' => 'admin','created_at' => ''),
  array('id' => '1001','email_address' => 'drei@gmail.com','password' => 'drei123','first_name' => 'Diandre Dawson','last_name' => 'Gomez','user_role' => 'patient','created_at' => '2024-01-14 12:44:55')
);
