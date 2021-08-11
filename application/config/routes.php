<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| ----------------------------------------------------------------  ---------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|   example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|   https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|   $route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|   $route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|   $route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples: my-controller/index -> my_controller/index
|       my-controller/my-method -> my_controller/my_method
*/
$route['default_controller'] = 'home';
$route['admin'] = 'admin/login';
$route['check'] = 'home/check';
$route['promo-code-check'] = 'home/promo_code_check';

$route['employee_form'] = 'admin/service/employee_form';
$route['employee_form_view'] = 'admin/service/employee_form_view';
$route['employee_form_delete'] = 'admin/service/employee_form_delete';
$route['employee_form_status'] = 'admin/service/employee_form_status';

$route['emp_job_book1'] = 'admin/service/emp_job_book1';
$route['emp_job_book2'] = 'admin/service/emp_job_book2';


$route['forgot_password'] = 'admin/login/forgot_password';
$route['dashboard'] = 'admin/dashboard';
$route['map'] = 'admin/dashboard/map_list';
$route['map_lists'] = 'admin/dashboard/service_map_list';
$route['admin-profile'] = 'admin/profile';
$route['admin/logout'] = 'admin/login/logout';
$route['admin/wallet'] = 'admin/wallet';
$route['admin/wallet-history'] = 'admin/wallet/wallet_history';
/*booking report*/
$route['admin/total-report'] = 'admin/Booking/total_bookings';
$route['admin/pending-report'] = 'admin/Booking/pending_bookings';
$route['admin/inprogress-report'] = 'admin/Booking/inprogress_bookings';
$route['admin/complete-report'] = 'admin/Booking/completed_bookings';
$route['admin/reject-report'] = 'admin/Booking/rejected_bookings';
$route['admin/cancel-report'] = 'admin/Booking/cancel_bookings';
$route['reject-payment/(:num)'] = 'admin/Booking/reject_booking_payment';
$route['pay-reject'] = 'admin/Booking/update_reject_payment';

$route['admin-notification'] = 'admin/Dashboard/admin_notification';
$route['send-notification/(:any)'] = 'admin/Dashboard/send_notification/$1';

$route['admin/admin-give-review/(:any)']='admin/custom_review/admin_give_review';
$route['review_submit/(:any)']='admin/custom_review/review_submit';

# update by maksimU : For Employee
//$route['employee-dashboard']='user/service/employee_dashboard';
$route['employee-login'] = 'employee/employee_login/employee_login_check';
$route['employee'] = 'employee/employee_login';
$route['employee-dashboard']='employee/service/employee_dashboard';
$route['employee-orders/(:any)']='employee/service/employee_orders';
$route['update_status_by_employee']='employee/service/update_status_user';
$route['employee-settings']='employee/dashboard/employee_settings';
$route['employee-jobs']='user/dashboard/employee_jobs';
$route['employee-chat']='employee/Chat_ctrl';
$route['employee-chat/employee-order-new-chat']='employee/Chat_ctrl/employee_order_new_chat';
$route['employee-chat/insert_chat']='employee/Chat_ctrl/insert_message';
$route['geticeserver']='Geticeserver';

$route['employee-chat/job-new-chat']='employee/Chat_ctrl/job_new_chat';
$route['employee-chat/insert_chat']='employee/Chat_ctrl/insert_message';
$route['employee-chat/get_user_chat_lists']='employee/Chat_ctrl/get_user_chat_lists';
$route['employee-chat/decline-coworker']='employee/Chat_ctrl/decline_coworker';

$route['employee-contact-admin']='employee/contact/contact';
$route['employee/insert_contact_admin']='employee/contact/insert_contact_admin';
$route['employee-contact-user']='employee/contact/contact_user';
$route['employee/insert_contact_user']='employee/contact/insert_contact_user';

$route['employee-wallet']='employee/dashboard/employee_wallet';
$route['employee-add-service']='employee/service/add_service';
$route['employee-my-services']='employee/myservice/index';
$route['employee-my-services-inactive']='employee/myservice/inactive_services';

$route['employee-project']='project/index';
$route['employee-project-post']='project/post_project';
$route['employee-project-detail/(:num)']='project/detail';
$route['employee-project-proposals/(:num)']='project/proposals';

