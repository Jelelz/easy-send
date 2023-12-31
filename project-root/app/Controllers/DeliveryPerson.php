<?php

namespace App\Controllers;

use App\Models\ConfirmationPhotoModel;
use App\Models\DeliveryPersonModel;
use App\Models\OrderDeliveryPersonModel;
use App\Models\OrderModel;
use App\Models\UserModel;

class DeliveryPerson extends User
{
    public function register() // Register a delivery person
    {
        if(isset($_POST['register_submit']))
        {
            $usermodel = new UserModel();
            $deliverypersonmodel = new DeliveryPersonModel();
            $password = password_hash($_POST['register_password'], PASSWORD_DEFAULT);

            $file = $this->request->getFile('register_photo'); // Getting the uploaded file, renaming it, and moving it to public/profile_photos

            if($file->isValid() && !$file->hasMoved()) 
            {
                $file->move('./profile_photos', $_POST['register_firstname'].'_'. $_POST['register_lastname'].'.'.$file->getExtension());
            }

            else
            {
                echo '<script>alert("Invalid File")</script>';
                return view('delivery_person/register');
            }

            $user_data = 
            [
                'role_id' => 3,
                'user_firstname' => $_POST['register_firstname'],
                'user_lastname' => $_POST['register_lastname'],
                'user_email' => $_POST['register_email'],
                'user_password' => $password,
                'user_number' => $_POST['register_number'],
                'user_location' => $_POST['register_location']
            ];

            $user_id = $usermodel->insert($user_data, true);

            if($user_id) // If inserting user data is successful, then insert extra delivery person data
            {
                $dp_data = 
                [
                    'user_id' => $user_id,
                    'dp_profile_photo' => $file->getName(),
                    'dp_city' => $_POST['register_city']
                ];

                $is_registered = $deliverypersonmodel->insert($dp_data, true);

                if($is_registered) // Successful registration, after which we add the delivery person's data to the session
                {
                    $session = session();
                
                    $user_data = [
                        'user_id' => $is_registered,
                        'user_firstname'  => $_POST['register_firstname'],
                        'user_lastname'  => $_POST['register_lastname'],
                        'dp_id' => $is_registered
                    ];

                    $session->set($user_data);                   
                    echo '<script>alert("Registration Successful")</script>';
                    return $this->viewAvailableOrders();
                }

                else // Inserting the delivery person specific data failed
                {
                    echo '<script>alert("Registration Failed")</script>';
                    return view('delivery_person/register');
                }
            }

            else // Inserting the general user data failed
            {
                echo '<script>alert("Registration Failed")</script>';
                return view('delivery_person/register');
            }
        }

        else // Delivery person loading the page for the first time
        {
            return view('delivery_person/register');
        }       
    }

    public function editProfile() // Edit the delivery person's profile
    {
        $session = session();
        $userModel = new UserModel();
        $deliveryPersonModel = new DeliveryPersonModel();
        
        if(isset($_POST['edit_submit'])) // If they submitted their profile details
        {           
            if($this->request->getFile('edit_photo')) // If they uploaded a new profile photo, check it's valid and add it to the dp_data array
            {
                $file = $this->request->getFile('edit_photo');

                if($file->isValid() && !$file->hasMoved()) // Uploaded file is valid
                {
                    $file->move('./profile_photos', $_POST['edit_firstname'].'_'. $_POST['edit_lastname'].'.'.$file->getExtension());

                    $dp_data = 
                    [
                        'dp_profile_photo' => $file->getName(),
                        'dp_city' => $_POST['edit_city']
                    ];
                }

                else // Uploaded file is invalid
                {
                    echo '<script>alert("Invalid File")</script>';
                    return view('delivery_person/register');
                }
            }

            else // They did not upload a new photo, so the dp_data array doesn't need the dp_profile_photo key
            {
                $dp_data = 
                [
                    'dp_city' => $_POST['edit_city']
                ];
            }
            
            $user_data = 
            [
                'role_id' => 3,
                'user_firstname' => $_POST['edit_firstname'],
                'user_lastname' => $_POST['edit_lastname'],
                'user_email' => $_POST['edit_email'],
                'user_number' => $_POST['edit_number'],
                'user_location' => $_POST['edit_location']
            ];

            $is_user_updated = $userModel->update($session->get('user_id'), $user_data); 

            if($is_user_updated) // General user data has been updated successfully
            {
                $is_dp_updated = $deliveryPersonModel->update($session->get('dp_id'), $dp_data);

                if($is_dp_updated) // Delivery person data has been updated successfully
                {   
                    echo "<script>alert('Delivery Person Update Successful')</script>";
                    return $this->viewAvailableOrders();
                }

                else
                {
                    echo "<script>alert('Delivery Person Update Failed')</script>";
                    return view('delivery_person/edit_profile');
                }
            }

            else // Updating general user data failed
            {
                echo "<script>alert('User Update Failed')</script>";
                return view('delivery_person/edit_profile');
            }
        }

        else // Delivery person is visiting the page for the first time, so retrieve their data from the db so that they can edit it
        {
            $delivery_person_data = 
            [
                'user_data' => $userModel->find($session->get('user_id')), // General data
                'delivery_person_data' => ($deliveryPersonModel->where('user_id', $session->get('user_id'))->find())[0] // Delivery person data
            ];

            return view('delivery_person/edit_profile', $delivery_person_data);
        }
    }

