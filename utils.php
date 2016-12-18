<?php
require_once("DHandler.php");
session_start();
/* File to handle all normal printing to views. MVC view contoller/generator.
which is handled by Contorller (DHandler). */
function HTMLheader() {
/* Function to print page Header. */
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
    /* Function to print navigaion panel. */
?>
                <nav>
                    <ul>
                        <li><a href="index.php">FRONT PAGE</a></li>
                        <li><a href="index.php?p=forums">FORUM TOPICS</a></li>
                        <?php
                        if(isset($_SESSION["username"]) && isset($_SESSION["userID"])) {
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

function HTMLfooter() {
    /* Function to print page footer. */
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

function printTopics($topics) { /* Function to print topics. */
    /*Topic colums are: "tID", "tName", "tDT", "uName" */
    print "<ol id='topicList'>";
    foreach ($topics as $value) {
        print "<li class='topicName'><a href='index.php?topic=" . $value["tName"] .
         "'><strong>" . $value['tName'] . "</strong> created by: <strong>" .
         $value["uName"] . "</strong> " . $value["tDT"] . "</a></li>";
    }

    print "</ol>";  /* Topics are listed in ordered list. */
}

function printHeader($header) {  /* Function to print any h3 header. */
    $header = preg_replace('/_/', ' ', $header);
    print "<h3 class='infoHeader'>". $header ."</h3>";
}

function printPosts($posts) { /* Function to print posts. */
    /*Post infos are: "pID", "postContent", "pDT", "uName" */
    print "<ul id='postList'>";
    foreach ($posts as $post) {
        print "<li class='postListElement'><p><u>" . $post["pDT"] . " <strong>" .
        $post["uName"] . "</strong></u></p><p>" . $post["postContent"] . " </p></li>";
    }

    print "</ul>"; /* Posts are in unordered list. */
}

function PDFbutton() { /* Function to create PDF download button. Used in topic pages. */
?>
    <a id="PDFlink" href="javascript:downloadPdf()">SAVE TOPIC AS PDF</a>
<?php
}

/* Function to print topic creation form. */
function topicFieldPrint() {
print <<<TOPIC
    <h3 class="infoHeader">Or create a new topic</h3>
    <input id="topicField" type="text" name="topicName" placeholder="Topic"> </input>
    <div class="postDiv"><textarea id="postArea" rows="10" cols="150" > </textarea></div>
    <input id="addTopicB" type="button" value="Create topic" onclick="createNewTopic();"> </input>
TOPIC;
}

/* Function to print post creation form. */
function postFieldPrint() {
print "<div class='postDiv'><textarea id='postArea' rows='10' cols='150' > </textarea></div>
    <input id='addPostB' type='button' value='Send post'></input>";

}

/* Function to print login form. */
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

/* Function to print email validation form. */
function validationForm() {
print <<<VALIDATE
    <form class="block" action="index.php?p=register" method="post">
        <input  type="text" name="code" placeholder="Validation key" />
        <input id= "validateButton" type="submit" value="Submit" />
    </form>
VALIDATE;
}

/* Function to print registeration form. */
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

function infoText($content) { /* Function to print any info content. */
    print "<div class='infoText'>" . $content . "</div>";
}

function advertisement() { /* Function to create canvas element which will be fill by image. */
?>
<div id="canvasAdvertisement">
    <a href="http://www.lut.fi/"><canvas id="canvasAd" width="630" height="193"></canvas></a>
</div>
<?php
}

?>
