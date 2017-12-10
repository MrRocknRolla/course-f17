<?php
/**
 * Created by PhpStorm.
 * User: Christopher
 * Date: 12/9/2017
 * Time: 6:48 PM
 */

namespace Course\Controllers;
use Course\Utilities\DatabaseConnection;

class Material implements \JsonSerializable
{
    private $sectionID;
    private $pencilType;
    private $penType;
    private $paperType;
    private $calculatorType;
    private $other1;
    private $other2;

    public function __construct()
    {

    }

    function jsonSerialize()
    {
        $rtn = array(
            'sectionID' => $this->sectionID,
            'pencilType' => $this->pencilType,
            'penType' => $this->penType,
            'paperType' => $this->paperType,
            'calculatorType' => $this->calculatorType,
            'other1' => $this->other1,
            'other2' => $this->other2
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
    public function getPencilType()
    {
        return $this->pencilType;
    }

    /**
     * @param mixed $pencilType
     */
    public function setPencilType($pencilType)
    {
        $this->pencilType = $pencilType;
    }

    /**
     * @return mixed
     */
    public function getPenType()
    {
        return $this->penType;
    }

    /**
     * @param mixed $penType
     */
    public function setPenType($penType)
    {
        $this->penType = $penType;
    }

    /**
     * @return mixed
     */
    public function getPaperType()
    {
        return $this->paperType;
    }

    /**
     * @param mixed $paperType
     */
    public function setPaperType($paperType)
    {
        $this->paperType = $paperType;
    }

    /**
     * @return mixed
     */
    public function getCalculatorType()
    {
        return $this->calculatorType;
    }

    /**
     * @param mixed $calculatorType
     */
    public function setCalculatorType($calculatorType)
    {
        $this->calculatorType = $calculatorType;
    }

    /**
     * @return mixed
     */
    public function getOther1()
    {
        return $this->other1;
    }

    /**
     * @param mixed $other1
     */
    public function setOther1($other1)
    {
        $this->other1 = $other1;
    }

    /**
     * @return mixed
     */
    public function getOther2()
    {
        return $this->other2;
    }

    /**
     * @param mixed $other2
     */
    public function setOther2($other2)
    {
        $this->other2 = $other2;
    }

    public function create()
    {
        try
        {
            $dbh = DatabaseConnection::getInstance();
            $stmtHandle = $dbh->prepare(
                "INSERT INTO `Materials`(
                `sectionID`,
                `pencilType`, 
                `penType`, 
                `paperType`, 
                `calculatorType`,
                `other1`
                `other2`) 
                VALUES (:sectionID, :pencilType, :penType, :paperType, :calculatorType, :other1, :other2)");

            $stmtHandle->bindValue(":sectionID", $this->sectionID);
            $stmtHandle->bindValue(":pencilType", $this->pencilType);
            $stmtHandle->bindValue(":penType", $this->penType);
            $stmtHandle->bindValue(":paperType", $this->paperType);
            $stmtHandle->bindValue(":calculatorType", $this->calculatorType);
            $stmtHandle->bindValue(":other1", $this->other1);
            $stmtHandle->bindValue(":other2", $this->other2);

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
                    "UPDATE `Materials` 
             SET `pencilType`= :textbookName,
                 `penType`= :textbookAuthor,
                 `paperType`= :optTextbookName,
                 `calculatorType`= :optTextbookAuthor,
                 `other1`= :other1,
                 `other2`= :other2
             WHERE `sectionID` = :sectionID");

                $stmtHandle->bindValue(":sectionID", $this->sectionID);
                $stmtHandle->bindValue(":pencilType", $this->pencilType);
                $stmtHandle->bindValue(":penType", $this->penType);
                $stmtHandle->bindValue(":paperType", $this->paperType);
                $stmtHandle->bindValue(":calculatorType", $this->calculatorType);
                $stmtHandle->bindValue(":other1", $this->other1);
                $stmtHandle->bindValue(":other2", $this->other2);

                $success = $stmtHandle->execute();

                if (!$success) {
                    throw new \PDOException("Materials full update operation failed");
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
                $stmtHandle = $dbh->prepare("DELETE FROM `Materials` WHERE `sectionID` = :sectionID");
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
                $stmtHandle = $dbh->prepare("SELECT * FROM `Materials` WHERE sectionID = :sectionID");
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
                        $this->setPencilType($user['pencilType']);
                        $this->setPenType($user['penType']);
                        $this->setPaperType($user['paperType']);
                        $this->setCalculatorType($user['calculatorType']);
                        $this->setOther1($user['other1']);
                        $this->setOther2($user['other2']);
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