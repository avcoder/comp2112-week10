# PHP / mySQL Reference
Table of Contents goes here

## Week 2 - Saving Data
### Connect to Database
```php
// dbconnect.php
$dsn = 'mysql:host=example1.localhost;dbname=avpeace';
$usernm = 'u___name';
$passwd = 'p___word';

// connect to db - dbtype, svr adrs, dbname, username, pwd
try {
    $conn = new PDO($dsn, $usernm, $passwd);

    // display error messages connecting to db, if any.
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

catch (PDOException $error) {
    echo "There was a problem connecting to db";
    echo $error->getMessage();
}

```
### Send Email
```html
<!-- enter-email.php -->
<form method="post" action="send-email.php">
    <fieldset class="form-group">
        <label for="email">Email:</label>
        <input name="email" id="email" type="email" />
        <button class="btn btn-primary">Send</button>
```
```php
// send-email.php
<?php 
$email = $_POST['email'];
mail($email, 'PHP email test', 'Sending email from PHP page');
echo 'Email sent';
?>
```

### Create mySQL Table
```sql
USE gcxxxxxxxxx;

CREATE TABLE movies (
title VARCHAR(50), year INT, length INT, url VARCHAR(100));

INSERT INTO movies VALUES
('Everest', 2015, 121, 'http://www.everestmovie.com'), 
('Black Mass', 2015, 122, 'http://www.blackmassthemovie.com');
```

### Building the Form
```html
<!-- movie.php -->
<form method="post" action="save-movie.php">
    <fieldset class="col-sm-9">
        <legend>Enter Movie</legend>
    <div class="form-group">
        <label for="title">Title</label>
        <input type="text" class="form-control" id="title" name="title" required">
    </div>
    <div class="form-group">
        <label for="year">Year</label>
        <input type="number" class="form-control" id="year" name="year">
    </div>
    </fieldset>
        <button type="submit" class="offset-sm-3 btn btn-primary">Submit</button>
```
### Saving to Database
```php
// save-movie.php
$title = $_POST['title']; 
$year = $_POST['year']; 

// OR more securely...

$title = trim(filter_input(INPUT_POST, 'title'));
$year = trim(filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT));
$url = trim(filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED));
```
### Build SQL and Execute
```php
// set up an SQL instruction to save the new album - INSERT
$sql = "INSERT INTO movies (title, year) VALUES (:title, :year)";

// pass the input vars to the SQL command
$cmd = $conn->prepare($sql);
$cmd->bindParam(':title', $title, PDO::PARAM_STR, 50);
$cmd->bindParam(':year', $year, PDO::PARAM_INT);

// execute the insert
$cmd->execute();

// disconnect
$conn = null;
```

### Check mySQL Database to see Inserts
```sql
USE gcxxxxxxxx;
SELECT * FROM movies;
```

### Sending Email
```html
<!-- enter-email.php -->
<form method="post" action="send-email.php">
    <fieldset class="form-group">
        <label for="email">Email:</label>
        <input name="email" id="email" type="email" />
        <button class="btn btn-primary">Send</button>
```
```php
// get email adrs
$email = $_POST['email'];

// send test msg
mail($email, 'This is Subject Line', 'This is body');
```

## Week 3 - Retrieving Data
### Select/Dropdown Menu
```php
// select-movie.php fills a dropdown menu
<h1>Select a Movie</h1>
<form method="post" action="display-movie.php">
    <fieldset>
        <label for="title">Title:</label>
        <select name="title" id="title">

<?php
// get movie titles and fill dropdown
$sql = "SELECT title FROM movies ORDER BY title";

// prepare sql
$cmd = $conn->prepare($sql);
$cmd->execute();
$movies = $cmd->fetchAll();

// loop through array and populate dropdown
foreach($movies as $movie) {
    echo '<option>' . $movie['title'] . '</option>';
}
?>

        </select>
        <button>See Movie Details</button>
```
```php
// display-movie.php displays selection from above dropdown
<h1>Movie Details</h1>
<?php
    // get selected title
    $title = $_POST['title'];

    // set query
    $sql = "SELECT * FROM movies WHERE title = :title";
    $cmd = $conn->prepare($sql);

    // before execute queyr, must inject title first
    $cmd->bindParam(':title', $title, PDO::PARAM_STR, 50);
    $cmd->execute();
    $movie = $cmd->fetch();

    echo 'Title: ' . $movie['title'] . '<br />';
    echo 'Year: ' . $movie['year'] . '<br />';

    // disconnect
    $conn = null;
```

