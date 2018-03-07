

var ec = new evercookie();

// set a cookie "id" to "12345"
// usage: ec.set(key, value)
//ec.set("identification", "123456789");

ec.get("identificatio", createCookie);


function createCookie(valeur){
  if (valeur.includes("<title>404 Not Found</title>"))
  {
    var numRand = Math.floor(Math.random() * 1000);
    ec.set("identificatio", numRand.toString());
  }

  ec.get("identificatio", function(value) { console.log("Cookie value is " + value) });
}

/*
if ((ec.get("indentification",function(value){})) == null)
{
  ec.set("identification", "12345");
  ec.get("identification", function(value) { console.log("Cookie value is " + value) });
}
else {
  ec.get("identification", function(value) { console.log("Cookie value is " + value) });
}*/

// retrieve a cookie called "id" (simply)
//ec.get("identification", function(value) { console.log("Cookie value is " + value) });

function getCookie(best_candidate, all_candidates)
{
  alert("The retrieved cookie is: " + best_candidate + "\n" +
  "You can see what each storage mechanism returned " +
  "by looping through the all_candidates object.");

  for (var item in all_candidates)
  document.write("Storage mechanism " + item +
  " returned: " + all_candidates[item] + "<br>");
}
//ec.get("id", getCookie);
