<?php   
include("conn.php");
                                  
if(isset($_REQUEST['user_id']))
{   
$user_id = $_REQUEST['user_id'];
    
  $result = mysqli_fetch_assoc(mysqli_query($conn,"select user_id, ucase(firstname) as firstname, ucase(lastname) as lastname,
    username, category, password, users_image from new_users where user_id ='$user_id'"));
    $user_id = $result["user_id"];
    $firstname = $result['firstname'];
    $lastname = $result['lastname'];
  $username = $result['username'];   
  $cat = $result['category'];
  $password = $result['password'];
  $user_image = $result['users_image'];
   
  //checking if user profile image is uploaded if not then use default image
if($user_image == ""){
    $user_image = "assets/images/users_images/user_sample.png";
}else{
    $user_image = "assets/images/users_images/".$user_image;
}
}
 include('header_user.php'); 
 ?>
<div class="dashboard-wrapper">
            <div class="container-fluid dashboard-content">
                <div class="row">
                    <div class="col-xl-10">                    
                         
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="section-block" id="basicform">
                                    <h3 class="section-title" align="center">USER UPDATE FORM</h3>
                                                                                                     
                                </div>
                                <div class="card">
                                    <h5 class="card-header" align="center" style="background-color:#424949;"><font color="white" size="4"> Change the field you want to update</font></h5>
                                    <div class="card-body" style="height:auto; border: 1px solid #424949; border-radius:0px;">
                                    <div class="card-body">
                    
        <form class="form-horizontal" id="myform" role="form" method="post" action="update_user_last.php">

        <table style="width:750px;" border="0">
        <tr><td width="150px">
        <input type="hidden"  class="form-control" value="<?php echo $user_id;?>" name="user_id">
        <div class="form-group "> 
           First Name: 
      </td><td width="700px">
          <div class="col-sm-6"> 
            <input type="text" class="form-control" name="firstname" value="<?php echo $firstname;?>" required >
          </div>
        </div>
       </td>
       </tr><tr><td width="150px">
        <div class="form-group"> 
          Last Name: 
          </td><td width="700px">
          <div class="col-sm-6"> 
            <input type="text" class="form-control"  name="lastname" value="<?php echo $lastname;?>" required>
          </div>
        </div>
        </td>
       </tr><tr><td width="150px">
        <div class="form-group "> 
         Username:
          </td><td width="700px">
          <div class="col-sm-6"> 
             <input type="text" class="form-control" name="username" value="<?php echo $username;?>" required>
          </div>
        </div>
        </td>
       </tr>
       <tr><td width="150px">
        <div class="form-group"> 
          Password: 
          </td><td width="700px">
          <div class="col-sm-6"> 
            <input type="text" class="form-control" name="password" value="<?php echo $password;?>"  pattern="^[0-9]+" title="User Numbers only" required>
          </div>
        </div>
        </td>
       </tr>
       <tr><td width="150px">
        <div class="form-group"> 
          Confirm Password: 
          </td><td width="700px">
          <div class="col-sm-6"> 
            <input type="text" class="form-control" name="password" value="<?php echo $password;?>"  pattern="^[0-9]+" title="User Numbers only" required>
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
         <button type="submit" name="update_user" class="btn btn-primary">
            
         <span class="glyphicon glyphicon-ok-circle"></span>&nbsp;&nbsp;&nbsp;
         Update a User&nbsp;&nbsp;&nbsp;</button>
      </form>   
 </td></tr></table>
     </div>
  </div>
</div>
</div>
</div>
</div>

 
  
                                    
                            
                    <!-- ============================================================== -->
                    <!-- sidenavbar -->
                    <!-- ============================================================== -->
              
<div class="col-xl-2 col-lg-2 col-md-6 col-sm-12 col-12" style="height:800px; margin-top:0px;background-color:#CCD1D1;
border-radius:0px; border: 10px solid #E5E7E9;">
                        <div class="sidebar-nav-fixed">
                            <ul class="list-unstyled">
                                <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
                                <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
                                
                                <li>

                                  <img src="<?php echo $user_image;?>" alt="<?php echo $firstname." ".$lastname;?>" style="width:100px;height:120px; border-radius:5px; margin-top:10px;" > 

                                </li>

                                <li> 

                                <form action="update_user_last.php" method="post" enctype="multipart/form-data">
                                <style>#upload{visibility: hidden;}</style>
                                                                                                  
                              <input type="hidden"  class="form-control" value="<?php  echo $user_id;?>" name="user_id">
                             <input type="file" name="image" id="upload">
                             <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
             
                             <input type="button" value="Select the Image" class="upbutton btn btn-default" onclick="document.getElementById('upload').click()">
                             <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
                           <input type="submit" name="upload" value="Upload Image" class="btn btn-primary">      
                           </form>

                                </li>
                              
                            </ul>
                        </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- end sidenavbar -->
                    <!-- ============================================================== -->
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
           <?php include('footer.php'); ?>
</html>