### Displaying Data from mySQL table
```php
// movies.php
$sql = "SELECT * FROM movies";
$cmd = $conn->prepare($sql);
$cmd->execute();
$movies = $cmd->fetchAll();
$conn = null;

// start table and headings + css framework containers
echo '<h1>Movies</h1>';
echo '<div class="container">
      <div class="row justify-content-left">
      <div class="col-8">';
echo '<table>
        <thead class="thead-inverse">
        <tr>
            <th>Title</th>
            <th>Year</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
        </thead><tbody>';

// loop through data
foreach ($movies as $movie) {

    // print each album as a new row
    echo '<tr><td>' . $movie['title'] . '</td>
            <td>' . $movie['year'] . '</td>';
}
```

## Week 4 - Input Validation
### Client-side Validation
```html
<!-- movie.php -->
<input name="title" id="title" required />
</fieldset>
    <fieldset>
    <label for="year">Year:</label>
    <input name="year" id="year" required type="number" />
</fieldset>
    <fieldset>
    <label for="length">Length:</label>
    <input name="length" id="length" required type="number" />
</fieldset>
    <fieldset>
    <label for="url">URL:</label>
    <input name="url" id="url" required type="url" />
```
### Server-side Validation
```php
// save-movie.php
// create a variable to indicate if the form data
// is ok to save or not
$ok = true;

// check each value
if (empty($title)) {
    // notify the user
    echo 'Title is required<br />';
    
    // change $ok to false so we know not to save
    $ok = false;
}
...

// check the $ok variable, save the data if $ok is still true 
// (meaning we didn't find any errors)
if ($ok == true) {
   // move all the save code we wrote last week in here, 
   // starting with the database connection and 
   //ending with the disconnect command
}



if ($is_formValid == true) {

    // Here is where we decide if it's an UPDATE or 
    // an INSERT - see line 9: 
    // $movie_id = $_POST['movie_id']
    if (empty($movie_id)) {
        // set up an SQL instruction to save the new album - INSERT
        $sql = "INSERT INTO movies (title, year, length, url, photo) 
        VALUES (:title, :year, :length, :url, :photo)";
    } else {
        // set up UPDATE sql
        $sql = "UPDATE movies SET title = :title, 
        year = :year, length = :length, 
        url = :url, photo = :photo 
        WHERE movie_id = :movie_id";
    }


    // pass the input vars to the SQL command
    $cmd = $conn->prepare($sql);
    $cmd->bindParam(':title', $title, PDO::PARAM_STR, 50);
    $cmd->bindParam(':year', $year, PDO::PARAM_INT);
    $cmd->bindParam(':length', $length, PDO::PARAM_INT);
    $cmd->bindParam(':url', $url, PDO::PARAM_STR, 100);
    $cmd->bindParam(':photo', $photo_path, PDO::PARAM_STR, 100);

    // if this is an UPDATE, if so, fill movie_id parameter (from hidden form field) so db knows which record to update
    if (!empty($movie_id)) {
        $cmd->bindParam('movie_id', $movie_id, PDO::PARAM_INT);
    }
```

## Week 5 - Changing and Removing Data
### Delete
```html
<a href="delete-movie.php?movie_id=' 
    . $movie['movie_id'] 
    . '" onclick="return confirm(\'Sure?\');">Delete</a>
```
```php
// in delete.movie.php
    $movie_id = $_GET['movie_id'];

    // set the query command
    $sql = "DELETE FROM movies WHERE movie_id = :movie_id";

    // pass the input vars to the sql command
    $cmd = $conn->prepare($sql);
    $cmd->bindParam(':movie_id', $movie_id, PDO::PARAM_INT);

    // execute sql
    $cmd->execute();

    // disconnect
    $conn = null;

    // redirect user to another page
```

