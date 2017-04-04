<?php
// Welcome
Route::get('/', 'WelcomeController@index');
route::post('/setup', 'WelcomeController@store');

// Authentication & Profile
Auth::routes();
Route::get('/profile', 'User\ProfileController@index');
Route::post('/profile', 'User\ProfileController@update');
//
Route::get('/password', 'User\PasswordController@index');
Route::post('/password', 'User\PasswordController@update');

// Sidebar
Route::get('/home', 'Tickets\UserTicketController@index');
Route::get('/settings', 'SettingController@index');

// User Tickets
Route::get('/tickets/user/{rows}', 'Tickets\UserTicketController@all');
route::get('/tickets/departments', 'Tickets\UserTicketController@departments');
Route::post('/tickets', 'Tickets\UserTicketController@store');
Route::get('/tickets/{id}', 'Tickets\UserTicketController@show');
Route::get('/tickets/details/{id}', 'Tickets\UserTicketController@details');

// Staff Tickets
Route::get('/tickets', 'Tickets\StaffTicketController@index');
Route::get('/tickets/staff/{rows}/{status}', 'Tickets\StaffTicketController@all');
Route::get('/tickets/staff/{id}', 'Tickets\StaffTicketController@show');
Route::post('/tickets/staff/assign/{id}', 'Tickets\StaffTicketController@assign');
//
Route::get('/api/staff/members', 'Tickets\StaffTicketController@members');
Route::get('/api/users', 'Tickets\StaffTicketController@users');

// Staff Tickets Status
Route::post('/tickets/status/close/{id}','Tickets\StatusController@close');
Route::post('/tickets/status/open/{id}','Tickets\StatusController@open');

// Comments
Route::get('/comments/{ticketId}', 'Tickets\CommentController@all');
Route::post('/comments', 'Tickets\CommentController@store');
Route::put('/comments/{id}', 'Tickets\CommentController@update');
Route::delete('/comments/{id}', 'Tickets\CommentController@destroy');

// Settings - User Locking
Route::post('/settings/user/{id}/lock', 'Settings\UserStatusController@lock');
Route::post('/settings/user/{id}/unlock', 'Settings\UserStatusController@unlock');

// Settings - Departments
Route::get('/settings/departments', 'Settings\DepartmentController@index');
Route::get('/settings/departments/all', 'Settings\DepartmentController@all');
Route::post('/settings/departments', 'Settings\DepartmentController@store');
Route::put('/settings/departments/{id}', 'Settings\DepartmentController@update');
Route::delete('/settings/departments/{id}', 'Settings\DepartmentController@destroy');

// Settings - Domains
Route::get('/settings/domains', 'Settings\DomainController@index');
Route::get('/settings/domains/all', 'Settings\DomainController@all');
Route::post('/settings/domains', 'Settings\DomainController@store');
Route::put('/settings/domains/{id}', 'Settings\DomainController@update');
Route::delete('/settings/domains/{id}', 'Settings\DomainController@destroy');

// Settings - Domains - Actions
Route::get('/settings/domains/action', 'Settings\DomainActionController@show');
Route::put('/settings/domains/action/{type}', 'Settings\DomainActionController@update');

// Settings - Organization
Route::post('/settings/organization', 'Settings\OrganizationController@update');

// Settings - Staff
Route::get('/settings/staff', 'Settings\StaffController@index');
Route::get('/settings/staff/all', 'Settings\StaffController@all');
Route::get('/settings/staff/password', 'Settings\StaffController@password');
Route::post('/settings/staff', 'Settings\StaffController@store');
Route::put('/settings/staff/{id}', 'Settings\StaffController@update');
Route::delete('/settings/staff/{id}', 'Settings\StaffController@destroy');

// Settings - Staff Departments
Route::get('/settings/staff/{id}/departments/associated', 'Settings\StaffDepartmentController@associated');
Route::get('/settings/staff/{id}/departments/available', 'Settings\StaffDepartmentController@available');
Route::post('/settings/staff/{id}/departments', 'Settings\StaffDepartmentController@store');
Route::delete('/settings/staff/{id}/departments/{deptId}', 'Settings\StaffDepartmentController@destroy');

// Settings - Bulk Transfer
Route::get('/settings/transfer', 'Settings\TransferController@index');
Route::post('/settings/transfer/staff', 'Settings\TransferController@staff');
Route::post('/settings/transfer/departments', 'Settings\TransferController@departments');

// Settings - Registered Users
Route::get('/settings/registered', 'Settings\RegisteredController@index');
Route::get('/settings/registered/{rows}', 'Settings\RegisteredController@all');
Route::delete('/settings/registered/{id}', 'Settings\RegisteredController@destroy');
