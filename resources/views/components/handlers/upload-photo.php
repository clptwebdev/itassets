<?php 
use App\Models\Photo;

$uploadDir = asset('images'); 
$response = array( 
    'status' => 0, 
    'message' => 'Form submission failed, please try again.' 
); 
 
// If form is submitted 
if(isset($_POST['file']){ 
    // Get the submitted form data 
    $name = $_POST['name']; 
           
    // Upload file 
    $uploadedFile = ''; 
    if(!empty($_FILES["file"]["name"])){ 
            
        // File path config 
        $fileName = basename($_FILES["file"]["name"]); 
        $targetFilePath = $uploadDir . $fileName; 
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
            
        // Allow certain file formats 
        $allowTypes = array('jpg', 'png', 'jpeg'); 
        if(in_array($fileType, $allowTypes)){ 
            // Upload file to the server 
            if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){ 
                $uploadedFile = $fileName; 
                $uploadStatus = 1; 
            }else{ 
                $uploadStatus = 0; 
                $response['message'] = 'Sorry, there was an error uploading your file.'; 
            } 
        }else{ 
            $uploadStatus = 0; 
            $response['message'] = 'Sorry, only JPG, JPEG, & PNG files are allowed to upload.'; 
        } 
    } 
             
            if($uploadStatus == 1){ 
                // Include the database config file 
                $photo = Photo::create(['name'=> $name, 'path'=>$fileName]);
                $response['status'] = 1; 
                $response['message'] = 'Sorry, only JPG, JPEG, & PNG files are allowed to upload.'; 
            } 
        } 
    }else{ 
         $response['message'] = ''; 
    } 
} 
 
// Return response 
echo json_encode($response);