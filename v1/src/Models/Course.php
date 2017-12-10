<?php
/**
 * Created by PhpStorm.
 * User: Christopher
 * Date: 12/9/2017
 * Time: 6:48 PM
 */

namespace Course\Controllers;
use Course\Utilities\DatabaseConnection;

class Course implements \JsonSerializable
{
    private $sectionID;
    private $courseID;
    private $courseName;
    private $courseDescription;
    private $courseTeacher;

    public function __construct()
    {

    }

    function jsonSerialize()
    {
        $rtn = array(
            'sectionID' => $this->sectionID,
            'courseID' => $this->courseID,
            'courseName' => $this->courseName,
            'courseDescription' => $this->courseDescription,
            'courseTeacher' => $this->courseTeacher,
        );
        return $rtn;
    }

    /**
     * @return mixed
     */
    public function getSectionID()
    {
        return $this->sectionID;
    }

    /**
     * @param mixed $sectionID
     */
    public function setSectionID($sectionID)
    {
        $this->sectionID = $sectionID;
    }

    /**
     * @return mixed
     */
    public function getCourseID()
    {
        return $this->courseID;
    }

    /**
     * @param mixed $courseID
     */
    public function setCourseID($courseID)
    {
        $this->courseID = $courseID;
    }

    /**
     * @return mixed
     */
    public function getCourseName()
    {
        return $this->courseName;
    }

    /**
     * @param mixed $courseName
     */
    public function setCourseName($courseName)
    {
        $this->courseName = $courseName;
    }

    /**
     * @return mixed
     */
    public function getCourseDescription()
    {
        return $this->courseDescription;
    }

    /**
     * @param mixed $courseDescription
     */
    public function setCourseDescription($courseDescription)
    {
        $this->courseDescription = $courseDescription;
    }

    /**
     * @return mixed
     */
    public function getCourseTeacher()
    {
        return $this->courseTeacher;
    }

    /**
     * @param mixed $courseTeacher
     */
    public function setCourseTeacher($courseTeacher)
    {
        $this->courseTeacher = $courseTeacher;
    }

    public function create()
    {
        try
        {
            $dbh = DatabaseConnection::getInstance();
            $stmtHandle = $dbh->prepare(
                "INSERT INTO `Course`(
                `sectionID`, 
                `courseID`, 
                `courseName`, 
                `courseDescription`, 
                `courseTeacher`) 
                VALUES (:sectionID, :courseID, :courseName, :courseDescription, :courseTeacher)");

            $stmtHandle->bindValue(":sectionID", $this->sectionID);
            $stmtHandle->bindValue(":courseID", $this->courseID);
            $stmtHandle->bindValue(":courseName", $this->courseName);
            $stmtHandle->bindValue(":courseDescription", $this->courseDescription);
            $stmtHandle->bindValue(":courseTeacher", $this->courseTeacher);

            $success = $stmtHandle->execute();

            if (!$success)
            {
                throw new \PDOException("SQL query execution failed");
            }

        }
        catch (\Exception $e)
        {
            throw $e;
        }
    }

    /*
     * This method updates the corresponding user in the database based on the data this user object holds
     */
    public function update()
    {
        try
        {
            if (empty($this->sectionID))
            {
                die("Error: the Section ID is not provided");
            }
            else
            {
                $dbh = DatabaseConnection::getInstance();
                $stmtHandle = $dbh->prepare(
                    "UPDATE `Course` 
             SET `courseID`= :courseID,
                 `courseName`= :courseName,
                 `courseDescription`= :courseDescription,
                 `courseTeacher`= :courseTeacher,
             WHERE `sectionID` = :sectionID");

                $stmtHandle->bindValue(":courseID", $this->courseID);
                $stmtHandle->bindValue(":courseName", $this->courseName);
                $stmtHandle->bindValue(":courseDescription", $this->courseDescription);
                $stmtHandle->bindValue(":courseTeacher", $this->courseTeacher);

                $success = $stmtHandle->execute();

                if (!$success) {
                    throw new \PDOException("Course full update operation failed");
                }
            }
        }
        catch (\PDOException $e)
        {
            throw $e;
        }
    }

    public function delete()
    {
        try
        {
            if (empty($this->sectionID))
            {
                die("Error: the Section ID is not provided");
            }
            else
            {
                $dbh = DatabaseConnection::getInstance();
                $stmtHandle = $dbh->prepare("DELETE FROM `course` WHERE `sectionID` = :sectionID");
                $stmtHandle->bindValue(":sectionID", $this->getSectionID());
                $success = $stmtHandle->execute();

                if (!$success) {
                    throw new \PDOException("Course full delete operation failed");
                }
            }
        }
        catch (\PDOException $e)
        {
            throw $e;
        }
    }

    /*
     * This method loads a user object with data from the database by the user object's wNumber
     */
    public function load()
    {
        try
        {
            if (empty($this->sectionID))
            {
                die("Error: the Section ID is not provided");
            }
            else
            {
                $dbh = DatabaseConnection::getInstance();
                $stmtHandle = $dbh->prepare("SELECT * FROM `Course` WHERE sectionID = :sectionID");
                $stmtHandle->bindValue(":sectionID", $this->sectionID);

                $stmtHandle->setFetchMode(\PDO::FETCH_ASSOC);
                $success = $stmtHandle->execute();

                if ($success === false) {
                    throw new \PDOException("Error: fail to execute SQL query");
                }
                else
                {

                    $user = $stmtHandle->fetch();

                    if ($this->courseExists())
                    {
                        $this->setCourseID($user['courseID']);
                        $this->setCourseName($user['courseName']);
                        $this->setCourseDescription($user['courseDescription']);
                        $this->setCourseTeacher($user['courseTeacher']);
                    }
                    else
                    {
                        throw new \PDOException("Error: this course does not exist in the database");
                    }
                }
            }
        }
        catch (\PDOException $e)
        {
            throw $e;
        }
    }
}