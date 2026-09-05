
<?php 
$s="";
if(isset($_POST['reload']))
{
$sec=$_POST['re'];
 header("refresh: $sec");
}


                    if(isset($_GET['delete_user'])){
                           $s = "<div style='background-color:#5E4EA0; border-radius:5px; color:white; 
                           height:40px; margin-left:25px; padding:7px; width: 1000px'>
                        <font color=white>A User is successfully deleted!!</font>
                        <a href='create_users.php?reload=1' style='color:white; margin-left:700px;''>X</a>
                        </div>";
                    }

                      if(isset($_GET['succ'])){
                           $s = "<div style='background-color:#5E4EA0; border-radius:5px; color:white; 
                           height:40px; margin-left:25px; padding:7px; width: 1000px'>
                        <font color=white>A User is successfully Created!!</font>
                        <a href='create_users.php?reload=1' style='color:white; margin-left:700px;''>X</a>
                        </div>";
                    }

                      if(isset($_GET['already_user'])){
                           $s = "<div style='background-color:red; border-radius:5px; color:white; 
                           height:40px; margin-left:25px; padding:7px; width: 1000px'>
                        <font color=white>A user is already Entered!!</font>
                        <a href='create_users.php?reload=1' style='color:white; margin-left:700px;''>X</a>
                        </div>";
                    }


    
include('header.php'); 
 ?>
<div class="dashboard-wrapper" style="margin-top:0px;">
            <div class="container-fluid dashboard-content">
                <div class="row">
                    <div class="col-xl-10">                    
                         
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="section-block" id="basicform">
                                      <?php
                                    
                                    echo $s;
                                   ?>
                                </div>

                                <table border="0" width="900px"><tr>
                                <td></td>
                                <td width="300px">  

                                <div class="card" style="float: left; width: 1020px; margin-left:10px;">
                   
                                    <h5 class="card-header" align="center" style="background-color:#424949;">
                                      <font color="white" size="3">CREATE USERS</font></h5>        
         
                                    <div class="card-body" style="height:auto; 
                                    border: 1px solid #424949; border-radius:0px;">
                                    <div class="card-body">
         
        <form class="form-horizontal" id="myform" role="form" method="post" action="admin_connector.php">            
        <table style="width:750px;" border="0">
        <tr><td width="150px">
        <div class="form-group "> 
        Firstname: 
      </td><td width="700px">
          <div class="col-sm-6"> 
          <input type="text" class="form-control"  name="fname"  required>
          </div>
        </div>
          </div>
        </div>
       </td>        
       </tr>

        <tr><td width="150px">
        <div class="form-group "> 
        Lastname: 
      </td><td width="700px">
          <div class="col-sm-6"> 
          <input type="text" class="form-control"  name="lname"  required>
          </div>
        </div>
          </div>
        </div>
       </td>        
       </tr>
       <tr><td width="150px">
        <div class="form-group "> 
       Password: 
      </td><td width="700px">
          <div class="col-sm-6"> 
          <input type="test" class="form-control"  name="password"  required>
          </div>
        </div>
          </div>
        </div>
       </td>        
       </tr>
              
       <tr><td width="150px">
        <div>         
        </td><td width="700px"><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>  
        </td></tr>
        <tr><td></td><td>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         <button type="submit" name="create_user" class="btn btn-primary" style="border: 1px solid #5E4EA0; border-radius:4px; color:white; background-color:#5E4EA0">
         &nbsp;&nbsp;&nbsp;Create a User&nbsp;&nbsp;&nbsp;</button>
      </form>   
     </td></tr>

   </table>
  <hr color="#5E4EA0" size="3"></hr>
<div> 
       
      <table class="table table-hover">
        <thead>
          <tr  style="height:5px;"> 
            <th>N<sup>o</sup></th>
            <th>First Name</th>   
            <th>Last Name</th>                                            
            <th>Password</th>            
                    
          </tr>
        </thead>
        <tbody>
          <?php
                    $i = 0;
          
            $select_grades = mysqli_query($conn,"SELECT * FROM new_users");
 
                    while($selected_grade = mysqli_fetch_array($select_grades)){
                        $user_id = $selected_grade["user_id"];
                        $f = $selected_grade["firstname"];
                        $l= $selected_grade["lastname"];
                        $p= $selected_grade["password"];                                                                            
                        $i++;
                                      ?>

                                   
            <tr> 
            <td><?php echo $i;?></td>
            <td><?php echo $user_id;?></td>
            <td><?php echo $f;?></td>  
            <td><?php echo $l;?></td>   
          <td><a style="border: 1px solid #5E4EA0; border-radius:4px; color:#5E4EA0" class="btn btn-default" 
            href="admin_connector.php?delete_user=<?php echo $user_id;?>">&nbsp;DELETE</a></td>
          </tr>
          <?php  } ?>                

                     
        </tbody>
      </table>
      
</td><td></td></tr></table></div></div>
</div>
 </div>
  </div>
</div>
 
<?php include('footer.php'); ?>
</body> 
</html>
