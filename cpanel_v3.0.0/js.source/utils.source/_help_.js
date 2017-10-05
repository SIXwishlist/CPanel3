/* 
 * 
 */

/* 
 * self excute function
 * 
 * (function() {
 * })();
 * 
 */

//I've been searching for such a possibility recently, didn't find a way to do it with selectors only. However, using the "ends with" selector with a .filter() method seems to do the trick, e.g. the following piece of code will show id of all elements which have the id beginning with "foo" and ending with "bar":

//$("[id^=foo]").filter("[id$=bar]").each( function() {
//  alert($(this).attr('id'));
//});

//$("[id^=foo]").find("[id$=bar]").each( function() {
//  alert($(this).attr('id'));
//});

//    element.find(":contains('yourstring')")   // conatins
//    $( 'select[name^=yourstring]' );          // starts with
//    $( 'select[name$="yourstring"]' );        // ends with
//
////////////////////////////////////////////////////////////////////////////////
//
////setup listener
//$('body').bind("actionName", onAction);
//
////fire event
//$('body').trigger("actionName", [ param ]);
//
//function onAction(e, param){
//    alert( 'param : ' + param );
//}
//
////////////////////////////////////////////////////////////////////////////////
//
// checkbox
//parentLI.find( 'input[name=mycheckbox]' ).attr( "checked", (someval>0) ? true : false );
// 
// radio
//parentLI.find( 'input[name=myradio]'    ).filter('[value='+someval+']').attr("checked", "checked"); // old
//parentLI.find( 'input[name=myradio]'    ).filter('[value='+someval+']').attr("checked", true);
// 
// select
//parentLI.find( 'select[name=myselect]'  ).find('option[value='+someval+']' ).attr( "selected", "selected" ); // old
//parentLI.find( 'select[name=myselect]'  ).find('option[value='+someval+']' ).attr( "selected", true );
//
//parentDiv.find( 'select[name=item]'    ).filter('[value='+someval+']').attr('selected', "selected" ); // old
//parentDiv.find( 'select[name=item]'    ).filter('[value='+someval+']').attr('selected', true);
//
////////////////////////////////////////////////////////////////////////////////