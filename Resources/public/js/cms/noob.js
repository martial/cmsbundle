
var x = 0;


function log () {


    var x = 20;

    // j'accede a la var x que je viens de declarer pas celle en global !!
    console.log(x);

    // j'accede a mon x en global avec this
    console.log(this.x);


}

function byArg(x) {


    // j'accede a la var x en argument pas celle en global !!
    console.log(x);

    // j'accede a mon x en global avec this
    console.log(this.x);

}


