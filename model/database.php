<?php

require_once ("config-student.php");

class Database
{
    //PDO object
    private $_dbh;

    function __construct()
    {
        try {
            //Create a new PDO connection
            $this->_dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
            //echo "Connected!";
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    function getStudents()
    {
        //1. Define the query
        $sql = "SELECT * FROM student
                ORDER BY last, first";

        //2. Prepare the statement
        $statement = $this->_dbh->prepare($sql);

        //3. Bind the parameters

        //4. Execute the statement
        $statement->execute();

        //5. Get the result
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    function getAdvisors()
    {
        //1. Define the query
        $sql = "SELECT * FROM advisor
                ORDER BY advisor_last, advisor_first";

        //2. Prepare the statement
        $statement = $this->_dbh->prepare($sql);

        //3. Bind the parameters

        //4. Execute the statement
        $statement->execute();

        //5. Get the result
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    function getDetails($sid)
    {
        //1. Define the query
        $sql = "SELECT * 
                FROM student, advisor
                WHERE student.advisor = advisor.advisor_id
                AND sid = :sid";

        //2. Prepare the statement
        $statement = $this->_dbh->prepare($sql);

        //3. Bind the parameters
        $statement->bindParam(':sid', $sid);

        //4. Execute the statement
        $statement->execute();

        //5. Get the result
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    function writeStudent($student)
    {
        //var_dump($student);

        //1. Define the query
        $sql = "INSERT INTO student (sid, first, last, birthdate,
                gpa, advisor)
                VALUES (:sid, :first, :last, :birthdate,
                        :gpa, :advisor)";

        //2. Prepare the statement
        $statement = $this->_dbh->prepare($sql);

        //3. Bind the parameters
        $statement->bindParam(':sid', $student->getSid());
        $statement->bindParam(':first', $student->getFirst());
        $statement->bindParam(':last', $student->getLast());
        $statement->bindParam(':gpa', $student->getGpa());
        $statement->bindParam(':birthdate', $student->getBirthdate());
        $statement->bindParam(':advisor', $student->getAdvisor());

        //4. Execute the statement
        $statement->execute();

        //Get the key of the last inserted row
        $id = $this->_dbh->lastInsertId();
    }
}