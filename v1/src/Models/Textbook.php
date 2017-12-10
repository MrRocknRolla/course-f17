<?php
/**
 * Created by PhpStorm.
 * User: Christopher
 * Date: 12/9/2017
 * Time: 6:48 PM
 */

namespace Course\Controllers;
use Course\Utilities\DatabaseConnection;

class Textbook implements \JsonSerializable
{
    private $sectionID;
    private $textbookID;
    private $textbookName;
    private $textbookAuthor;
    private $optTextbookName;
    private $optTextbookAuthor;

    public function __construct()
    {

    }

    function jsonSerialize()
    {
        $rtn = array(
            'sectionID' => $this->sectionID,
            'textbookID' => $this->textbookID,
            'textbookName' => $this->textbookName,
            'textbookAuthor' => $this->textbookAuthor,
            'optTextbookName' => $this->optTextbookName,
            'optTextbookAuthor' => $this->optTextbookAuthor
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
    public function getTextbookName()
    {
        return $this->textbookName;
    }

    /**
     * @param mixed $textbookName
     */
    public function setTextbookName($textbookName)
    {
        $this->textbookName = $textbookName;
    }

    /**
     * @return mixed
     */
    public function getTextbookAuthor()
    {
        return $this->textbookAuthor;
    }

    /**
     * @param mixed $textbookAuthor
     */
    public function setTextbookAuthor($textbookAuthor)
    {
        $this->textbookAuthor = $textbookAuthor;
    }

    /**
     * @return mixed
     */
    public function getOptTextbookName()
    {
        return $this->optTextbookName;
    }

    /**
     * @param mixed $optTextbookName
     */
    public function setOptTextbookName($optTextbookName)
    {
        $this->optTextbookName = $optTextbookName;
    }

    /**
     * @return mixed
     */
    public function getOptTextbookAuthor()
    {
        return $this->optTextbookAuthor;
    }

    /**
     * @param mixed $optTextbookAuthor
     */
    public function setOptTextbookAuthor($optTextbookAuthor)
    {
        $this->optTextbookAuthor = $optTextbookAuthor;
    }

    /**
     * @return mixed
     */
    public function getTextbookID()
    {
        return $this->textbookID;
    }

    /**
     * @param mixed $textbookID
     */
    public function setTextbookID($textbookID)
    {
        $this->textbookID = $textbookID;
    }



    public function create()
    {
        try
        {
            $dbh = DatabaseConnection::getInstance();
            $stmtHandle = $dbh->prepare(
                "INSERT INTO `Textbook`(
                `sectionID`,
                `textbookID`, 
                `textbookName`, 
                `textbookAuthor`, 
                `optTextbookName`, 
                `optTextbookAuthor`) 
                VALUES (:sectionID, :textbookID, :textbookName, :textbookAuthor, :optTextbookName, :optTextbookAuthor)");

            $stmtHandle->bindValue(":sectionID", $this->sectionID);
            $stmtHandle->bindValue(":textbookID", $this->textbookID);
            $stmtHandle->bindValue(":textbookName", $this->textbookName);
            $stmtHandle->bindValue(":textbookAuthor", $this->textbookAuthor);
            $stmtHandle->bindValue(":optTextbookName", $this->optTextbookName);
            $stmtHandle->bindValue(":optTextbookAuthor", $this->optTextbookAuthor);

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
                die("error: the Section ID is not provided");
            }
            else
            {
                $dbh = DatabaseConnection::getInstance();
                $stmtHandle = $dbh->prepare(
                    "UPDATE `Textbook` 
             SET `textbookID`= :textbookID,
                 `textbookName`= :textbookName,
                 `textbookAuthor`= :textbookAuthor,
                 `optTextbookName`= :optTextbookName,
                 `optTextbookAuthor`= :optTextbookAuthor
             WHERE `sectionID` = :sectionID");

                $stmtHandle->bindValue(":sectionID", $this->sectionID);
                $stmtHandle->bindValue(":textbookID", $this->textbookID);
                $stmtHandle->bindValue(":textbookName", $this->textbookName);
                $stmtHandle->bindValue(":textbookAuthor", $this->textbookAuthor);
                $stmtHandle->bindValue(":optTextbookName", $this->optTextbookName);
                $stmtHandle->bindValue(":optTextbookAuthor", $this->optTextbookAuthor);

                $success = $stmtHandle->execute();

                if (!$success) {
                    throw new \PDOException("Textbook full update operation failed.");
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
                $stmtHandle = $dbh->prepare("DELETE FROM `Textbook` WHERE `sectionID` = :sectionID");
                $stmtHandle->bindValue(":sectionID", $this->getSectionID());
                $success = $stmtHandle->execute();

                if (!$success) {
                    throw new \PDOException("Textbook full delete operation failed.");
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
                $stmtHandle = $dbh->prepare("SELECT * FROM `Textbook` WHERE sectionID = :sectionID");
                $stmtHandle->bindValue(":sectionID", $this->sectionID);

                $stmtHandle->setFetchMode(\PDO::FETCH_ASSOC);
                $success = $stmtHandle->execute();

                if ($success === false) {
                    throw new \PDOException("Error: Fail to execute sql squery");
                }
                else
                {

                    $user = $stmtHandle->fetch();

                    if ($this->textbookExists())
                    {
                        $this->setTextbookID($user['textbookID']);
                        $this->setTextbookName($user['textbookName']);
                        $this->setTextbookAuthor($user['textbookAuthor']);
                        $this->setOptTextbookName($user['opTextbookName']);
                        $this->setOptTextbookAuthor($user['optTextbookAuthor']);
                    }
                    else
                    {
                        throw new \PDOException("Error: this texbook does not exist in the database");
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