
/*! PrintArea */
/* Based on: [...] */
/* global CDictionary, Cover, CPopup */

function PrintArea() {
    this.myvar1 = false;
}

PrintArea.print_content = function (content){
    
    try{

        console.log('print content');

        //var iframe = $('body').find('iframe[id=print-iframe]');
        //var iframe = $('body').find("#print-iframe");
        var print_area = $('body').find('#print-area');

        if( print_area.length == 0 ){
            $('body').append('<div id="print-area"></div>');
            print_area = $('body').find('#print-area');
        }else{
            print_area.html('');
        }

        print_area.append(content);
        
        window.focus();
        window.print();

        //iframe.contents().focus();
        //iframe.contents().print();

        console.log('done');

    } catch (err) {
        throw 'PrintArea - print_content :[' + err + ']';
    }

    return false;
};