# self-employed -----------------------------------------------------------------------
$route['self-employed-dashboard']='employee/service/employee_dashboard';
$route['self-employed-orders/(:any)']='employee/service/employee_orders';
$route['self-employed-settings']='employee/dashboard/employee_settings';
$route['self-employed-jobs']='user/dashboard/employee_jobs';
$route['self-employed-chat']='employee/Chat_ctrl';
$route['self-employed-chat/employee-order-new-chat']='employee/Chat_ctrl/employee_order_new_chat';
$route['self-employed-chat/insert_chat']='employee/Chat_ctrl/insert_message';

$route['self-employed-chat/job-new-chat']='employee/Chat_ctrl/job_new_chat';
$route['self-employed-chat/insert_chat']='employee/Chat_ctrl/insert_message';
$route['self-employed-chat/get_user_chat_lists']='employee/Chat_ctrl/get_user_chat_lists';
$route['self-employed-chat/decline-coworker']='employee/Chat_ctrl/decline_coworker';
$route['self-employed-chat/avcall']='employee/Chat_ctrl/avcall';

$route['self-employed-contact-admin']='employee/contact/contact';
$route['employee/insert_contact_admin']='employee/contact/insert_contact_admin';
$route['self-employed-contact-user']='employee/contact/contact_user';

$route['self-employed-wallet']='employee/dashboard/employee_wallet';
$route['self-employed-add-service']='employee/service/add_service';
$route['self-employed-my-services']='employee/myservice/index';
$route['self-employed-my-services-inactive']='employee/myservice/inactive_services';

$route['self-employed-project']='project/index';
$route['self-employed-project-post']='project/post_project';
$route['self-employed-project-detail/(:num)']='project/detail';
$route['self-employed-project-proposals/(:num)']='project/proposals';

# sole trader -------------------------------------------------------------------------
$route['soletrader-dashboard']='employee/service/employee_dashboard'; //=employee-dashboard
$route['soletrader-orders/(:any)']='employee/service/employee_orders'; //=soletrader-orders
$route['soletrader-contact-admin']='employee/contact/contact';
$route['soletrader/insert_contact_admin']='employee/contact/insert_contact_admin';
$route['soletrader-contact-user']='employee/contact/contact_user';
$route['soletrader/insert_contact_user']='employee/contact/insert_contact_user';

$route['soletrader-wallet']='employee/dashboard/employee_wallet';
$route['soletrader-add-service']='employee/service/add_service';
$route['soletrader-my-services']='employee/myservice/index';
$route['soletrader-my-services-inactive']='employee/myservice/inactive_services';

$route['employee-chat/avcall']='employee/Chat_ctrl/avcall';
$route['user-chat/avcall']='user/Chat_ctrl/avcall';

# Organization -------------------------------------------------------------------------
$route['organization-dashboard']='organization/service/organization_dashboard';
$route['organization-orders/(:any)']='organization/service/organization_orders';
$route['update_status_by_organization']='organization/service/update_status_user';
$route['organization-settings']='organization/dashboard/organization_settings';
$route['organization-chat']='organization/Chat_ctrl';
$route['organization-chat/organization-order-new-chat']='organization/Chat_ctrl/organization_order_new_chat';
$route['organization-chat/insert_chat']='organization/Chat_ctrl/insert_message';
$route['organization-chat/avcall']='organization/Chat_ctrl/avcall';
$route['organization-add-service']='organization/service/add_service';
$route['organization-my-services']='organization/myservice/index';
$route['organization-my-services-inactive']='organization/myservice/inactive_services';
$route['organization-wallet']='organization/dashboard/organization_wallet';
$route['organization-staff']='organization/staff/index';
$route['organization-subscription']='organization/dashboard/organization_subscription';

$route['organization-staff-info']='organization/staff/ajax_staff_info';
$route['ajax-remove-staff']='organization/staff/ajax_remove_staff';

$route['organization-contact-admin']='organization/contact/contact';
$route['organization/insert_contact_admin']='organization/contact/insert_contact_admin';
$route['organization-contact-user']='organization/contact/contact_user';
$route['organization/insert_contact_user']='organization/contact/insert_contact_user';

$route['organization-ads']='organization/ads/index';