    public function viewAvailableOrders() // Get all pending orders and display them to the delivery person
    {
        $db = \Config\Database::connect();
        $builder = $db->table('orders');
        $builder->select('order_id, user_id, pickup_area, pickup_street_name, destination_area, destination_street_name, created_at');
        $builder->where('status', 'pending');
        $builder->where('is_paid', 1);
        $query = $builder->get();

        foreach($query->getResultArray() as $row)
        {
            $result[] = $row;
        }

        if(isset($result))
        {
            $available_orders = 
            [
                'available_orders' => $result
            ];
            
            return view('delivery_person/available_orders', $available_orders);
        }

        else
        {
            $available_orders = 
            [
                'available_orders' => null
            ];
        }
        
        return view('delivery_person/available_orders', $available_orders);
    }

    public function acceptOrder() // The delivery person accepts an order, so we update the database and add the order to the session
    {
        $session = session();
        
        if(isset($_POST['acceptorder_submit'])) // If the delivery person accepted the order on the form on the available_orders page
        {
            $orderDeliveryPersonModel = new OrderDeliveryPersonModel();
            $orderModel = new OrderModel();

            $data = 
            [
                'order_id' => $_POST['acceptorder_order_id'],
                'dp_id' => $session->get('dp_id')
            ];

            $is_accepted = $orderDeliveryPersonModel->insert($data, true);

            if($is_accepted) // The order_deliveryperson table has been inserted to
            {
                $update_data = 
                [
                    'status' => 'accepted'
                ];
                
                $is_updated = $orderModel->update($_POST['acceptorder_order_id'], $update_data);

                if($is_updated) // The orders table has been updated
                {
                    $session->set('order_id', $_POST['acceptorder_order_id']);
                    echo '<script>alert("Order Accepted")</script>';
                    return $this->fulfillOrder();
                }

                else
                {
                    echo '<script>alert("Order Update Failed")</script>';
                    return $this->viewAvailableOrders();
                }             
            }

            else
            {
                echo '<script>alert("Order failed to accept")</script>';
                return $this->viewAvailableOrders();
            }
        }
    }

    public function fulfillOrder()
    {
        $session = session();
        $orderModel = new OrderModel();

        if(isset($_POST['confirmation_submit']))
        {
            $confirmationPhotoModel = new ConfirmationPhotoModel();

            $file = $this->request->getFile('confirmation_photo');

            if($file->isValid() && !$file->hasMoved()) 
            {
                $file->move('./confirmation_photos', 'order_'.$session->get('order_id').'.'.$file->getExtension());
            }

            else
            {
                echo '<script>alert("Invalid File")</script>';
                return view('delivery_person/fulfill_order');
            }

            $update_data = 
            [
                'status' => 'completed'
            ];

            $is_updated = $orderModel->update($session->get('order_id'), $update_data);

            if($is_updated)
            {
                $data = 
                [
                    'order_id' => $session->get('order_id'),
                    'confirmation_photo' => $file->getName()
                ];

                $is_inserted = $confirmationPhotoModel->insert($data, true);

                if($is_inserted)
                {
                                      
                    echo '<script>alert("Order Completed")</script>';
                    return $this->viewAvailableOrders();
                }

                else
                {
                    echo "Confirmation photo upload failed";
                }
            }

            else
            {
                echo "Order update failed";
            }
            
        }

        else
        {
            $order = 
            [
                'order' => $orderModel->find($session->get('order_id'))
            ];
            
            return view('delivery_person/fulfill_order', $order);
        }
    }

    public function viewOrderHistory()
    {
        $session = session();
        
        $db = \Config\Database::connect();
        $builder = $db->table('orders');
        $builder->select('orders.updated_at, pickup_area, pickup_estate, destination_area, destination_estate');
        $builder->join('order_deliveryperson', 'order_deliveryperson.order_id = orders.order_id', 'inner');
        $builder->where('status', 'completed');
        $builder->where('order_deliveryperson.dp_id', $session->get('dp_id'));
        $query = $builder->get();

        foreach($query->getResultArray() as $row)
        {
            $result[] = $row;
        }

        if(isset($result))
        {
            $order = 
            [
                'order' => $result
            ];
        }

        else
        {
            $order = 
            [
                'order' => 'no orders'
            ];
        }
            
        return view('user/order_history', $order);
    }
}