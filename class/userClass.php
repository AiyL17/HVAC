<?php
class userClass
{
     /* User Login */



     public function userLogin($username, $password)
     {
          $db = pdo_init();

          // Get user data including password hash for verification
          $stmt = $db->prepare("SELECT user_id, user_pass FROM user WHERE user_name=:userName");
          $stmt->bindParam("userName", $username);
          $stmt->execute();
          $count = $stmt->rowCount();
          $data = $stmt->fetch(PDO::FETCH_OBJ);
          
          if ($count && $data) {
               $storedPassword = $data->user_pass;
               $isValidPassword = false;
               
               // Check if password is hashed (starts with $2y$ for bcrypt)
               if (password_get_info($storedPassword)['algo'] !== null) {
                    // Password is hashed, use password_verify
                    $isValidPassword = password_verify($password, $storedPassword);
               } else {
                    // Password is plain text (legacy), use direct comparison
                    $isValidPassword = ($password === $storedPassword);
               }
               
               if ($isValidPassword) {
                    $_SESSION['uid'] = $data->user_id;
                    
                    // Set the current user to active and update last login time and activity
                    $updateStmt = $db->prepare("UPDATE user SET is_active = 1, last_login = NOW(), last_activity = NOW() WHERE user_id = :user_id");
                    $updateStmt->bindParam(":user_id", $data->user_id, PDO::PARAM_INT);
                    $updateStmt->execute();
                    
                    $db = null;
                    return true;
               }
          }
          
          $db = null;
          return false;
     }
     public function userRegister($first_name, $middle_name, $last_name, $email, $contact, $house_building_street, $barangay, $municipality_city, $province, $zip_code, $password)
     {
          $db = pdo_init();

           try {
                // Check for duplicate email to prevent multiple registrations
                $emailCheckStmt = $db->prepare("SELECT COUNT(*) as count FROM user WHERE user_email = :email");
                $emailCheckStmt->bindParam(':email', $email, PDO::PARAM_STR);
                $emailCheckStmt->execute();
                $emailResult = $emailCheckStmt->fetch(PDO::FETCH_ASSOC);
                
                if ($emailResult['count'] > 0) {
                    error_log("Registration blocked: Email already exists");
                    return false;
                }
                
                // Check for duplicate password by comparing with all existing passwords (both hashed and plain text)
                $passwordCheckStmt = $db->prepare("SELECT user_pass FROM user");
                $passwordCheckStmt->execute();
                $existingPasswords = $passwordCheckStmt->fetchAll(PDO::FETCH_COLUMN);
                
                foreach ($existingPasswords as $existingPassword) {
                    // Check if password is hashed (starts with $2y$ for bcrypt)
                    if (password_get_info($existingPassword)['algo'] !== null) {
                        // Password is hashed, use password_verify
                        if (password_verify($password, $existingPassword)) {
                            error_log("Registration blocked: Password already exists");
                            return false;
                        }
                    } else {
                        // Password is plain text (legacy), use direct comparison
                        if ($password === $existingPassword) {
                            error_log("Registration blocked: Password already exists");
                            return false;
                        }
                    }
                }

                // Generate unique user_id manually (fix for auto-increment issue)
                $maxIdQuery = $db->prepare("SELECT MAX(user_id) as max_id FROM user");
                $maxIdQuery->execute();
                $maxIdResult = $maxIdQuery->fetch(PDO::FETCH_ASSOC);
                $nextUserId = ($maxIdResult['max_id'] ?? 0) + 1;

                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                $user_type = "4";
                
                // Insert with all new address fields
                $stmt = $db->prepare("INSERT INTO user (user_id, user_name, user_midname, user_lastname, user_pass, user_email, user_contact, house_building_street, barangay, municipality_city, province, zip_code, user_type_id) VALUES (:user_id, :first_name, :middle_name, :last_name, :password, :email, :contact, :house_building_street, :barangay, :municipality_city, :province, :zip_code, :user_type_id)");
                $stmt->bindParam(':user_id', $nextUserId);
                $stmt->bindParam(':first_name', $first_name);
                $stmt->bindParam(':middle_name', $middle_name);
                $stmt->bindParam(':last_name', $last_name);
                $stmt->bindParam(':password', $hashed_password);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':contact', $contact);
                $stmt->bindParam(':house_building_street', $house_building_street);
                $stmt->bindParam(':barangay', $barangay);
                $stmt->bindParam(':municipality_city', $municipality_city);
                $stmt->bindParam(':province', $province);
                $stmt->bindParam(':zip_code', $zip_code);
                $stmt->bindParam(':user_type_id', $user_type);

                $stmt->execute();
                
                return $nextUserId;
          } catch (PDOException $e) {
               error_log("Registration failed: " . $e->getMessage());
               return false;
          }
     }


     /* User Details */
     public function userDetails($uid)
     {
          try {
               $db = pdo_init();
               $stmt = $db->prepare("SELECT
    user.*,
    user_type.user_type_name as user_type
FROM
    user
JOIN
    user_type
ON
    user.user_type_id = user_type.user_type_id
 WHERE user_id=:uid");
               $stmt->bindParam("uid", $uid, PDO::PARAM_INT);
               $stmt->execute();
               $data = $stmt->fetch(PDO::FETCH_OBJ);
               
               // Return false if no user found
               if (!$data) {
                    return false;
               }
               
               return $data;
          } catch (PDOException $e) {
               echo '{"error":{"text":' . $e->getMessage() . '}}';
               return false;
          }
     }
}
