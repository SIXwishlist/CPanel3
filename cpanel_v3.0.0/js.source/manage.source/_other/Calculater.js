//Custom JavaScript objects can have instance methods (function that are associated with a particular JavaScript object), but like other Object-Oriented languages, they can also have static methods, that is functions that are associated with the JavaScript class that created an object, as opposed to the object itself. This is useful in cases where a function (a.k.a. a method) will not be different in different object instances. Let’s look at an example…

//Suppose you created a class to handle simple arithmetic calculations:

function Calculator(){
 
}

//To begin with, an instance method could be added to this class in one of two ways, either inside the constructor or through the class prototype. In this example, one method called multiply will be created, which returns the product of two values multiplied together. First, implemented in the constructor it looks like:

function Calculator() {
    this.multiply = function (val1, val2) {
        return (val1 * val2);
    };
}

//Via the class prototype, which is a more readable solution in my opinion, it would look like:

function Calculator() {
}

Calculator.prototype.multiply = function (val1, val2) {
    return (val1 * val2);
}

//Use of this method would then occur through instances of the Calculator class, like so:

var calc = new Calculator();
alert( calc.multiply(4,3) ); //pop-up alert with product of 4 times 3

//However, it shouldn’t really be necessary to create an object to use the multiply method, since the method isn’t dependent on the state of the object for its execution. The method can be moved to the class to clean up this code a bit. First the class definition is created, which looks almost identical to the instance method declaration above, with the exception of the prototype keyword being removed:

function Calculator() {
}

Calculator.multiply = function (val1, val2) {
    return (val1 * val2);
}

alert( Calculator.multiply(4,3) );