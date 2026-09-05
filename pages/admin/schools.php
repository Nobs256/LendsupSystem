
<?php include('header.php'); ?>
<div class="dashboard-wrapper" style="margin-top:0px;">
            <div class="container-fluid dashboard-content">
                <div class="row">
                    <div class="col-xl-10">                    
                         
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="section-block" id="basicform">
                                                                      
                                </div>
                                <table border="0" width="900px"><tr>
                                <td></td>
                                <td width="300px">  

                                <div class="card" style="float: left; width: 1000px; height:900px; margin-right:0px;">
                                    <h5 class="card-header" align="center" style="background-color:#424949; color:white">SELECT A SCHOOL
                                   </h5>
                                    <div class="card-body" >
                                    
<?php
            
                        $search_query= mysqli_query($conn,"SELECT sc_id, ucase(sc_name) as sc_name, ucase(hm_name) as hm_name, hm_phone 
                          FROM schools order by sc_name");
                        ?>               
                  <div style="margin-left:25px; margin-right:25px; width:1000px; float:center; height:900px;"> 
                                      
       <table class="table table-responsive table-hover" style="width:1000px;">
        <thead>
        <tr><th>No</th><th>Names</th><th>H/M Name</th><th>H/M Phone</th><th>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        &nbsp;&nbsp;&nbsp;Manage</th>
        </tr></thead><tbody>

         <?php
         $j=0;
                   while($returned_result = mysqli_fetch_assoc($search_query)){
                   $sc_id=$returned_result["sc_id"];
                   $sc_name = $returned_result["sc_name"];
                   $hm_name = $returned_result["hm_name"];
                   $hm_phone = $returned_result["hm_phone"];
        $j++;

             echo "<tr>
                   <td><font size=2> $j </font></td>
                   <td><font size=2> $sc_name </font></td>
                   <td><font size=2> $hm_name</font></td>
                   <td><font size=2> $hm_phone</font></td>";                   
         
                   ?>
                   <td>
             <!-- ====================Action button -->
 <div>
 <ul>
                             <li class="dropdown-item">
                            <a  href="#"  data-toggle="dropdown"><i class="fa fa-fw fa-users"></i>&nbsp;More</a>               
                               
                            <div class="dropdown-menu dropdown-menu-right"  
                             aria-labelledby="navbarDropdownMenuLink1">

                                <a class="dropdown-item" href="add_contract.php?sc_id=<?php echo $sc_id?>">
                                  <i class="fas fa-user mr-2"></i>Add Contract</a>
                                <a class="dropdown-item" href="items_given_admin_reports.php?sc_id=<?php echo $sc_id?>">
                                <i class="fas fa-user mr-2"></i>Items Report(Invetory)</a>
                                 <a class="dropdown-item" href="items_given_admin_facture.php?sc_id=<?php echo $sc_id?>">
                                <i class="fas fa-user mr-2"></i>Items Facture(Edit_price)</a>
                                <a class="dropdown-item" href="services_given_admin_reports.php?sc_id=<?php echo $sc_id?>">
                                <i class="fas fa-user mr-2"></i>Services Report</a>
                                <a class="dropdown-item" href="req_made.php?sc_id=<?php echo $sc_id?>">
                                <i class="fas fa-user mr-2"></i>Requisitions</a>
                                 
                            </div>
                        </li>
</ul> </div>
                 </td>

              <?php
                  echo "</tr>";

               }
                   echo "</tbody></table>";

   mysqli_close($conn);
   
           
  ?>   
</div>
</div>
</div>
 </div>
  </div>
</div>
</td><td></td>
</tr>
<tr><td width="300px"><font color="white">-------------------------------</font></td><td> </td><td></td></tr></table>
<?php include('footer.php'); ?>
</body> 
</html>
