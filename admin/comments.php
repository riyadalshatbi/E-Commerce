<?php

/*
== Manages Comments Page
== You Can Edit | Delete | Approve Comments From Here
*/

ob_start(); // Output Buffering Start

session_start();

$pageTitle = 'Comments';

if(isset($_SESSION['Username']))
{   
    include 'init.php';
    
    $do = '';

if(isset($_GET['do']))
{
    $do = $_GET['do'];
    
}
else
{
    $do = 'Manage';
    
    
}
    //Start Manage Page
    
    if($do == 'Manage') //Manage Members Page
    {
        // Select All Comments
        
        $stmt = $con->prepare("SELECT 
                                    comments.* , items.Name AS Item_Name , users.Username AS Member
                                FROM 
                                    comments 
                                INNER JOIN 
                                    items 
                                ON 
                                    items.Item_ID = comments.item_id
                                INNER JOIN 
                                    users 
                                ON 
                                    users.UserID = comments.user_id 
                                ORDER BY 
                                    c_id
                                DESC ");
        
        // Execute The Statement
        
        $stmt->execute();
        
        // Assign To Variable
        
        $comments = $stmt->fetchAll();
   
        if(! empty($comments))
        {
        ?>

            <h1 class="text-center">Manage Comments</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td>ID</td>
                            <td>Comment</td>
                            <td>Item Name</td>
                            <td>User Name</td>
                            <td>Added Date</td>
                            <td>Control</td>
                        </tr>
                        <?php
                            foreach($comments as $comment)
                            {
                                echo '<tr>';
                                echo '<td>'.$comment['c_id'].'</td>';
                                echo '<td>'.$comment['comment'].'</td>';
                                echo '<td>'.$comment['Item_Name'].'</td>';
                                echo '<td>'.$comment['Member'].'</td>';
                                echo '<td>'.$comment['comment_date'].'</td>';
                                echo '<td>
                                <a href="comments.php?do=Edit&comid='.$comment['c_id'].'" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
                                <a href="comments.php?do=Delete&comid='.$comment['c_id'].'" class="btn btn-danger confirm"><i class="fa fa-close"></i> Delete </a>';
                                
                                if($comment['status'] == 0)
                                {
                                    echo '<a href="comments.php?do=Approve&comid='.$comment['c_id'].'" class="btn btn-info activate"><i class="fa fa-check"></i> Approve</a>';
                                }
                                echo '</td>';
                                echo '</tr>';
                            }
                        ?>
                        
                    </table>
                </div>
            </div>
    <?php 
        }
        else
        {
            echo '<div class="container">';
                echo '<div class="nice-message">There\'s No Comments To Show</div>';                
            echo '</div>';
        }
    ?>

    <?php
    
    }
    
    elseif($do == 'Edit') //Edit Members Page
    {
        //Check If Request Comment ID Is Numeric & Get The Integer Value Of It

        $comid = isset($_GET['comid']) && is_numeric($_GET['comid'])?intval($_GET['comid']) : 0 ;

        //Select All Data Depend On This ID

        $stmt = $con->prepare('SELECT * FROM comments WHERE c_id=?');

        //Execute Query

        $stmt->execute(array($comid));

        //Fetch The Data

        $row = $stmt->fetch();

        //The rowCount
        
        $count = $stmt->rowCount();
        
        //If There is Such ID Show The Form 
        
        if($count > 0)
        { ?>

            <!--        Edit Page-->

            <h1 class="text-center">Edit Comment</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    
                    <input type="hidden" name="comid" value="<?php echo $comid; ?>" />
                    
            <!--        Start Comment Field-->
                    <div class="form-group form-group-lg row">
                        <label class="col-sm-2 control-label">Comment</label>
                        <div class="col-sm-10 col-md-8">
                            <textarea class="form-control" name="comment"><?php echo $row['comment'] ?></textarea>
                        </div>
                    </div>
            <!--        End Comment Field-->
                    
                    <!--        Start Submit Field-->
                    <div class="form-group row">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Save" class="btn btn-primary btn-lg" />
                        </div>
                    </div>
            <!--        End Submit Field-->
                </form>
            </div>
        

    <?php
         
         //If There Is No Such ID Show Error Message
         
        }
        else
        {
            echo "<div class='container'>";
            
            $theMsg = "<div class='alert alert-danger'>there is no sush id</div>";
            
            redirectHome($theMsg);
            
            echo "</div>";
        }
    }
    elseif($do == 'Update') // Update Comment
    {
        echo '<h1 class="text-center">'."Update Member".'</h1>';
        echo "<div class='container'>";
        
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            //Get Varibles From Form
            
            $comid = $_POST['comid'];
            $comment = $_POST['comment'];
            
            //Update The DataBase with This Info 
            
            $stmt = $con->prepare("UPDATE comments SET comment = ? WHERE c_id = ?");
            $stmt->execute(array($comment,$comid));
            
            //Echo Success Message

            $theMsg = '<div class="alert alert-success">'.$stmt->rowCount().'Record Updates</div>';

            redirectHome($theMsg ,'back');            
            
        }
        else
        {
            $theMsg = '<div class="alert alert-danger">'."Sorry You Cant Browse This Page Directly".'</div>';
            
            redirectHome($theMsg);
        }
        
        echo "</div>";
        
    }
    
    elseif($do == 'Delete') // Delete Comment Page
    {
        echo '<h1 class="text-center">'."Delete Comment".'</h1>';
        echo "<div class='container'>";
        
        // Check If Request Comment ID Is Numeric & Get The Integer Value Of It

        $comid = isset($_GET['comid']) && is_numeric($_GET['comid'])?intval($_GET['comid']) : 0 ;
        
        // Select All Data Depend On This ID
                
        $check = checkItem('c_id' , 'comments' , $comid);
        
        // If There is Such ID Show The Form 

    if($check > 0)
    { 
        $stmt = $con->prepare("DELETE FROM comments WHERE c_id = :zid");
        
        // هذه أحدى الطرق اننا اربط بين الباراميترز الاثنين وارجع انفذ الاستعلام
       
        $stmt->bindParam(':zid',$comid);
        $stmt->execute();
        
        //وهذه طريقه ثانيه اننا انفذ الاستعلام واربط بين الباراميترز داخل المصفوفه
        //$stmt->execute(array(
        //'zuser' => $userid
        // ));
        
        
        // Echo Success Message
        
            $theMsg = '<div class="alert alert-success">'.$stmt->rowCount().'Record Deleted</div>';
        
            redirectHome($theMsg,'back');
    }
        
    else
    {
        $theMsg = "<div class='alert alert-danger'>Sorry This ID Is Not Exist</div>";
        
        redirectHome($theMsg);
    }

        echo '</div>';
        
  }
    
    elseif($do == 'Approve')
    {
        echo '<h1 class="text-center">'."Approve Comment".'</h1>';
        echo "<div class='container'>";
        
        // Check If Request Comment ID Is Numeric & Get The Integer Value Of It

        $comid = isset($_GET['comid']) && is_numeric($_GET['comid'])?intval($_GET['comid']) : 0 ;
        
        // Select All Data Depend On This ID
                
        $check = checkItem('c_id' , 'comments' , $comid);
        
        // If There is Such ID Show The Form 

    if($check > 0)
    { 
        $stmt = $con->prepare("UPDATE comments SET status = 1 WHERE c_id = ?");
        
        $stmt->execute(array($comid));
        
        // Echo Success Message
        
            $theMsg = '<div class="alert alert-success">'.$stmt->rowCount().'Record Approved</div>';
        
            redirectHome($theMsg,'back');
    }
        
    else
    {
        $theMsg = "<div class='alert alert-danger'>Sorry This ID Is Not Exist</div>";
        
        redirectHome($theMsg);
    }

        echo '</div>';
    }
   
    include $tpl.'footer.php';
}
else
{
    header('Location:index.php');
    exit();
}

ob_end_flush(); // Release The Output

?>