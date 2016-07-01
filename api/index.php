<?php 
/*
$servername = "localhost";
$username = "admin";
$password = "Admin";
$dbname = "movie_management";
*/
$servername = "localhost";
$username = "bradentc_movie";
$password = "something";
$dbname = "bradentc_movie_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    return "";
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
  case 'TEST':
    test();  
    break;
  case 'PUT':
    updateMovie($conn);  
    break;
  case 'POST':
    addMovie($conn);  
    break;
  case 'GET':
    getMovies($conn);  
    break;
  case 'DELETE':
    deleteMovie($conn);  
    break;
  case 'OPTIONS':
    getOptions();    
    break;
  default:
    handle_error();  
    break;
}

function test(){
  if(isset($_REQUEST['id'])){
    echo $_REQUEST['id'];
  }
  echo "TEST METHOD";

}

function handle_error() {
    echo "error!";
}

function getMovies($conn) {

    //get movie by id
    if(isset($_REQUEST["id"])){
          
          $id = $_REQUEST["id"];

          $sql = "SELECT * FROM movies WHERE movieId= '$id';";
          //$sql->mysqli_bind_param('i', $id);
          $result = $conn->query($sql);

          //echo json_encode($result);



          if ($result->num_rows > 0) {
              // output data of each row
              $data = array();
            while($row = $result->fetch_assoc())
            {
              $data[] = array("id"=>$row['movieId'],"title"=>$row['title'],"price"=>$row['price'],"studio"=>$row['studio'],"year"=>$row['year']); 
            }
            echo (json_encode($data)); 
          } else {
              echo "";
          }
          $conn->close();

    }
    else if(isset($_REQUEST["count"])){

          $sql = "SELECT COUNT(*) as total FROM movies";
          $result = $conn->query($sql);
          $data = mysql_fetch_assoc($result);
          echo $data['total'];
          

          $conn->close();
    }
    else if(isset($_REQUEST["search_title"]) || isset($_REQUEST["search_studio"])){

          $search;
          $sql;

          if(isset($_REQUEST["search_title"]) && isset($_REQUEST["search_studio"])){
            searchtitleandstudio($conn);
          }
          else if(isset($_REQUEST["search_title"]))
          {
             searchtitle($conn);
          }
          else{
             
             searchstudio($conn);
          }
             
    }
    else if(isset($_REQUEST["get_all"]))
    {
          $sql = "SELECT * FROM movies ORDER BY title";
          $result = $conn->query($sql);
          //echo json_encode($result);

          if ($result->num_rows > 0) {
              // output data of each row
              $data = array();
            while($row = $result->fetch_assoc())
            {
              $data[] = array("id"=>$row['movieId'],"title"=>$row['title'],"price"=>$row['price'],"studio"=>$row['studio'],"year"=>$row['year']); 
            }
            echo (json_encode($data)); 
          } else {
              echo "";
          }
          $conn->close();
              
      }
    else
    {
      getDOC();
    }

}
    

function deleteMovie($conn) {

    $id = $_REQUEST["id"];

    // sql to delete a record
    $sql = "DELETE FROM movies WHERE movieId='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $conn->close();
}

function searchtitleandstudio($conn){
         $title = $_REQUEST["search_title"];
         $studio = $_REQUEST["search_studio"];
             $sql = "SELECT * FROM movies WHERE title LIKE '%$tile%' AND studio LIKE '%$studio%';";
          
             
          //$sql->mysqli_bind_param('i', $id);
          $result = $conn->query($sql);

          //echo json_encode($result);

          if($result->num_rows > 0) {
              // output data of each row
              $data = array();
            while($row = $result->fetch_assoc())
            {
              $data[] = array("id"=>$row['movieId'],"title"=>$row['title'],"price"=>$row['price'],"studio"=>$row['studio'],"year"=>$row['year']); 
            }
            echo (json_encode($data)); 
          } else {
              echo "error";
          }
          $conn->close();
}

function searchtitle($conn)
{
             
             $search = $_REQUEST["search_title"];
             $sql = "SELECT * FROM movies WHERE title LIKE '%$search%';";
          
             
          //$sql->mysqli_bind_param('i', $id);
          $result = $conn->query($sql);

          //echo json_encode($result);

          if($result->num_rows > 0) {
              // output data of each row
              $data = array();
            while($row = $result->fetch_assoc())
            {
              $data[] = array("id"=>$row['movieId'],"title"=>$row['title'],"price"=>$row['price'],"studio"=>$row['studio'],"year"=>$row['year']); 
            }
            echo (json_encode($data)); 
          } else {
              echo "error";
          }
          $conn->close();
}

