<?php
    $data = [];
    # added by maksimU : for whitelist labeled deligaters
    // print($_SERVER['HTTP_HOST'].'<br>');
    // print($_SERVER['REQUEST_URI'].'<br>');

    $data['tazzer_logoimage'] = C_FRONTLOGO;
    $data['tazzer_favicon'] =  C_FAVICON;
    $data['tazzer_brandname'] =  C_WEBSITENAME;
    $data['tazzer_brandcolor'] =  C_DEFAILTCOLOR;
    $data['tazzer_whitelabeled'] = false;
    if($_SERVER['HTTP_HOST']=='127.0.0.1' || $_SERVER['HTTP_HOST']=='10.10.11.21')
    {
        $data['tazzer_whitelabeled'] = true;
        $data['tazzer_logoimage'] =  'wll_logos\logo_email.png';
        $data['tazzer_brandname'] =  'Maksim U';
        $data['tazzer_brandcolor'] =  '#789966';
        print('<style> .footer{ background-color:'.$data['tazzer_brandcolor'].' !important } </style>');
        print('<style> .about-block[data-v-60e7013d]{ background-color:'.$data['tazzer_brandcolor'].' !important } </style>');
        print('<style> .service-block-1[data-v-60e7013d]{ background-color:'.$data['tazzer_brandcolor'].' !important } </style>');
        print('<style> .review-block[data-v-60e7013d]{ background-color:'.$data['tazzer_brandcolor'].' !important } </style>');
        print('<style> .app-block[data-v-60e7013d]{ background-color:'.$data['tazzer_brandcolor'].' !important } </style>');
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
    $this->load->view('user' . '/'.TEMPLATE_THEME.'/header');
    $this->load->view('user' . '/'.TEMPLATE_THEME.'/navbar');
    $this->load->view($theme . '/'.$module . '/' . $page);
    $this->load->view('user' . '/'.TEMPLATE_THEME.'/footer');

    # added by maksimU for white list labeld
    if($data['tazzer_whitelabeled'])
    {
        print('<style> .footer{ background-color:'.$data['tazzer_brandcolor'].' !important } </style>');
        print('<style> .about-block[data-v-60e7013d]{ background-color:'.$data['tazzer_brandcolor'].' !important } </style>');
        print('<style> .service-block-1[data-v-60e7013d]{ background-color:'.$data['tazzer_brandcolor'].' !important } </style>');
        print('<style> .review-block[data-v-60e7013d]{ background-color:'.$data['tazzer_brandcolor'].' !important } </style>');
        print('<style> .app-block[data-v-60e7013d]{ background-color:'.$data['tazzer_brandcolor'].' !important } </style>');
        

    }
    # maksimU end