# Staff --------------------------------------------------------------------------------
$route['staff-dashboard']='staff/service/staff_dashboard';
$route['staff-orders/(:any)']='staff/service/staff_orders';
$route['update_status_by_staff']='staff/service/update_status_user';
$route['staff-settings']='staff/dashboard/staff_settings';
$route['staff-chat']='staff/Chat_ctrl';
$route['staff-chat/staff-order-new-chat']='staff/Chat_ctrl/staff_order_new_chat';
$route['staff-chat/insert_chat']='staff/Chat_ctrl/insert_message';
$route['staff-chat/avcall']='staff/Chat_ctrl/avcall';
$route['staff-my-services']='staff/myservice/index';
$route['staff-my-services-inactive']='staff/myservice/inactive_services';
$route['staff-wallet']='staff/dashboard/staff_wallet';
$route['staff-contact-admin']='staff/contact/contact';
$route['staff/insert_contact_admin']='staff/contact/insert_contact_admin';
$route['staff-contact-user']='staff/contact/contact_user';
$route['staff/insert_contact_user']='staff/contact/insert_contact_user';

# Partner ------------------------------------------------------------------------------
$route['partner-dashboard']='partner/service/partner_dashboard';
$route['partner-orders/(:any)']='partner/service/partner_orders';
$route['update_status_by_partner']='partner/service/update_status_user';
$route['partner-settings']='partner/dashboard/partner_settings';
$route['partner-chat']='partner/Chat_ctrl';
$route['partner-chat/partner-order-new-chat']='partner/Chat_ctrl/partner_order_new_chat';
$route['partner-chat/insert_chat']='partner/Chat_ctrl/insert_message';
$route['partner-chat/avcall']='partner/Chat_ctrl/avcall';
$route['partner-my-services']='partner/myservice/index';
$route['partner-my-services-inactive']='partner/myservice/inactive_services';
$route['partner-add-service']='partner/service/add_service';
$route['partner-wallet']='partner/dashboard/partner_wallet';
$route['partner-subscription']='partner/dashboard/partner_subscription';
$route['partner-contact-admin']='partner/contact/contact';
$route['partner/insert_contact_admin']='partner/contact/insert_contact_admin';
$route['partner-contact-user']='partner/contact/contact_user';
$route['partner/insert_contact_user']='partner/contact/insert_contact_user';

# update end ===========================================================================

//admin users
$route['adminusers'] = 'admin/dashboard/adminusers';
$route['adminusers/edit']='admin/dashboard/edit_adminusers';
$route['adminusers/edit/(:num)']='admin/dashboard/edit_adminusers/$1';
$route['adminuser-details/(:num)'] = 'admin/dashboard/adminuser_details/$1';
$route['adminusers_list'] = 'admin/dashboard/adminusers_list';

//
$route['review_submit_delete/(:any)']='admin/custom_review/review_submit_delete';
$route['review_submit_edit/(:any)']='admin/custom_review/review_submit_edit/$1';

//email template 
$route['emailtemplate'] = 'admin/emailtemplate';
$route['edit-emailtemplate/(:num)'] = 'admin/emailtemplate/edit/$1';

//

/* Settings*/
$route['admin/fb_social_media'] = 'admin/settings/fb_social_media';
$route['admin/googleplus_social_media'] = 'admin/settings/googleplus_social_media';
$route['admin/twit_social_media'] = 'admin/settings/twit_social_media';
$route['admin/emailsettings'] = 'admin/settings/emailsettings';
$route['admin/sms-settings'] = 'admin/settings/smssettings';
$route['admin/stripe_payment_gateway'] = 'admin/settings/stripe_payment_gateway';
$route['admin/razorpay_payment_gateway'] = 'admin/settings/razorpay_payment_gateway';
$route['admin/paypal_payment_gateway'] = 'admin/settings/paypal_payment_gateway';
$route['admin/paytabs_payment_gateway'] = 'admin/settings/paytabs_payment_gateway';

//$route['yourself_uuuu'] = 'admin/settings/paytabs_payment_gateway';

$route['otp-verification'] = 'home/otp_verification';

$route['otp-verification-check'] = 'home/otp_verification_check';
$route['user-form'] = 'home/user_form';
$route['second_user_form'] = 'home/second_user_form';

$route['users'] = 'admin/dashboard/users';
$route['user-details/(:num)'] = 'admin/dashboard/user_details/$1';
$route['users_list'] = 'admin/dashboard/users_list';
$route['user-login-history'] = 'admin/userloginhistory';
$route['user-chat-history'] = 'admin/userchathistory';