function searchstudio($conn){
          $search = $_REQUEST["search_studio"];
             $sql = "SELECT * FROM movies WHERE studio LIKE '%$search%';";
          
             
          //$sql->mysqli_bind_param('i', $id);
          $result = $conn->query($sql);

          //echo json_encode($result);

            if ($result->num_rows > 0) {
              // output data of each row
              $data = array();
            while($row = $result->fetch_assoc())
            {
              $data[] = array("id"=>$row['movieId'],"title"=>$row['title'],"price"=>$row['price'],"studio"=>$row['studio'],"year"=>$row['year']); 
            }
            echo (json_encode($data)); 
          } else {
              echo "";
          }
          $conn->close();
}

function addMovie($conn) {

      $title = $_REQUEST["title"];
      $year = $_REQUEST["year"];
      $studio = $_REQUEST["studio"];
      $price = $_REQUEST["price"];

      $sql = "INSERT INTO `movies` (`movieId`, `title`, `price`, `studio`, `year`) VALUES (NULL, '$title', '$price', '$studio', '$year');";

      if ($conn->query($sql) === TRUE) {
          echo "New record created successfully";
      } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
      }

      $conn->close();
}

function updateMovie($conn) {

  if(isset($_REQUEST["id"])){

      $title = $_REQUEST["title"];
      $year = $_REQUEST["year"];
      $studio = $_REQUEST["studio"];
      $price = $_REQUEST["price"];
      $id = $_REQUEST["id"];
      $sql = "UPDATE movies SET movieId=$id, title='$title' ,price=$price, studio='$studio', year=$year WHERE movieId =$id;";

      if ($conn->query($sql) === TRUE) {
          echo "Record updated successfully";
      } else {
          echo "Error: " . $sql . "<br>" . $conn->error;
      }
       $conn->close();
  }
  else{
      addMovie($conn);
  }

     
}

function getOptions() {
	echo "options";
}

function getDOC() {

  echo "<br>

  <a href='www.bradentclapp.com/AS9/api'>www.bradentclapp.com/AS9/api/</a> <br>


  METHODS FOR API<br><br>
    GET <br>
        -    All Movies : <a href='www.bradentclapp.com/AS9/api/index.php/?get_all'>www.bradentclapp.com/AS9/api/index.php/?get_all</a> <br>
        -    Single Movies by id: <a href='www.bradentclapp.com/AS9/api/index.php/?id=1'>www.bradentclapp.com/AS9/api/index.php/?id=1</a> <br>
        -    Movies with search term in Title or Studio : <a href = 'www.bradentclapp.com/AS9/api/index.php/?search_title=God'>www.bradentclapp.com/AS9/api/index.php/?search_title=God' | <br><a href='www.bradentclapp.com/AS9/api/index.php/?search_studio=Paramount'>www.bradentclapp.com/AS9/api/index.php/?search_studio=Paramount</a> | <br> <a href='http://www.bradentclapp.com/AS9/api/index.php/?search_studio=p&search_title=t'>http://www.bradentclapp.com/AS9/api/index.php/?search_studio=p&search_title=t</a> <br>
        -    If count data sent as true, number of movies in table : <a href='www.bradentclapp.com/AS9/api/index.php/?count'>www.bradentclapp.com/AS9/api/index.php/?count</a> <br><br>
    POST <br>
        -    Add Movie to table: <a href='www.bradentclapp.com/AS9/api/index.php/?title=GODFATHER&year=1992&studio=Paramount&price=11.00'>www.bradentclapp.com/AS9/api/index.php/?title=GODFATHER&year=1992&studio=Paramount&price=11.00</a> <br><br>
    PUT <br>
        -    Update Movie by id. : <a href='www.bradentclapp.com/AS9/api/index.php/?id=1&title=Fight%20Club&year=1999&studio=Paramount&price=11.00'>www.bradentclapp.com/AS9/api/index.php/?id=1&title=Fight%20Club&year=1999&studio=Paramount&price=11.00</a> <br>
        -    If movie does not exist, add movie : <a href='www.bradentclapp.com/AS9/api/index.php/?title=Fight%20Club&year=1999&studio=Paramount&price=11.00'>www.bradentclapp.com/AS9/api/index.php/?title=Fight%20Club&year=1999&studio=Paramount&price=11.00</a><br><br>
    DELETE <br>
        -    Remove Movie by id. : <a href='www.bradentclapp.com/AS9/api/index.php/?id=3'>www.bradentclapp.com/AS9/api/index.php/?id=3</a><br><br>
    OPTIONS
        The types of methods allowed by your API (GET, POST, PUT, DELETE, OPTIONS).

          ";
}






 ?>