### Edit
```html
// within movies.php
<a href="movie.php?movie_id=' 
    . $movie['movie_id'] . '">Edit</a>
```
```html
// within movie.php, change each input value to php echos
<input type="text" class="form-control" id="title" 
name="title" required value="<?php echo $title; ?>">
...
<input type="hidden" name="movie_id" 
value="<?php echo $movie_id; ?>">
```
```php
// within movie.php
if (empty($_GET['movie_id']) == false) {
    $movie_id = $_GET['movie_id'];

    // write sql query
    $sql = "SELECT * FROM movies WHERE movie_id = :movie_id";

    // execute query
    $cmd = $conn->prepare($sql);
    $cmd->bindParam('movie_id', $movie_id, PDO::PARAM_INT);
    $cmd->execute();
    $movie = $cmd->fetch();

    // populate fields
    $title = $movie['title'];
    ...
    $photo = $movie['photo'];

    // disconnect
    $conn = null;
}
```
```php
// update within save-movie.php

// check if form is a result of an update (vs. new record creation)
// this value will be used later to know whether to UPDATE or INSERT
$movie_id = $_POST['movie_id'];

// use filter_input before saving form values
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)) {

    // capture form values
    $title = trim(filter_input(INPUT_POST, 'title'));
    $year = trim(filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT));
    $length = trim(filter_input(INPUT_POST, 'length', FILTER_VALIDATE_INT));
    $url = trim(filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED));
}
```
## Week 6 - Authentication
### Registering Users
```mysql 
CREATE TABLE users 
(user_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(50) NOt NULL,
password VARCHAR(255) NOT NULL);
```
```html
// within register.php
<form method="post" action="save-registration.php">
    <fieldset class="form-group">
        <label for="username">Email:*</label>
        <input name="username" id="username" required class="form-control" type="email"/>

        <label for="password">Password:*</label>
        <input type="password" name="password" id="password" required class="form-control"/>

        <label for="confirm">Confirm Password:*</label>
        <input type="password" name="confirm" id="confirm" required class="form-control"/>
    </fieldset>
    <button class="btn btn-success offset-sm-4">Register</button>
</form>
```
```php
// within save.registration.php
// get the form inputs
$username = $_POST['username'];
$password = $_POST['password'];
$confirm = $_POST['confirm'];

$is_PasswordOK = true;



// validate inputs
if (empty($username)) {
    echo 'Username is required';
    $is_PasswordOK = false;
}

if (empty($password)) {
    echo "Password is required";
    $is_PasswordOK = false;
}

if ($password != $confirm) {
    echo "Passwords do not match";
    $is_PasswordOK = false;
}


// hash password
if ($is_PasswordOK == true) {
    $password = password_hash($password, PASSWORD_DEFAULT);

    // set up and execute query
    $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";

    $cmd = $conn->prepare($sql);
    $cmd->bindParam(':username', $username, PDO::PARAM_STR, 50);
    $cmd->bindParam(':password', $password, PDO::PARAM_STR, 256);
    $cmd->execute();

    // disconnect
    $conn = null;

    // show msg to user
    echo 'Registration successful';

    // registration success, store session var then redirect
    $_SESSION['user_id'] = $username;
    header('location:menu.php');
}
```
```html
// within login.php
<form method="post" action="validate.php">
    <fieldset class="form-group">
        <label for="username">Email:*</label>
        <input name="username" id="username" required class="form-control" type="email"/>
...
    <button class="btn btn-success offset-sm-4">Login</button>
</form>
```
```php
// within validate.php
$username = $_POST['username'];
$password = $_POST['password'];

// set up sql and execute
$sql = "SELECT * FROM users WHERE username = :username";

$cmd = $conn->prepare($sql);
$cmd->bindParam('username', $username, PDO::PARAM_STR, 50);
$cmd->execute();
$user = $cmd->fetch();

echo "password is " . $password;
echo "hashed password is " . $user['password'];
echo "password_verify is " . password_verify($password, $user['password']);

// if count is 1 then we found a matching username in db.  Check pwd.
if (password_verify($password, $user['password'])) {

    // user found, store session var then redirect
    $_SESSION['user_id'] = $user['user_id'];
    header('location:menu.php');

} else {

    // user not found
    header('location:login.php?invalid=true');
    exit();
}

// disconnect
$conn = null;
```
```php
// add session check to movies.php, movie.php, 
// save-movie.php, delete-movie.php etc 

session_start();
// check if there is a user identity stored in session var
if (empty($_SESSION['user_id'])) {

    // since user didn't login yet, we'll redirect them
    header('location:login.php');
    exit();
}
```
## Week 8 - Code Re-use and Logout.php
### auth.php
```php
// within auth.php
session_start();
// check if there is a user identity stored in session var
if (empty($_SESSION['user_id'])) {

    // since user didn't login yet, we'll redirect them
    header('location:login.php');
    exit();
}

```
### db.php
```php
$dsn = 'mysql:host=example1.localhost;dbname=avpeace';
$usernm = 'root';
$passwd = 'mustardseed';

// connect to db - dbtype, svr adrs, dbname, username, pwd
try {
    $conn = new PDO($dsn, $usernm, $passwd);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

catch (PDOException $error) {
    echo "There was a problem connecting to db";
    echo $error->getMessage();
}
```

