<?php
require_once("DHandler.php");
session_start();
function HTMLheader() {
 ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8" />
            <title>ForumOfGreenShades</title>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
            <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css" />
            <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
            <script src='https://www.google.com/recaptcha/api.js'></script>
            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.0.272/jspdf.debug.js"></script>
            <link type="text/css" rel="stylesheet" href="forum.css" />
            <script src="javaS.js"> </script>
            <!--script src="ToDo.js"></script-->
        </head>
      <body>
          <div id="wholePage">
              <div id="headerDiv">
                  <header id="siteHeader">
                      <h1>Forum of Green Shades</h1>
                  </header>

<?php
}
function HTMLnavigation() {
?>
                <nav>
                    <ul>
                        <li><a href="index.php">FRONT PAGE</a></li>
                        <li><a href="index.php?p=forums">FORUM TOPICS</a></li>
                        <li><a href="index.php?p=ajax">STATISTCS</a></li>
                        <?php
                        if(isset($_SESSION["username"])) {
                            print "<li class='loginLinks'><a href='index.php?p=logout'>LOGOUT</a></li>
                            <li id='hello' class='loginLinks'>HELLO {$_SESSION['username']}!</li>";

                        } else {
                            print '<li class="loginLinks"><a href="index.php?p=register">REGISTER</a></li>
                            <li class="loginLinks"><a href="index.php?p=login">LOGIN</a></li>';
                        }
                        ?>
                    </ul>
                </nav>

<?php
}
function HTMLlist() {
?>
            </div>
                <div id="border">

                    <form action="index.php?p=adder" method="post" class="form">
                        <input id="inputField" type="text" name="textInput" placeholder="Write note" />
                        <input id="button" type="submit" value="Add">
                    </form>

                    <br/><br/><div class="list"></div>
<?php
}
function HTMLfooter() {
?>
                    <footer>
                        <p><strong>WWW-sovellukset kurssin harjoitustyö, Roope Luukkainen, 2016 </strong></p>
                    </footer>
                </div>
            </div>
        </body>
    </html>

<?php
}

function printTopics($topics) {
    /*Topic rows are: "tID", "tName", "tDT", "users.uName" */
    print "<h3>Select post to read</h3>";
    print "<ol>";
    foreach ($topics as $value) {
        print "<li class='topicName'><a href='index.php?p=readTopic>" .
        $value['tName'] . " created by: " . $value["uName"] . " " . $value["tDT"] . "</a></li>";
    }

    print "</ol>";
}

function topicFieldPrint() {
print <<<TOPIC
    <h3>Or create new a post</h3>
    <input id="topicField" type="text" name="topicName" placeholder="Topic" </input>
    <textarea id="postArea" rows="10" cols="100"> </textarea>
    <input id="addTopicB" type="button" value="Create topic" onclick="createNewTopic();" </input>
TOPIC;
}

function postFieldPrint() {
print <<<POSTING
    <textarea id="postArea" rows="10" cols="100"> </textarea>
    <input id="addPostB" type="button" value="Send post" onclick="createNewPost();" </input>
POSTING;
}

function loginForm() {
print <<<LOGIN
<form class="block" action="index.php?p=login" method="post">
    <input  type="text" name="username" placeholder="Username" />
    <input type="password" name="password" placeholder="Password" />
    <div id = "recaptchaID" class="g-recaptcha" data-sitekey="6Ldz9A4UAAAAAADYkCFsNDJbkU-DvyKHtgfpwl_q"></div>
    <input type="submit" value="Login" />
</form>
LOGIN;
}

function validationForm() {
print <<<VALIDATE
    <form class="block" action="index.php?p=register" method="post">
        <input  type="text" name="code" placeholder="Validation key" />
        <input id= "validateButton" type="submit" value="Submit" />
    </form>
VALIDATE;
}

function regForm() {
print <<<REGISTRATION
    <form class="block" action="index.php?p=register" method="post">
        <input type="text" name="username" placeholder="Username" />
        <input type="password" name="password" placeholder="Password" />
        <input type="password" name="password_again" placeholder="Password again" />
        <input type="text" name="email" placeholder="Email" />
        <input type="text" name="realname" placeholder="Real name" />
        <div id = "recaptchaID" class="g-recaptcha" data-sitekey="6Ldz9A4UAAAAAADYkCFsNDJbkU-DvyKHtgfpwl_q"></div>
        <input id = "regButton" type="submit" value="Create an account" />
    </form>
REGISTRATION;
}

function infoText($content) {
    print "<div class='infoText'>" . $content . "</div>";
}

function generateValue($lenght) {
    return bin2hex(openssl_random_pseudo_bytes($lenght));
}

function advertisement() {
?>
<div id="canvasAdvertisement">
    <a href="http://www.lut.fi/"><canvas id="canvasAd" width="630" height="193"></canvas></a>
</div>
<?php
}

?>
