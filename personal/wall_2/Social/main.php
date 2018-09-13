<?php
require_once('connection.php');
session_start();
if (empty($_SESSION['user'])) {
    header('Location: login.php');
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>FaceBrigadeiro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="main.css">
</head>
<body>

<!-- Navigation bar (welcome the user, log out) --> 

    <nav class="navbar navbar-light bg-light">
        <a class="navbar-brand"><strong>FaceBrigadeiro</strong></a> 
            <span>Welcome, <?php
echo $_SESSION['user']['first_name'];
?> </span>
        <form class="form-inline" action="process.php" method="POST">
            <button class="btn btn-outline-danger my-2 my-sm-0" type="submit" name="action" value="logout">Log out</button>
        </form>
    </nav>  

<!-- Messages and comments below --> 

    <div class="container">
        
<!-- Message form --> 

        <div class="formstyling">
            <form action="process.php" method="post">
                <input type="hidden" name="action" value="add_message">
                <div class="form-group">
                    <label for="exampleFormControlTextarea1"><h3>Post a message</h3></label>
                    <textarea class="form-control" name="message" id="exampleFormControlTextarea1" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Post a message</button>
            </form>
        </div> <!-- end of div formstyling-->

<!-- Display messages -->

        <div class="messages">
            <?php
$query = "SELECT messages.id, concat(users.first_name, ' ', users.last_name) as name, messages.message, messages.created_at 
                    FROM messages 
                    INNER JOIN users ON users.id = messages.user_id 
                    ORDER BY messages.created_at DESC";

$results = mysqli_query($connection, $query);
foreach ($results as $row) {
?>
                   <div class="newcomment"> 
                        <p><strong><?= $row['name'] ?> - <?= $row['created_at'] ?></strong></p>
                    </div> <!-- end of printing div -->

<!-- Display comments -->

            <div class="comments">
                <?php
    $comment_query = "SELECT concat(users.first_name, ' ', users.last_name) AS name, comments.comment, comments.created_at, comments.message_id 
                        FROM comments
                        LEFT JOIN users
                        ON comments.user_id = users.id
                        WHERE comments.message_id = '$row[id]' -- Test value 14 - it shows all comments for message id 14. 
                        ORDER BY comments.created_at ASC";
    
    $comment_results = mysqli_query($connection, $comment_query); # Fetch array? 
    foreach ($comment_results as $comment_row) {
?>
                   <div> 
                        <p><strong><?= $comment_row['name'] ?> - <?= $comment_row['created_at'] ?></strong></p>
                        <p><?= $comment_row['comment'] ?></p>
                        <p><?= "Message id: " . $comment_row['message_id'] ?></p>
                    </div>
                    <?php
    } # End of foreach to display the comments
?>
           </div> <!--  End of div class comments -->

<!-- Comment form -->

            <form action="process.php" method="post">
                <div class="forms">
                    <input type="hidden" name="action" value="add_comment">
                    <input type="hidden" name="message_id" value="<?= $row['id'] ?>">  <!-- Value has been tested -->
                    <div class="form-group">
                        <textarea class="form-control" name="comment" rows="1" placeholder="Add a comment..."></textarea>
                        <button type="submit" class="btn btn-success">Post a comment</button>
                    </div>
                </div> <!-- End of div class forms -->
            </form>
            <?php
} # End of foreach to display the messages and comment forms
?>
          



        </div> <!-- End of div class messages -->

    </div> <!-- End of div class container (bootstrap styling) --> 

</body>
</html>