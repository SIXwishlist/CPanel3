
function myFunction(x) {

    try {

        console.log('start testing  try catch throw...');

        x = Number(x);

        if(x == "")  throw "is empty";

        if(isNaN(x)) throw "is not a number";

        if(x > 10)   throw "is too high";

        if(x < 5)    throw "is too low";

    } catch(err) {
        console.log( "Error: " + err );
    } finally {
        console.log('done');
    }
}