### header.php
```html
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $page_title; ?></title>
    <!-- css -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
</head>
<body>
```
### footer.php
```html
...
</footer>

<!-- js -->
    <script src="js/jquery-3.1.1.slim.min.js"></script>
    <script src="js/tether.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
```

### Logout
```php
// within logout.php
<?php ob_start();

session_start();

// remove session variables
session_unset();

// end session
session_destroy();

// redirect
header('location:login.php');

ob_flush();
?>
```
## Week 9 - Error Handling, Send Email
### 404.php
```html
<?php
    $page_title = null;
    $page_title = 'Error';

    include_once 'header.php';
?>

<main class="container">
    <h1>Oops!</h1>
    <p class="jumbotron-fluid">Sorry but we can't find the page you requested.  Please try one of the links above instead.</p>
</main>


<?php include_once 'footer.php'; ?>
```
```xml
// within .htaccess, saved in root folder
// test by going to /nopage.php
ErrorDocument 404 http://example1.localhost/404.php

// if using Azure, don't use .htaccess, 
// rather use web.config
<?xml version="1.0" encoding="UTF-8"?>
<configuration>
 <system.web>
  <customErrors defaultRedirect="error.php" mode="On">
   <error redirect="404.php" statusCode="404" />
  <customErrors>
 </system.web>
 <system.webServer>
  <!-- 404 handling for everything not .aspx -->
  <httpErrors errorMode="Custom">
   <clear/>
   <error statusCode="404" responseMode="Redirect" path="404.php"/>
  </httpErrors>
 </system.webServer>
</configuration>
```
### db PDO errors
```php
// in db.php, add 1 setAttr line below new connection 
    $conn = new PDO($dsn, $usernm, $passwd);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
```
```html
// within error.php 
<?php

$page_title = null;
$page_title = 'COMP1006 App - Yikes';

    include_once 'header.php';
?>

<main class="">
    <h1>We're Sorry!</h1>
    <p class="jumbotron-fluid">Unexpected...are on it.</p>
</main>


<?php include_once 'footer.php' ?>
```
### Try / Catch blocks / Sending Email
```php
try {

}
catch (Exception $e) {
    //mail('av@gmail.com', 'Web App Error', $e);
    header('location:error.php');    
}
```

