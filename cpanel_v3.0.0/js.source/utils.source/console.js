//alert("debug start");

/// this is for IE and other browsers w/o console

if (!window.console) console = {};
console.log   = console.log   || function(){};
console.warn  = console.warn  || function(){};
console.error = console.error || function(){};
console.info  = console.info  || function(){};
console.debug = console.debug || function(){};
console.clear = console.clear || function(){};

//console.log("الحمد لله");

//
//console.clear();
//console.log   ( "log"   );
//console.warn  ( "warn"  );
//console.error ( "error" );
//console.info  ( "info"  );
//console.debug ( "debug" );
//console.clear();


//alert("debug end");