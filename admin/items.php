<?php

/*
===============================
== Items Page
===============================
*/

ob_start(); // Output Buffering Start

session_start();

$pageTitle = 'Items';

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
    
    if($do == 'Manage')
    {
        $stmt = $con->prepare("SELECT 
                                    items.*, 
                                    categories.Name AS category_name , 
                                    users.Username 
                                FROM 
                                    items
                                INNER JOIN 
                                    categories 
                                ON 
                                    categories.ID = items.Cat_ID
                                INNER JOIN 
                                    users 
                                ON 
                                    users.UserID = items.Member_ID 
                                ORDER BY 
                                    Item_ID 
                                DESC ");
        
        // Execute The Statement
        
        $stmt->execute();
        
        // Assign To Variable
        
        $items = $stmt->fetchAll();
        
        if(! empty($items))
        {
            
        ?>

            <h1 class="text-center">Manage Items</h1>
            <div class="container">
                <div class="table-responsive">
                    <table class="main-table text-center table table-bordered">
                        <tr>
                            <td>#ID</td>
                            <td>Name</td>
                            <td>Description</td>
                            <td>Price</td>
                            <td>Adding Date</td>
                            <td>Category</td>
                            <td>Username</td>
                            <td>Control</td>
                        </tr>
                        <?php
                            foreach($items as $item)
                            {
                                echo "<tr>";
                                echo "<td>".$item['Item_ID']."</td>";
                                echo "<td>".$item['Name']."</td>";
                                echo "<td>".$item['Description']."</td>";
                                echo "<td>".$item['Price']."</td>";
                                echo "<td>".$item['Add_Date']."</td>";
                                echo "<td>".$item['category_name']."</td>";
                                echo "<td>".$item['Username']."</td>";
                                echo "<td>
                                <a href='items.php?do=Edit&itemid=".$item['Item_ID']."' class='btn btn-success'><i class='fa fa-edit'></i> Edit</a>
                                <a href='items.php?do=Delete&itemid=".$item['Item_ID']."' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
                                if($item['Approve'] == 0)
                                {
                                    echo '<a href="items.php?do=Approve&itemid='.$item['Item_ID'].'" class="btn btn-info activate"><i class="fa fa-check"></i> Approve</a>';
                                }
                                echo "</td>";
                                echo "</tr>";
                            }
                        ?>
                        
                    </table>
                </div>
                <a href="items.php?do=Add" class="btn btn-sm btn-primary">
                    <i class="fa fa-plus"></i> New Item
                </a>
            </div>
    <?php 

        }
        else
        {
            echo '<div class="container">';
                echo '<div class="nice-message">There\'s No Items To Show</div>';
                echo '<a href="items.php?do=Add" class="btn btn-sm btn-primary">
                    <i class="fa fa-plus"></i> New Item
                    </a>';
            echo '</div>';
        }
    ?>

    <?php
    }
    
    elseif($do == 'Add')
    {
?>
        <h1 class="text-center">Add New Item</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="POST">
            <!--        Start Name Field-->
                    <div class="form-group form-group-lg row">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" name="name" class="form-control" required="required" placeholder="Name of The Item"  />
                        </div>
                    </div>
            <!--        End Name Field-->
                    
                    <!--        Start Description Field-->
                    <div class="form-group form-group-lg row">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" name="description" class="form-control" required="required" placeholder="Description of The Item"  />
                        </div>
                    </div>
            <!--        End Description Field-->
                    
                    <!--        Start Price Field-->
                    <div class="form-group form-group-lg row">
                        <label class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" name="price" class="form-control" required="required" placeholder="Price of The Item"  />
                        </div>
                    </div>
            <!--        End Price Field-->
                    
                    <!--        Start Country Field-->
                    <div class="form-group form-group-lg row">
                        <label class="col-sm-2 control-label">Country</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" name="country" class="form-control" required="required" placeholder="Country of Made"  />
                        </div>
                    </div>
            <!--        End Country Field-->
                    
                    <!--        Start Status Field-->
                    <div class="form-group form-group-lg row">
                        <label class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-10 col-md-8">
                            <select name="status">
                                <option value="0">...</option>
                                <option value="1">New</option>
                                <option value="2">Like New</option>
                                <option value="3">Used</option>
                                <option value="4">Very Old</option>
                            </select>
                        </div>
                    </div>
            <!--        End Status Field-->
                    
                    <!--        Start Members Field-->
                    <div class="form-group form-group-lg row">
                        <label class="col-sm-2 control-label">Member</label>
                        <div class="col-sm-10 col-md-8">
                            <select name="member">
                                <option value="0">...</option>
                                <?php
                                    $stmt = $con->prepare("SELECT * FROM users");
                                    $stmt->execute();
                                    $users = $stmt->fetchAll();
                                    foreach($users as $user)
                                    {
                                        echo "<option value='".$user['UserID']."'>".$user['Username']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
            <!--        End Members Field-->
                    
                    <!--        Start Categories Field-->
                    <div class="form-group form-group-lg row">
                        <label class="col-sm-2 control-label">Category</label>
                        <div class="col-sm-10 col-md-8">
                            <select name="category">
                                <option value="0">...</option>
                                <?php
                                    $stmt2 = $con->prepare("SELECT * FROM categories");
                                    $stmt2->execute();
                                    $cats = $stmt2->fetchAll();
                                    foreach($cats as $cat)
                                    {
                                        echo "<option value='".$cat['ID']."'>".$cat['Name']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
            <!--        End Categories Field-->

                    <!--        Start Submit Field-->
                    <div class="form-group row">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Add Item" class="btn btn-primary btn-sm" />
                        </div>
                    </div>
            <!--        End Submit Field-->
                </form>
            </div>

<?php
    }
    
    elseif($do == 'Insert')
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            echo '<h1 class="text-center">'."Insert Item".'</h1>';
            echo "<div class='container'>";
            
            //Get Varibles From Form
            
            $name    = $_POST['name'];
            $desc    = $_POST['description'];
            $price   = $_POST['price'];
            $country = $_POST['country'];            
            $status  = $_POST['status'];
            $member  = $_POST['member'];
            $cat  = $_POST['category'];
            
            //Validate The Form
            
            $formErrors = array();
            
            if(empty($name))
            {
                $formErrors[] = 'Nmae Cant Be <strong>Empty</strong>';
            }
            if(empty($desc))
            {
                $formErrors[] = 'Description Cant Be <strong>Empty</strong>';
            }
            if(empty($price))
            {
                $formErrors[] = 'Price Cant Be <strong>Empty</strong>';
            }
            if(empty($country))
            {
                $formErrors[] = 'Country Cant Be <strong>Empty</strong>';
            }
            if($status == 0)
            {
                $formErrors[] = 'You Must Choose The <strong>Status</strong>';
            }
            if($member == 0)
            {
                $formErrors[] = 'You Must Choose The <strong>Member</strong>';
            }
            if($cat == 0)
            {
                $formErrors[] = 'You Must Choose The <strong>Category</strong>';
            }
            
            //Loop Into Error Array And Echo It
            
            foreach($formErrors as $error)
            {
                echo '<div class="alert alert-danger">'.$error.'</div>';
            }
            
            //Check There is No Error Proceed The Update Operation
            
            if(empty($formErrors))
            {
                
                // Insert User Info In DataBase
                
                $stmt = $con->prepare('INSERT INTO items (Name, Description, Price, Country_Made, Status, Add_Date, 	Cat_ID, Member_ID) VALUES (:zname,:zdesc,:zprice,:zcountry,:zstatus,now(),:zcat,:zmember)');
                
                $stmt->execute(array(
                    'zname'    => $name,
                    'zdesc'    => $desc,
                    'zprice'   => $price,
                    'zcountry' => $country,
                    'zstatus'  => $status,
                    'zcat'     => $cat,
                    'zmember'  => $member
                ));
            
            
            // Echo Success Message
                    
                $theMsg = '<div class="alert alert-success">'.$stmt->rowCount().'Record Inserted</div>';
                    redirectHome($theMsg,'back');
                 
            }
            
        }
        else
        {
            echo "<div class='container'>";
            
            $theMsg = '<div class="alert alert-danger">Sorry You Cant Browse This Page Directly</div>';
            redirectHome($theMsg);
            
            echo "</div>";
        }
        
        echo "</div>";
    }
    
    elseif($do == 'Edit')
    {
        //Check If Request item Is Numeric & Get The Integer Value Of It

        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid'])?intval($_GET['itemid']) : 0 ;

        //Select All Data Depend On This ID

        $stmt = $con->prepare('SELECT * FROM items WHERE Item_ID=?');

        //Execute Query

        $stmt->execute(array($itemid));

        //Fetch The Data

        $item = $stmt->fetch();

        //The rowCount
        
        $count = $stmt->rowCount();
        
        //If There is Such ID Show The Form 
        
        if($count > 0)
        { ?>

            <h1 class="text-center">Edit Item</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="POST">
                    
                    <input type="hidden" name="itemid" value="<?php echo $itemid; ?>" />
                    
            <!--        Start Name Field-->
                    <div class="form-group form-group-lg row">
                        <label class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" name="name" class="form-control" required="required" placeholder="Name of The Item" value="<?php echo $item['Name'] ?>" />
                        </div>
                    </div>
            <!--        End Name Field-->
                    
                    <!--        Start Description Field-->
                    <div class="form-group form-group-lg row">
                        <label class="col-sm-2 control-label">Description</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" name="description" class="form-control" required="required" placeholder="Description of The Item" value="<?php echo $item['Description'] ?>" />
                        </div>
                    </div>
            <!--        End Description Field-->
                    
                    <!--        Start Price Field-->
                    <div class="form-group form-group-lg row">
                        <label class="col-sm-2 control-label">Price</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" name="price" class="form-control" required="required" placeholder="Price of The Item" value="<?php echo $item['Price'] ?>" />
                        </div>
                    </div>
            <!--        End Price Field-->
                    
                    <!--        Start Country Field-->
                    <div class="form-group form-group-lg row">
                        <label class="col-sm-2 control-label">Country</label>
                        <div class="col-sm-10 col-md-8">
                            <input type="text" name="country" required="required" class="form-control" placeholder="Country of Made" value="<?php echo $item['Country_Made'] ?>" />
                        </div>
                    </div>
            <!--        End Country Field-->
                    
                    <!--        Start Status Field-->
                    <div class="form-group form-group-lg row">
                        <label class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-10 col-md-8">
                            <select name="status">
                                <option value="1" <?php if($item['Status'] == 1) { echo 'selected'; } ?> >New</option>
                                <option value="2" <?php if($item['Status'] == 2) { echo 'selected'; } ?> >Like New</option>
                                <option value="3" <?php if($item['Status'] == 3) { echo 'selected'; } ?> >Used</option>
                                <option value="4" <?php if($item['Status'] == 4) { echo 'selected'; } ?> >Very Old</option>
                            </select>
                        </div>
                    </div>
            <!--        End Status Field-->
                    
                    <!--        Start Members Field-->
                    <div class="form-group form-group-lg row">
                        <label class="col-sm-2 control-label">Member</label>
                        <div class="col-sm-10 col-md-8">
                            <select name="member">
                                <?php
                                    $stmt = $con->prepare("SELECT * FROM users");
                                    $stmt->execute();
                                    $users = $stmt->fetchAll();
                                    foreach($users as $user)
                                    {
                                        echo "<option value='" . $user['UserID'] . "'"; 
                                        if($item['Member_ID'] == $user['UserID']) { echo 'selected'; } 
                                        echo " >" . $user['Username'] . "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
            <!--        End Members Field-->
                    
                    <!--        Start Categories Field-->
                    <div class="form-group form-group-lg row">
                        <label class="col-sm-2 control-label">Category</label>
                        <div class="col-sm-10 col-md-8">
                            <select name="category">
                                <?php
                                    $stmt2 = $con->prepare("SELECT * FROM categories");
                                    $stmt2->execute();
                                    $cats = $stmt2->fetchAll();
                                    foreach($cats as $cat)
                                    {
                                        echo "<option value='".$cat['ID']."'";
                                        if($item['Cat_ID'] == $cat['ID']) { echo 'selected'; }
                                        echo ">" . $cat['Name'] . "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
            <!--        End Categories Field-->

                    <!--        Start Submit Field-->
                    <div class="form-group row">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input type="submit" value="Save Item" class="btn btn-primary btn-sm" />
                        </div>
                    </div>
            <!--        End Submit Field-->
                </form>
                
                <?php
                
                // Select All Comments

                $stmt = $con->prepare("SELECT 
                                            comments.* , users.Username AS Member
                                        FROM 
                                            comments 
                                        INNER JOIN 
                                            users 
                                        ON 
                                            users.UserID = comments.user_id 
                                        WHERE item_id = ? ");

                // Execute The Statement

                $stmt->execute(array($itemid));

                // Assign To Variable

                $rows = $stmt->fetchAll();
         
                if(! empty($rows))
                {

                ?>

                    <h1 class="text-center">Manage [ <?php echo $item['Name'] ?> ] Comments</h1>                    
                        <div class="table-responsive">
                            <table class="main-table text-center table table-bordered">
                                <tr>
                                    <td>Comment</td>
                                    <td>User Name</td>
                                    <td>Added Date</td>
                                    <td>Control</td>
                                </tr>
                                <?php
                                    foreach($rows as $row)
                                    {
                                        echo '<tr>';
                                        echo '<td>'.$row['comment'].'</td>';
                                        echo '<td>'.$row['Member'].'</td>';
                                        echo '<td>'.$row['comment_date'].'</td>';
                                        echo '<td>
                                        <a href="comments.php?do=Edit&comid='.$row['c_id'].'" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
                                        <a href="comments.php?do=Delete&comid='.$row['c_id'].'" class="btn btn-danger confirm"><i class="fa fa-close"></i> Delete </a>';

                                        if($row['status'] == 0)
                                        {
                                            echo '<a href="comments.php?do=Approve&comid='.$row['c_id'].'" class="btn btn-info activate"><i class="fa fa-check"></i> Approve</a>';
                                        }
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                ?>

                            </table>
                        </div>
            <?php } ?>
                
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
    
    elseif($do == 'Update')
    {
        echo '<h1 class="text-center">'."Update Item".'</h1>';
        echo "<div class='container'>";
        
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            //Get Varibles From Form
            
            $id      = $_POST['itemid'];
            $name    = $_POST['name'];
            $desc    = $_POST['description'];
            $price   = $_POST['price'];            
            $country = $_POST['country'];
            $status  = $_POST['status'];
            $cat     = $_POST['category'];
            $member  = $_POST['member'];
            
            
            //Validate The Form
            
            $formErrors = array();
            
            if(empty($name))
            {
                $formErrors[] = 'Nmae Cant Be <strong>Empty</strong>';
            }
            if(empty($desc))
            {
                $formErrors[] = 'Description Cant Be <strong>Empty</strong>';
            }
            if(empty($price))
            {
                $formErrors[] = 'Price Cant Be <strong>Empty</strong>';
            }
            if(empty($country))
            {
                $formErrors[] = 'Country Cant Be <strong>Empty</strong>';
            }
            if($status == 0)
            {
                $formErrors[] = 'You Must Choose The <strong>Status</strong>';
            }
            if($member == 0)
            {
                $formErrors[] = 'You Must Choose The <strong>Member</strong>';
            }
            if($cat == 0)
            {
                $formErrors[] = 'You Must Choose The <strong>Category</strong>';
            }
            
            //Loop Into Error Array And Echo It
            
            foreach($formErrors as $error)
            {
                echo '<div class="alert alert-danger">'.$error.'</div>';
            }
            
            //Check There is No Error Proceed The Update Operation
            
            if(empty($formErrors))
            {
                //Update The DataBase with This Info 
            
            $stmt = $con->prepare("UPDATE 
                                        items 
                                    SET 
                                        Name = ? , 
                                        Description = ? , 
                                        Price = ? , 
                                        Country_Made = ? ,
                                        Status = ? ,
                                        Cat_ID = ? ,
                                        Member_ID = ? 
                                    WHERE 
                                        Item_ID = ?");
            $stmt->execute(array($name,$desc,$price,$country,$status,$cat,$member,$id));
            
            //Echo Success Message

            $theMsg = '<div class="alert alert-success">'.$stmt->rowCount().'Record Updates</div>';

            redirectHome($theMsg ,'back');
            }
            
        }
        else
        {
            $theMsg = '<div class="alert alert-danger">'."Sorry You Cant Browse This Page Directly".'</div>';
            
            redirectHome($theMsg);
        }
        
        echo "</div>";
    }
    
    elseif($do == 'Delete')
    {
        echo '<h1 class="text-center">'."Delete Item".'</h1>';
        echo "<div class='container'>";
        
        // Check If Request Item ID Is Numeric & Get The Integer Value Of It

        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid'])?intval($_GET['itemid']) : 0 ;
        
        // Select All Data Depend On This ID
                
        $check = checkItem('Item_ID' , 'items' , $itemid);
        
        // If There is Such ID Show The Form 

    if($check > 0)
    { 
        $stmt = $con->prepare("DELETE FROM items WHERE Item_ID = :zid");
        
        // هذه أحدى الطرق اننا اربط بين الباراميترز الاثنين وارجع انفذ الاستعلام
       
        $stmt->bindParam(':zid',$itemid);
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
        echo '<h1 class="text-center">'."Approve Item".'</h1>';
        echo "<div class='container'>";
        
        // Check If Request Item ID Is Numeric & Get The Integer Value Of It

        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid'])?intval($_GET['itemid']) : 0 ;
        
        // Select All Data Depend On This ID
                
        $check = checkItem('Item_ID' , 'items' , $itemid);
        
        // If There is Such ID Show The Form 

    if($check > 0)
    { 
        $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE Item_ID = ?");
        
        $stmt->execute(array($itemid));
        
        // Echo Success Message
        
            $theMsg = '<div class="alert alert-success">'.$stmt->rowCount().'Record Updated</div>';
        
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