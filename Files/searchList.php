<!doctype html>
<head>
	<link rel="stylesheet" type="text/css" href="TableCSSCode.css">
</head>
<html>
<body>
<br>
<img src="images/chicago1.jpg" alt="Chicago" style="height:120px" width="587px">
<?php


$Clat;
 $Clong;

/* function distance takes the curret position and return a the distance between address and the restuarants */
function get_distance($address, $Clat, $Clong){
    //$address = "1100 W Bryn Mawr Chicago IL 60660";
    $address = str_replace(" ", "+", $address);
	
	$region = 'usa';

    $json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false");
    $json = json_decode($json);

    $Rlat = $json->results[0]->geometry->location->lat;
    $Rlong = $json->results[0]->geometry->location->lng;
    
     
      $theta = $Clong - $Rlong;
      $dist = sin(deg2rad($Clat)) * sin(deg2rad($Rlat)) +  cos(deg2rad($Clat)) * cos(deg2rad($Rlat)) * cos(deg2rad($theta));
      $dist = acos($dist);
      $dist = rad2deg($dist);
      $miles = $dist * 60 * 1.1515;
      return $miles;
          }
    
$con=mysqli_connect("localhost","root", "","ChicagoHub");
if (mysqli_connect_errno()){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
} 
			function appendValue( $query, $value, $valueString ) {
				$queryValue = "";
				if ( strlen($value) >0 )
				{
					if (strpos($query, 'where') === FALSE)
						$clause = " where ";
					else 
						$clause = " and ";	
					echo $queryValue;		
					$queryValue = $clause."".$valueString." ='".$value."'";
				}					
				return $queryValue;
			}
			
			/* geting the neighborhood name and fetching list of restuarnts from the database */
			if( isset( $_GET['Neighbourhood'] ) )
			{		
			     //$Clat = $_GET['Clat'];	
				 //$Clong = isset($_GET['Clong']);
				 //echo $Clat."Hello";	
				Echo "<div class='blinking'><b>Location : </b><span class='blink'>".$_GET['Neighbourhood']."</span></div><br>";
				//<th>Sno</th><th>Neighborhood</th>				
				/*<tr><th>Restaurant_Name</th>
	<th>Address</th><th>Typ_of_Rest</th><th>Cuisine</th>
	<th>Price</th><th>Rating</th><th>Phone</th><th>Link</th></tr>				*/
				$query = "SELECT * FROM details";
				//$query = $query.appendValue( $query, $_GET['Price'], "Price" );			
				//$query = $query.appendValue( $query, $_GET['Cuisine'], "Cuisine" );
				$query = $query.appendValue( $query, $_GET['Neighbourhood'], "Neighborhood" );		
				$result = mysqli_query($con, $query);	
				//check result available
				if(mysqli_num_rows($result)==0) 
					echo "No data found"; 
				else 
				{
					echo "<table class='CSSTableGenerator' ><tr></tr>";
					while($row = mysqli_fetch_array($result)) {
						$Address = $row['Address'];
						$length = get_distance($Address,41.897557299999995,-87.6270428);
						$length = round($length, 2);
						echo "<tr style='border-bottom-color: black; border-bottom-width: 1px;'><td>";
						//echo "<td>" . $row['Sno'] . "</td>";
						//echo "<td>" . $row['Neighborhood'] . "</td>";
						echo "<B>Restaurant Name : </B>" . $row['Restaurant_Name'] . "<br>";
						echo "<b>Address : </b>" . $row['Address'] . "<br>";
						echo "<b>Type of Restaurant : </b>" . $row['Typ_of_Rest'] . "<br>";
						echo "<b>Cusine : </b>" . $row['Cuisine'] . "<br>";
						echo "<b>Price : </b>" . $row['Price'] . "<br>";
						echo "<b>Rating : </b>" . $row['Rating'] . "<br>";
                                                echo "<div ><img src='images/Call.png' /> " . $row['Phone'] . "</div>";
						//echo "<b>Phone : </b>" . $row['Phone'] . "<br>";
					        //echo "<b>Link : </b>" . $row['Link'] . "<br>";
                                                echo "<b>Link :<a href='".$row['Link']."' target='_blank'>".
                                  $row['Link']."</a>"."<br>";
								  echo "<b>Distance : </b>" . $length . " miles"."<br>";
								  
						echo "</td></tr>";  
					}	
				}						
			}	
			?>
			</table>
            <br>             
            </form>          
      </div>
      <!-- end div#sidebar -->	  
<?php mysqli_close( $con ); ?>      
</body>
</html>
