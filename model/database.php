<?php

/*
 * CREATE TABLE Members(
    member_id INT AUTO_INCREMENT PRIMARY KEY,
    fname varchar(50),
    lname varchar(50),
    age INT,
    gender varchar(1),
    phone varchar(12),
    email varchar(50),
    state varchar(2),
    seeking varchar(1),
    bio varchar(255),
    premium tinyint,
    image varchar(255),
    interests varchar(255)
    );
 */
//Connect to the database
require '/home2/jsuhover/config.php';

class Database
{
    function connect()
    {
        try {
            //Instantiate a database object
            $dbh = new PDO(DB_DSN, DB_USERNAME,
                DB_PASSWORD);
            //echo "Connected to database!!!";
            return $dbh;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return;
        }
    }

    function getMembers()
    {
        global $dbh;

        $dbh = $this->connect();
        //1. define the query
        $sql = "SELECT * FROM Members ORDER BY lname";

        //2. prepare the statement
        $statement = $dbh->prepare($sql);

        //3. bind parameters

        //4. execute the statement
        $statement->execute();

        //5. return the result
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        //print_r($result);
        return $result;
    }

    function addMember($fname, $lname, $age, $gender, $phone, $email, $state, $seeking, $bio, $premium, $image, $interests)
    {
        global $dbh;
        $dbh = $this->connect();
        //echo $image;
        //1. define the query

        $sql = "INSERT INTO Members(`fname`, `lname`, `age`, `gender`, `phone`, `email`, `state`, `seeking`, `bio`, `premium`, `interests`)
            VALUES (:fname, :lname, :age, :gender, :phone, :email, :state, :seeking, :bio, :premium, :interests)";

/*
        $sql = "INSERT INTO Members(`fname`, `lname`, `age`, `gender`, `phone`, `email`, `state`, `seeking`, `bio`, `premium`, `image`, `interests`)
            VALUES ('jane','doe',30,
        'Female','1234567890','jane@email.com','Idaho',
        'Male','Why is this not working',0,'','')";
*/

        //2. prepare the statement
        $statement = $dbh->prepare($sql);

        //3. bind parameters
        $statement->bindParam(':fname', $fname, PDO::PARAM_STR);
        $statement->bindParam(':lname', $lname, PDO::PARAM_STR);
        $statement->bindParam(':age', $age, PDO::PARAM_INT);
        $statement->bindParam(':gender', $gender, PDO::PARAM_STR);
        $statement->bindParam(':phone', $phone, PDO::PARAM_STR);
        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        $statement->bindParam(':state', $state, PDO::PARAM_STR);
        $statement->bindParam(':seeking', $seeking, PDO::PARAM_STR);
        $statement->bindParam(':bio', $bio, PDO::PARAM_STR);
        $statement->bindParam(':premium', $premium, PDO::PARAM_BOOL);
        $statement->bindParam(':interests', $interests, PDO::PARAM_STR);

       // echo $statement;

        //4. execute the statement
        $success = $statement->execute();

        //5. return the result
        return $success;

    }

    function getMember($id)
    {
        global $dbh;
        $dbh = $this->connect();

        //1. define the query
        $sql = "SELECT * FROM Members WHERE member_id = :id";

        //2. prepare the statement
        $statement = $dbh->prepare($sql);

        //3. bind parameters
        $statement->bindParam(':id', $id, PDO::PARAM_INT);

        //4. execute the statement
        $statement->execute();

        //5. return the result
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        //check if this is a premium member
        if($result['premium'] == 1)
        {

            $interests = explode(';',$result['interests']);
            $outdoor = explode(',',$interests[0]);
            $indoor = explode(',',$interests[1]);


            return new PremiumMember($result['fname'], $result['lname'], $result['age'],
                $result['gender'], $result['phone'], $result['email'], $result['state'],
                $result['seeking'], $result['bio'],$indoor,$outdoor);
        }

        // if not premium we dont need some fields
        return new Member($result['fname'], $result['lname'], $result['age'],
            $result['gender'], $result['phone'], $result['email'], $result['state'],
            $result['seeking'], $result['bio']);
    }
}