$route['categories'] = 'admin/categories/categories';
$route['add-category'] = 'admin/categories/add_categories';
$route['categories/check_category_name'] = 'admin/categories/check_category_name';
$route['edit-category/(:num)'] = 'admin/categories/edit_categories/$1';

$route['subcategories'] = 'admin/categories/subcategories';
$route['add-subcategory'] = 'admin/categories/add_subcategories';
$route['categories/check_subcategory_name'] = 'admin/categories/check_subcategory_name';
$route['edit-subcategory/(:num)'] = 'admin/categories/edit_subcategories/$1';

$route['subscriptions'] = 'admin/service/subscriptions';

$route['add-subscription'] = 'admin/service/add_subscription';

$route['service/check_subscription_name'] = 'admin/service/check_subscription_name';

$route['service/save_subscription'] = 'admin/service/save_subscription';

$route['edit-subscription/(:num)'] = 'admin/service/edit_subscription/$1';

$route['service/update_subscription'] = 'admin/service/update_subscription';

$route['subscription-list'] = 'user/subscription/subscription_list';

$route['ratingstype'] = 'admin/ratingstype/ratingstype';
$route['review-reports'] = 'admin/ratingstype/review_report';

$route['add-ratingstype'] = 'admin/ratingstype/add_ratingstype';

$route['ratingstype/check_ratingstype_name'] = 'admin/ratingstype/check_ratingstype_name';

$route['edit-ratingstype/(:num)'] = 'admin/ratingstype/edit_ratingstype/$1';

$route['service-providers'] = 'admin/service/service_providers';

$route['provider_list'] = 'admin/service/provider_list';
$route['service-list'] = 'admin/service/service_list';
$route['provider-details/(:num)'] = 'admin/service/provider_details/$1';
$route['admin/provider_list'] = 'admin/service/provider_list';
$route['payment_list'] = 'admin/payments/payment_list';
$route['admin-payment/(:any)'] = 'admin/payments/admin_payment/$1';  
$route['service-details/(:num)'] = 'admin/service/service_details/$1';

$route['service-details-delete/(:num)'] = 'admin/service/service_details_delete/$1';

$route['service-details-delete-del/(:num)'] = 'admin/service/service_details_delete_del/$1';

$route['contact-details/(:num)'] = 'admin/contact/contact_details/$1';

/*job_view*/

$route['job-view'] = 'admin/dashboard/job_view';


/*web*/

$route['all-categories'] = 'categories';
$route['featured-category'] = 'user/categories/featured_categories';
$route['service-preview/(:any)'] = 'home/service_preview/$1';
$route['all-services'] = 'home/all_services';
$route['all-services/(:any)'] = 'home/all_services/$1';
$route['services'] = 'home/services';
$route['service-search-by-category'] = 'home/service_search_by_category';
$route['service-category-detail/(:any)'] = 'home/service_category_detail/$1';
$route['get-service-price'] = 'home/get_service_price';
$route['service-booking/(:any)'] = 'home/service_booking/$1';

//your self
$route['yourself'] = 'home/yourself';
$route['yourself_next'] = 'home/yourself_next';

$route['verifyotp'] = 'home/otp_verify_email';

$route['admin/settings_verifyotp'] = 'admin/settings/otp_verify';

$route['admin/verifyotp'] = 'admin/settings/otp_verify_email';
//$route['admin/settings_verifyotp'] = 'settings/otp_verify';  
//$route['admin/verifyotp'] = 'admin/settings/otp_verify_email';

$route['second_form'] = 'home/second_form';

$route['yourself_form'] = 'home/yourself_final';

$route['yourself_final'] = 'home/yourself_last';


$route['admin/yourself_page'] = 'admin/carrer/self_data';

$route['featured-services'] = 'user/service/featured_services';
$route['popular-services'] = 'user/service/popular_services';
$route['search'] = 'home/all_services';
$route['about-us'] = 'user/about/about_us';
$route['latest-job'] = 'user/about/latest_job';
$route['search-by-category'] = 'user/about/search_by_category';
$route['terms-conditions'] = 'user/terms/terms';
$route['contact'] = 'user/contact/contact';
$route['pages/(:any)'] = 'home/pages/$1';
$route['search/(:any)'] = 'home/all_services/$1';
$route['privacy'] = 'user/privacy/privacy';
$route['faq'] = 'user/privacy/faq';
$route['help'] = 'user/privacy/help';
$route['blog'] = 'user/blog';
$route['blog-detail/(:any)'] = 'user/blog/blog_detail/$1';

