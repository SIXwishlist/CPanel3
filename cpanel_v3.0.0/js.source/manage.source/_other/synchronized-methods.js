///*
// * Synchronized methods in JavaScript
// * By sundararajan on Nov 14, 2005
// * 
// * How do we write synchronized methods in JavaScript? Rhino JavaScript shell supports a global function called sync. sync function creates a synchronized function from an existing function. The new function synchronizes on the "this" object of its invocation. Scripts executing in the shell have access this sync function. But, in Mustang's JavaScript engine, the sync function is available always (i.e., global scope is always initialized with this function).
//*/
//
//
//var obj = { f: sync(function () { 
//                     print('I am synchronized!');
//               }
//          };
//
//// 'f' is a "synchronized" method.
//obj.f(); 
//
///*
// * If you just need mutual exclusion, then sync function is enough. But, what if you need wait, notify and notifyAll? We can add these as function properties to Object.prototype as shown below:
// */
//
//
//Object.prototype.wait = function() {
//     var objClazz = java.lang.Class.forName('java.lang.Object');
//     var waitMethod = objClazz.getMethod('wait', null);
//     waitMethod.invoke(this, null);
//}
//
//Object.prototype.notify = function() {
//     var objClazz = java.lang.Class.forName('java.lang.Object');
//     var notifyMethod = objClazz.getMethod('notify', null);
//     notifyMethod.invoke(this, null);
//}
//
//Object.prototype.notifyAll = function() {
//     var objClazz = java.lang.Class.forName('java.lang.Object');
//     var notifyAllMethod = objClazz.getMethod('notifyAll', null);
//     notifyAllMethod.invoke(this, null);
//}
//
///*
// * We have added wait, notify and notifyAll to all JavaScript objects. Note that you can call the above methods only inside methods wrapped by sync. Or else you'll get IllegalMonitorStateException.
// * If you need multiple condition queues, read-write locks, semaphores, barriers and so on, then you can use java.util.concurrent API in JavaScript. (please refer to my earlier post )
// */