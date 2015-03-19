var express = require('express')
var fs = require('fs')
var app = express();

var bodyParser = require('body-parser')
app.use( bodyParser.json({parameterLimit: 10000,
     limit: 1024 * 1024 * 10}) );       // to support JSON-encoded bodies
app.use(bodyParser.urlencoded({extended:true, parameterLimit: 10000,
     limit: 1024 * 1024 * 10}));
// app.use(express.json());       // to support JSON-encoded bodies

//CORS middleware
var allowCrossDomain = function(req, res, next) {
    res.header('Access-Control-Allow-Origin', '*');
    res.header('Access-Control-Allow-Methods', 'GET,PUT,POST,DELETE');
    res.header('Access-Control-Allow-Headers', 'Content-Type');

    next();
}

 app.use(allowCrossDomain);


app.get('/',function (req, res){
	res.send('Hello World!');
	console.log(req)
})

// app.post('/post-data',function(req,res){	
// 	// console.log(req);
// 	// console.log(req.body);
//   console.log("Received Data");
//   console.log("userId: "+req.body.userId)
//   var filename = "data/" + (new Date()).toLocaleString().replace(/\/|,| |:/g,'_') + ".json";
//   fs.writeFile(filename, JSON.stringify(req.body.data), function(){
//     console.log("Write to: " + filename);
//     // res.sendStatus(200);
//     res.send(req.body.data);
//   })	
// })
var MAX_ID = 100;

app.post('/api',function(req,res){
  console.log("\033[93m---------Request---------\033[0m")
  console.log("\033[1mAction: " + req.body.action+"\033[0m")
  console.log(req.body.data)
  

  switch (req.body.action){
    case "add_paper":
      res.status(200).send({pub_id:++MAX_ID})      
      break;
    case "delete_paper":
    case "update_paper":
      res.status(200).send({pub_id: req.body.data.pub_id})
      break;    
    default:
      res.sendStatus(501);
  }


  


})


app.post('/add_paper',function(req, res){
  console.log("Post: add_paper");
  console.log(req.body);
  res.sendStatus(200);
})

app.get('/get',function(req,res){
    res.send({abc:1});
})

var server = app.listen(3000, function () {

  var host = '127.0.0.1'
  var port = 3000

  console.log('Example app listening at http://%s:%s', host, port)

})