<?php

/**
 * @modified by Leo: add common data init and template theme routing 
 * @modified: 
*/

$data = [];


# added by maksimU : for whitelist labeled deligaters
$data['tazzer_logoimage']       = C_FRONTLOGO;
$data['tazzer_favicon']         =  C_FAVICON;
$data['tazzer_brandname']       =  C_WEBSITENAME;
$data['tazzer_brandcolor']      =  C_DEFAILTCOLOR;
$data['tazzer_whitelabeled']    = false;

$preview = false;
if ($this->input->get('web_preview') == 'true') $preview = true;

// Leo - bug fix -- add "$this->MY && "
if ($this->MY && ($whitelabelInfo = $this->MY->WLA())) {
    $data['tazzer_whitelabeled'] = true;
    if (!empty($whitelabelInfo['logofile']))
        $data['tazzer_logoimage'] =  'wll_logos/' . $whitelabelInfo['logofile'];   //'wll_logos/logo_email.png';
    if (!empty($whitelabelInfo['brandname']))
        $data['tazzer_brandname'] =  $whitelabelInfo['brandname'];  //'Maksim U';
    if (!empty($whitelabelInfo['color']))
        $data['tazzer_brandcolor'] = $whitelabelInfo['color'];      // '#789966';
    if (!empty($whitelabelInfo['favicon']))
        $data['tazzer_favicon'] =  'wll_logos/' . $whitelabelInfo['favicon'];      //'wll_logos/favicon.png';

    print('<style> .footer{ background-color:' . $data['tazzer_brandcolor'] . ' !important } </style>');
    print('<style> .about-block[data-v-60e7013d]{ background-color:' . $data['tazzer_brandcolor'] . ' !important } </style>');
    print('<style> .service-block-1[data-v-60e7013d]{ background-color:' . $data['tazzer_brandcolor'] . ' !important } </style>');
    print('<style> .review-block[data-v-60e7013d]{ background-color:' . $data['tazzer_brandcolor'] . ' !important } </style>');
    print('<style> .app-block[data-v-60e7013d]{ background-color:' . $data['tazzer_brandcolor'] . ' !important } </style>');
}

if ($preview) {
    $data['tazzer_whitelabeled'] = true;
    if (!empty($this->input->get('logofile')))
        $data['tazzer_logoimage'] =  'wll_logos/' . $this->input->get('logofile');
    if (!empty($this->input->get('favicon')))
        $data['tazzer_favicon'] =  'wll_logos/' . $this->input->get('favicon');
    if (!empty($this->input->get('brandname')))
        $data['tazzer_brandname'] =  $this->input->get('brandname');
    if (!empty($this->input->get('color')))
        $data['tazzer_brandcolor'] = '#' . $this->input->get('color');

    print('<style> .footer{ background-color:' . $data['tazzer_brandcolor'] . ' !important } </style>');
    print('<style> .about-block[data-v-60e7013d]{ background-color:' . $data['tazzer_brandcolor'] . ' !important } </style>');
    print('<style> .service-block-1[data-v-60e7013d]{ background-color:' . $data['tazzer_brandcolor'] . ' !important } </style>');
    print('<style> .review-block[data-v-60e7013d]{ background-color:' . $data['tazzer_brandcolor'] . ' !important } </style>');
    print('<style> .app-block[data-v-60e7013d]{ background-color:' . $data['tazzer_brandcolor'] . ' !important } </style>');
}
# maksimU end


    $default_language_select = default_language();
    if ($this->session->userdata('user_select_language') == '') {
        $data['user_selected'] = $default_language_select['language_value'];
    } else {
        $data['user_selected'] = $this->session->userdata('user_select_language');
    }
    $data['default_language_select'] = $default_language_select;
    $data['active_language'] = active_language();
    $lg = custom_language($data['user_selected']);
    $data['default_language'] = $lg['default_lang'];
    $data['user_language'] = $lg['user_lang'];
    
    $popularServices = getPopularServices(8);
    $data['popularServices'] = $popularServices;
    $popularCategories = getPopularCategories(6);
    $data['popularCategories'] = $popularCategories;

    $data['settings'] = settingValue();
    
    $this->load->vars($data);
    
    $this->load->view($theme . '/'.TEMPLATE_THEME.'/header');
    $this->load->view($theme . '/'.TEMPLATE_THEME.'/navbar');
    $this->load->view($theme . '/'.$module . '/' . $page);
    $this->load->view($theme . '/'.TEMPLATE_THEME.'/footer');

# added by maksimU for white list labeld
if ($data['tazzer_whitelabeled']) {
    print('<style> .footer{ background-color:' . $data['tazzer_brandcolor'] . ' !important } </style>');
    print('<style> .about-block[data-v-60e7013d]{ background-color:' . $data['tazzer_brandcolor'] . ' !important } </style>');
    print('<style> .service-block-1[data-v-60e7013d]{ background-color:' . $data['tazzer_brandcolor'] . ' !important } </style>');
    print('<style> .review-block[data-v-60e7013d]{ background-color:' . $data['tazzer_brandcolor'] . ' !important } </style>');
    print('<style> .app-block[data-v-60e7013d]{ background-color:' . $data['tazzer_brandcolor'] . ' !important } </style>');
}
    # maksimU end