## Week 10 - File Uploads
```html
// within upload.php
<form method="post" action="save-upload.php" enctype="multipart/form-data">
    <fieldset>
        <legend>Upload a File (.jpg)</legend>
        <label for="upload">1. Select file:</label>
        <input type="file" name="upload"><br /><br />
        <button class="btn btn-primary">2. Upload File</button>
    </fieldset>
</form>
```
```php
// within save-upload.php
// get name of uploaded file
$name = $_FILES['upload']['name'];
echo "name is $name <br />";

// get size of uploaded file
$size = $_FILES['upload']['size'];
echo "size is $size <br />";

// get type based on file extension
$type = $_FILES['upload']['type'];
echo "type is $type <br />";

// get temporary location of file in the server cache
$tmp_name = $_FILES['upload']['tmp_name'];
echo "Tmp name is $tmp_name <br />";

// move the file from its temp location to uploads dir
move_uploaded_file($tmp_name, "uploads/$name");
```

## Week 11 - Search / Sort
```html
// within header.php
<li class="nav-item navbar-toggler-right">
    <form method="get" action="movies.php" class="form-inline">
        <select name="search_type">
            <option value="OR">Any Keyword</option>
            <option value="AND">All Keywords</option>
        </select>
        <label class="sr-only" for="inlineFormInput">Name</label>
        <input type="text" class="form-control mb-2 mr-sm-2 mb-sm-0" id="keywords" name="keywords" value="<?php echo $keywords; ?>">
        <button class="btn btn-primary">Search</button>
    </form>
</li>
```
```php
// within movies.php
try {

    // check if user is searching or not.  If yes, then GET's keyword, is not empty
    $keywords = null;
    if(!empty($_GET['keywords'])) {
        $keywords = $_GET['keywords'];
    }

    $sql = "SELECT * FROM movies";
    $word_list = null;


    // check if the user entered keywords for searching
    if (!empty($keywords)) {

        // start the WHERE clause MAKING SURE to include spaces around the word WHERE
        $sql .= " WHERE ";


        // split keywords into an array
        $word_list = explode(" ", $keywords);


        // start a counter so we know which element in the array we are at
        $i = 0;


        // did user select Any keyword, or All keywords from dropdown menu?
        $search_type = $_GET['search_type'];

        foreach($word_list as $word) {

            $word_list[$i] = "%" . $word . "%";

            // for the first word OMIT the word OR
            if ($i == 0) {
                $sql .= " title LIKE ?";
            } else {
                $sql .= " $search_type title LIKE ?";
            }

            $i++;
        }
    }


    // execute query, store results, passing the $word_list array to substitute the ? in sql statement above.
    $cmd = $conn->prepare($sql);
    $cmd->execute($word_list);

    $movies = $cmd->fetchAll();
```
## Week 12 - API
### JSON encode
```php
// within /api/movies.php
<?php

require_once '../dbconnect.php';

// check if user is searching or not.  If yes, then GET's keyword, is not empty
$keywords = null;
if(!empty($_GET['title'])) {
    $keywords = $_GET['title'];
}

$sql = "SELECT * FROM movies";

if (!empty($keywords)) {

    // start the WHERE clause MAKING SURE to include spaces around the word WHERE
    $sql .= " WHERE ";


    // split keywords into an array
    $word_list = explode(" ", $keywords);


    // start a counter so we know which element in the array we are at
    $i = 0;

    foreach($word_list as $word) {

        $word_list[$i] = "%" . $word . "%";

        // for the first word OMIT the word OR
        if ($i == 0) {
            $sql .= " title LIKE ?";
        } else {
            $sql .= " AND title LIKE ?";
        }

        $i++;
    }


    // execute query, store results, passing the $word_list array to substitute the ? in sql statement above.
    $cmd = $conn->prepare($sql);
    $cmd->execute($word_list);

    $movies = $cmd->fetchAll(PDO::FETCH_ASSOC);



} else {

    $cmd = $conn->prepare($sql);
    $cmd->execute();
    $movies = $cmd->fetchAll(PDO::FETCH_ASSOC);
}

// convert to json
$json_movies = json_encode($movies);

// output json
echo $json_movies;

// disconnect
$conn = null;


?>
```