//my_service_pagination
$route['my-services']='user/myservice/index';
$route['my-services-inactive']='user/myservice/inactive_services';
//end

$route['add-service']='user/service/add_service';

$route['edit_service']='user/service/edit_service';
$route['notification-list']='user/service/notification_view';
$route['booking']='user/service/booking';
$route['update_bookingstatus']='user/service/update_bookingstatus';
$route['update_status_user']='user/service/update_status_user';
$route['update_booking/(:any)']='user/service/update_booking/$1';
$route['user_bookingstatus/(:any)']='user/service/user_bookingstatus/$1';
$route['book-service/(:any)']='user/service/book_service/$1';
$route['user-dashboard']='user/service/user_dashboard';
$route['provider-dashboard']='user/service/provider_dashboard';
$route['user-settings']='user/dashboard/user_settings';
$route['change-password']='user/dashboard/userchangepassword';
$route['provider-change-password']='user/dashboard/prochangepassword';
$route['user-wallet']='user/dashboard/user_wallet';
$route['user-wallet-payment']='user/dashboard/user_wallet_payment';
$route['user-stipe-payment-process']='user/dashboard/user_stipe_payment_process';
$route['user-stipe-sub-payment-process']='user/dashboard/user_stripe_sub_payment_process';

$route['onboard-stripe'] = 'user/dashboard/onboard_stripe';
$route['onboard-stripe-refresh/(:any)'] = 'user/dashboard/onboard_stripe_refresh/$1';
$route['create-stripe-pro-subscription'] = 'user/dashboard/create_stripe_pro_subscription';
$route['pro-subscription-success/(:any)'] = 'user/dashboard/pro_subscription_success/$1';

$route['create-stripe-user-wallet'] = 'user/dashboard/create_stripe_user_wallet';
$route['webhook-stripe-user-wallet'] = 'user/dashboard/webhook_stripe_user_wallet';
$route['add-wallet-success/(:any)'] = 'user/dashboard/add_wallet_success/$1';

$route['pay-by-stripe/(:any)']		='user/dashboard/user_pay_by_stripe/$1';
$route['subscription-by-stripe/(:any)']		='user/dashboard/user_subscription_pay_by_stripe/$1';

$route['get-paypal-access-token'] = "user/dashboard/get_paypal_access_token";
$route['get-paypal-client-token'] = "user/dashboard/get_paypal_client_token";
$route['paypal-add-wallet'] = "user/dashboard/paypal_add_wallet";
$route['create-paypal-user-wallet'] = 'user/dashboard/create_paypal_user_wallet';
$route['capture-paypal-user-wallet'] = 'user/dashboard/capture_paypal_user_wallet';
$route['paypal-add-wallet-success/(:any)'] = 'user/dashboard/paypal_add_wallet_success/$1';

$route['start-paypal-pro-subscription'] = "user/dashboard/start_paypal_pro_subscription";
$route['paypal-pro-subscription'] = "user/dashboard/paypal_pro_subscription";
$route['paypal-pro-subscription-success'] = "user/dashboard/paypal_pro_subscription_success";

$route['paytab_payment']='user/dashboard/paytab_payment';//user/dashboard/paytab_payment
$route['user-payment']='user/dashboard/user_payment';
$route['user-accountdetails']='user/dashboard/user_accountdetails';
$route['user-reviews']='user/dashboard/user_reviews';
$route['provider-reviews']='user/dashboard/provider_reviews';
$route['booking-details/(:any)']='user/service/booking_details/$1';
$route['booking-details-user/(:any)']='user/service/booking_details_user/$1';

$route['provider-bookings']='user/dashboard/provider_bookings';
$route['provider-settings']='user/dashboard/provider_settings';
$route['provider-wallet']='user/dashboard/provider_wallet';
$route['provider-payment']='user/dashboard/provider_payment';
$route['provider-subscription']='user/dashboard/provider_subscription';

$route['verify-payment-method']='user/dashboard/verify_payment_method';

$route['provider-availability']='user/dashboard/provider_availability';
$route['provider-accountdetails']='user/dashboard/provider_accountdetails';
$route['create_availability']='user/dashboard/create_availability';
$route['user-bookings']='user/dashboard/user_bookings';

