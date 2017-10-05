/*
 *
 */

/*! url-utils */

/* global CForm, CDictionary, Validate, MainGlobals, TreeJSON, g_base_url, Utils */

function UrlUtil() {}

UrlUtil.get_home_href         = function (lang) {

    var href = '';

    lang = ( lang == null ) ? CDictionary.lang : lang;

    href = g_base_url+lang;

    return href;

};

UrlUtil.get_contact_href      = function (lang) {

    var href = '';

    lang = ( lang == null ) ? CDictionary.lang : lang;

    href = g_base_url + lang + "/" + CDictionary.get_text("ContactUs_lbl") + '/7';

    return href;
};

UrlUtil.get_login_href        = function (lang) {

    var href = '';

    lang = ( lang == null ) ? CDictionary.lang : lang;

    href = g_base_url + lang + "/" + CDictionary.get_text("Login_lbl") + '/3';

    return href;
};

UrlUtil.get_register_href     = function (lang) {

    var href = '';

    lang = ( lang == null ) ? CDictionary.lang : lang;

    href = g_base_url + lang + "/" + CDictionary.get_text("Register_lbl") + '/4';

    return href;
};

UrlUtil.get_user_profile_href = function (user_id, username, lang) {

    var href = '';

    lang = ( lang == null ) ? CDictionary.lang : lang;

    href = g_base_url + lang + "/" + CDictionary.get_text("UserProfile_lbl") + '/' + username + '/5-'+user_id;

    return href;
};

UrlUtil.get_cart_href         = function (lang) {

    var href = '';

    lang = ( lang == null ) ? CDictionary.lang : lang;

    href = g_base_url + lang + "/" + CDictionary.get_text("Cart_lbl") + '/12';

    return href;
};

UrlUtil.get_checkout_href     = function (lang) {

    var href = '';

    lang = ( lang == null ) ? CDictionary.lang : lang;

    href = g_base_url + lang + "/" + CDictionary.get_text("Checkout_lbl") + '/13';

    return href;
};

UrlUtil.get_search_href       = function (search_item, lang) {
    
    var href = '';
    
    href = g_base_url + lang + "/" + CDictionary.get_text("Search_lbl") + '/' + search_item + '/5';

    //url = g_root_url+lang+'/'+CDictionary.get_text('Search_lbl')+'/'+search_item+'/8';

    ////alert( 'url = '+url );
    //url = url.toLowerCase().replace(/\s+/g,"-").replace(/&+/g,"").replace("?","");
    
    return href;
};

UrlUtil.get_section_href      = function (section, lang) {

    var href = '';

    lang = ( lang == null ) ? CDictionary.lang : lang;

    var path_string = '';

    var path_array = TreeJSON.get_section_path(section.section_id);


    for (var i=0; i<path_array.length; i++){

        var psection = path_array[i];

        var title = psection.title;

        path_string += title + '/';

    }
    
    var sub_url = lang + '/' + path_string + '1-' + section.section_id;
        
    //sub_url = self::fix_item_href($sub_url);

    href    = g_base_url + sub_url;

    return href;
};

UrlUtil.get_product_href      = function (product, lang){

    var href = '';

    //if( ! USE_MEANINGFUL_URL ){
    //    href  = '?page=product_info&product_id='+product.product_id+'&lang='+lang;
    //    return href;
    //}

    lang = ( lang == null ) ? CDictionary.get_language() : lang;

    var path_string = '';

    ///var path_array = TreeJSON.get_element(array, label, var);
    var path_array = TreeJSON.get_section_path(product.section_id);

    for (var i=0; i<path_array.length; i++){

        var psection = path_array[i];

        var title = Utils.trim( psection.title );

        path_string += title+'/';

    }

    title = Utils.trim( product.title );
    path_string += title+'/';

    var sub_url = lang+'/'+path_string+'2-'+product.product_id;

    //sub_url = self.fix_item_href(sub_url);

    href    = g_base_url + sub_url;

    return href;

};
    
UrlUtil.get_features_href     = function (features, values, value_ids, lang) {
    
    var href = '';
    
    lang = ( lang == null ) ? CDictionary.lang : lang;

    var sub_url = '';

    for(var i=0; i<features.length; i++){
        sub_url += features[i] + ':' + values[i] + '/';
    }

    sub_url += '13/';

    for(var i=0; i<features.length; i++){

        sub_url += value_ids[i];

        if( i<features.length - 1 ){
            sub_url += '-';
        }
    }

    sub_url += '/';

    href = g_base_url + lang + "/" + CDictionary.get_text("Features_lbl") + '/' + sub_url;

    return href;
};

////////////////////////////////////////////////////////////////////////////////
