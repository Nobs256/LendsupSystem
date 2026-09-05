<?php   
include("../conn.php");
                                  
if(isset($_REQUEST['sc_id']))
{   
$sc_id = $_REQUEST['sc_id'];
    
  $result = mysqli_fetch_assoc(mysqli_query($conn,"select sc_id, ucase(sc_name) as sc_name, ucase(hm_name) as hm_name,
    hm_phone, sc_email, sc_moto, ucase(province) as province, ucase(dist) as dist, ucase(county) as county, ucase(sub_county) as sub_county,
    ucase(parish) as parish, ucase(cell) as cell, sc_logo from schools where sc_id ='$sc_id'"));
    $schs_id = $result["sc_id"];
    $sch_name = $result['sc_name'];
    $sc_hm_name = $result['hm_name'];
  $sc_hm_phone = $result['hm_phone'];   
  $sch_email = $result['sc_email'];
  $sch_moto = $result['sc_moto'];
  $pro = $result['province'];
  $dist = $result['dist'];
  $county = $result['county'];
  $sub = $result['sub_county'];
  $par = $result['parish'];
  $cell = $result['cell'];
  $sc_logo = $result['sc_logo'];
   
  //checking if user profile image is uploaded if not then use default image
if($sc_logo == ""){
    $sc_logo = "../assets/images/sch_logo/user_sample.png";
}else{
    $sc_logo = "../assets/images/sch_logo/".$sc_logo;
}
}
 include('header.php'); 
 ?>
<main class="column main">   
<div id="main_heading"> UPDATE SCHOOL FORM</div>     
<div id="main_body"><br><br>
<div class="field is-horizontal">
<div style="width:800px">

<form  method="post" action="admin_connector.php">
<input type="hidden" name="sc_id" value="<?php echo $schs_id; ?>">

<table style="width:600px;" border="0">
        <tr>
        <td width="160px"> 
       <label class="label"> School Name: </label>
        </td>
        <td>
        <input type="text"   name="sc_name" class="input is-success" style="width:320px; border: 1px solid #5E4EA0" 
        value="<?php echo $sch_name; ?>" required><br><br>
       </td>
      </tr>

      <tr>
      <td> 
      <label class="label">H/M Name: </label>
      </td>
      <td width="200px">
      <input type="text" size="30" name="hm_name" class="input is-success" style="width:320px; border: 1px solid #5E4EA0"
          value="<?php echo $sc_hm_name; ?>" required><br><br>       
       </td>
       </tr><!--end of tr -->

      <tr>
      <td>
      <label class="label">H/M Phone No: </label>
      </td>
      <td>
      <input type="tel" maxlength = "10" name="hm_phone" class="input is-success" style="width:320px; border: 1px solid #5E4EA0" 
      value="<?php echo $sc_hm_phone; ?>" required><br><br>          
       </td> 
       </tr> 

      <tr>     
      <td>      
      <label class="label">  School E-Mail: </label> 
      </td>
      <td width="200px">
      <input type="text" size="30" name="email" class="input is-success" style="width:320px; border: 1px solid #5E4EA0"
          value="<?php echo $sch_email; ?>"  required><br><br> 
         
       </td>        
       </tr><!--end of tr -->
       <tr>
      <td>       
      <label class="label">  Moto: </label>
      </td><td>
       <input type="text" size="30" name="moto" class="input is-success" style="width:320px; border: 1px solid #5E4EA0" 
          value="&nbsp;<?php echo $sch_moto; ?>"required><br><br>
         
       </td>        
      </tr><!--end of tr -->
       <tr>
        <td>
        
       <label class="label"> Province: </label>
       </td><td>
       <select  name="pro" class="select is-success" style="width:320px; border: 1px solid #5E4EA0" required>
            <option><?php echo $pro; ?></option>
            <option>Western</option>
            <option>Central</option>
            <option>Eastern</option>
            <option>Northern</option>
            <option>Southern</option>
           </select> <br><br>
       </td>        
       </tr><!--end of tr -->

         <tr>
          <td>      
      <label class="label">  District: </label>
      </td>
      <td>
      <input type="text" size="30" name="dist" class="input is-success" style="width:320px; border: 1px solid #5E4EA0"
          value="&nbsp;<?php echo $dist; ?>"required><br><br>
       </td>
        </tr><!--end of tr -->
       
         <tr>        
        <td>
      <label class="label">    County: </label>
      </td>
      <td>
      <input type="text" size="30" name="county" class="input is-success" style="width:320px; border: 1px solid #5E4EA0"
          value="&nbsp;<?php echo $county; ?>"  required><br><br>
          
       </td>        
       </tr><!--end of tr -->

       <tr>
      <td>
      <label class="label"> Sub-County:</label> 
      </td>
      <td>
      <input type="text" size="30" name="sub"  class="input is-success" style="width:320px; border: 1px solid #5E4EA0"
          value="&nbsp;<?php echo $sub; ?>" required><br><br>         
       </td> 
       </tr><!--end of tr -->

       <tr>       
       <td>
    <label class="label">   Parish: </label>
      </td>
      <td>
      <input type="text" size="30" name="par" class="input is-success" style="width:320px; border: 1px solid #5E4EA0"
      value="&nbsp;<?php echo $par; ?>" required><br><br>
      </td>
      </tr><!--end of tr -->

       <tr>
       <tr>
        <td>
      <label class="label">   Cell: </label>
      </td>
      <td>
     <input type="text" size="30" name="cell" class="input is-success" style="width:320px; border: 1px solid #5E4EA0"
     value="&nbsp;<?php echo $cell; ?>" required><br><br>
       </td>        
       </tr><!--end of tr -->
       
        <tr><td></td><td>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
         <button type="submit" name="update_sch" class="button is-primary" style="border: 1px solid #5E4EA0; border-radius:4px; color:white; background-color:#5E4EA0">
         &nbsp;&nbsp;&nbsp;Update School&nbsp;&nbsp;&nbsp;</button>
      </form>   
     </td></tr>
   </table>
<br><br>
</div>              
<div style="border: 1px solid #5E4EA0; width: 200px; height: 300px; padding-left:30px">
<img src="<?php echo $sc_logo;?>" alt="<?php echo $sch_name;?>" style="width:120px;height:150px; border-radius:5px; margin-top:10px;" > 
<form action="admin_connector.php" method="post" enctype="multipart/form-data">
<style>#upload{visibility: hidden;}</style>
<input type="hidden"  class="form-control" value="<?php  echo $schs_id;?>" name="sc_id">
<input type="file" name="image" id="upload">
<input type="button" value="Choose the Logo" class="button si-default" onclick="document.getElementById('upload').click()"> <br><br>
<input type="submit" name="school_logo" value="&nbsp;&nbsp;Upload Image&nbsp;&nbsp;" class="button is-primary" style="border: 1px solid #5E4EA0; border-radius:4px; color:white; background-color:#5E4EA0">      
</form>

 </div>
</div>
</div>
</div> 
<?php include('../footer.php'); ?>
</div>
</main>
</body> 
</html> 