/*$route['post-a-jobs']='user/dashboard/post_a_jobs';
$route['post_jobs_form']='user/dashboard/post_jobs_form';
$route['manage-jobs']='user/dashboard/manage_jobs';
$route['manage-jobs-view']='user/dashboard/manage_jobs_view';
$route['manage-jobs-edit']='user/dashboard/manage_jobs_edit';
$route['manage-jobs-delete']='user/dashboard/manage_jobs_delete';
$route['manage-proposal']='user/dashboard/manage_proposal'; 
$route['send-reviews']='user/dashboard/send_reviews'; 
$route['send_reviews_form']='user/dashboard/send_reviews_form'; 
$route['view-reviews']='user/dashboard/view_reviews'; 

$route['send-proposal']='home/send_proposal';
$route['view-proposal']='home/view_proposal';
$route['send_proposal_form']='home/send_proposal_form';
$route['send_proposal_edit']='home/send_proposal_edit';
$route['action_proposal']='home/action_proposal';*/

$route['login'] = 'home/login';
$route['logout']='user/login/logout';
$route['go_to_vendor']='user/login/go_to_vendor';
$route['go_to_user']='user/login/go_to_user';

/*
 * Multiple Languages
 */
$route['language']='admin/language';

/*api*/

/*chat api*/

$route['user-chat'] = 'user/Chat_ctrl';
$route['user-chat/booking-new-chat']='user/Chat_ctrl/booking_new_chat';
$route['user-chat/job-new-chat']='user/Chat_ctrl/job_new_chat';

$route['user-chat/insert_chat']='user/Chat_ctrl/insert_message';
$route['user-chat/get_user_chat_lists']='user/Chat_ctrl/get_user_chat_lists';
$route['user-chat/decline-coworker']='user/Chat_ctrl/decline_coworker';

$route['api/country_details'] = 'api/api/country_details';
$route['api/chat_details'] = 'api/api/chat_details';
$route['api/chat'] = 'api/api/chat';
$route['api/chat_storage'] = 'api/api/insert_message';
$route['api/get-chat-list'] = 'api/api/get_chat_list';
$route['api/get-chat-history'] = 'api/api/get_chat_history';
$route['api/flash-device-token'] = 'api/api/flash_device_token';
$route['api/get-notification-list'] = 'api/api/get_notification_list';
$route['api/home'] = 'api/api/home';
$route['api/demo-home'] = 'api/api/demo_home';
$route['api/service-details'] = 'api/api/service_details';
$route['api/all-services'] = 'api/api/all_services';
$route['api/category'] = 'api/api/category';
$route['api/subcategory'] = 'api/api/subcategory';
$route['api/generate_otp'] = 'api/api/generate_otp';
$route['api/provider_signin'] = 'api/api/provider_signin';
$route['api/subcategory_services'] = 'api/api/subcategory_services';
$route['api/profile'] = 'api/api/profile';
$route['api/subscription'] = 'api/api/subscription';
$route['api/subscription_success'] = 'api/api/subscription_success';
$route['api/add_service'] = 'api/api/add_service';
$route['api/update_service'] = 'api/api/update_service';
$route['api/delete_service'] = 'api/api/delete_service';
$route['api/update_provider'] = 'api/api/update_provider';
$route['api/my_service'] = 'api/api/my_service';
$route['api/edit_service'] = 'api/api/edit_service';
$route['api/existing_user'] = 'api/api/existing_user';
$route['api/delete_serviceimage'] = 'api/api/delete_serviceimage';
$route['api/add_availability'] = 'api/api/add_availability';
$route['api/update_availability'] = 'api/api/update_availability';
$route['api/availability'] = 'api/api/availability';
$route['api/user_signin'] = 'api/api/user_signin';
$route['api/generate_userotp'] = 'api/api/generate_userotp';
$route['api/logout'] = 'api/api/logout';
$route['api/logout_provider'] = 'api/api/logout_provider';
$route['api/update_user'] = 'api/api/update_user';
$route['api/user_profile'] = 'api/api/user_profile';
$route['api/service_availability'] = 'api/api/service_availability';
$route['api/book_service'] = 'api/api/book_service';
$route['api/search_services'] = 'api/api/search_services';
$route['api/bookingdetail'] = 'api/api/bookingdetail';
$route['api/bookinglist_provider'] = 'api/api/bookinglist_provider';
$route['api/requestlist_provider'] = 'api/api/requestlist_provider';
$route['api/bookinglist_users'] = 'api/api/bookinglist_users';
$route['api/bookingdetail_user'] = 'api/api/bookingdetail_user';
$route['api/views'] = 'api/api/views';
$route['api/update_bookingstatus'] = 'api/api/update_bookingstatus';
$route['api/service_statususer'] = 'api/api/service_statususer';
$route['api/bookinglist'] = 'api/api/bookinglist';
$route['api/get_services_from_subid'] = 'api/api/get_services_from_subid';#get services belongs to sub category id
$route['api/get_provider_dashboard_infos'] = 'api/api/get_provider_dashboard_infos';#get provider dashboar infos
$route['api/delete_account'] = 'api/api/delete_account';
$route['api/rate_review'] = 'api/api/rate_review';
$route['api/review_type'] = 'api/api/review_type'; 
$route['api/update_booking'] = 'api/api/update_booking';
$route['api/generate_otp_provider'] = 'api/api/generate_otp_provider';
$route['api/check_provider_email'] = 'api/api/check_provider_email';
$route['api/check_user_emailid'] = 'api/api/check_user_emailid';
$route['api/forget_password'] = 'api/api/forget_password';
$route['api/userchangepassword'] = 'api/api/userchangepassword';
$route['api/generate_otp_user'] = 'api/api/generate_otp_user';
$route['api/stripe_account_details'] = 'api/api/stripe_account_details';
$route['api/details'] = 'api/api/details';
$route['api/account_details'] = 'api/api/account_details';
$route['api/update-myservice-status'] = 'api/api/update_myservice_status';


$route['api/chat_storage'] = 'api/api/insert_message';
$route['api/get-chat-list'] = 'api/api/get_chat_list';
$route['api/get-chat-history'] = 'api/api/get_chat_history';
$route['api/get-wallet'] = 'api/api/get_wallet_amt';
$route['api/add-user-wallet'] = 'api/api/add_user_wallet';
$route['api/add-user-wallet-payment'] = 'api/api/add_user_walle_payment';
$route['api/withdraw-provider'] = 'api/api/provider_wallet_withdrawal';
$route['api/customer-card-list'] = 'api/api/get_customer_saved_card';
$route['api/wallet-history'] = 'api/api/wallet_history';
$route['api/stripe_details'] = 'api/api/stripe_details';
$route['api/provider-card-info'] = 'api/api/provider_card_info';


$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//Add Multiple Language
$route['language'] = 'admin/language';
$route['add-language'] = 'admin/language/AddLanguages';
$route['insert-language'] = 'admin/language/InsertLanguage';
$route['update_language'] = 'admin/language/update_language_status';
//Add Wep Keywords
$route['wep_language'] = 'admin/language/wep_language';
$route['add-wep-keyword'] = 'admin/language/AddWepKeyword';
$route['insert_web_keyword'] = 'admin/language/InsertWepKeyword';
$route['update_multi_web_language'] = 'admin/language/update_multi_web_language/';
$route['language_web_list'] = 'admin/language/language_web_list';
//App Keyword
$route['app_page_list'] = 'admin/language/AppPageList';
$route['app_page_list/(:any)'] = 'admin/language/pages_language';
$route['add-app-keyword'] = 'admin/language/AddAPPKeyword';
$route['insert_app_keyword'] = 'admin/language/InsertAppKeyword';
$route['language_list'] = 'admin/language/language_list';
//$route['app-keyword-add'] = 'admin/language/AllAPPKeyword';
$route['insertApp'] = 'admin/language/AppKeyword';
$route['app-keyword-add/(:any)'] = 'admin/language/AllAPPKeyword';


$route['Revenue'] = 'admin/Revenue';

$route['paypal_braintree'] = 'user/paypal/braintree';


$route['admin/theme-color'] = 'admin/Settings/ThemeColorChange';
$route['Change_color'] = 'admin/Settings/ChangeColor';

// User Subscription
$route['user-subscription'] = 'admin/userSubscription';
$route['user-subscription-list'] = 'admin/userSubscription/userSubscriptionLists';
$route['user-promo-code-notification'] = 'admin/userSubscription/promoNotification';

$route['third_form'] = 'home/third_form';
$route['fourth_step'] = 'home/fourth_step';

$route['fifth_step'] = 'home/fifth_step';

