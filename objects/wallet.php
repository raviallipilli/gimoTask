<?php

class Wallet
{
    // database connection and table name
    private $conn;
    private $wallet_table_name = "wallets";

    // object properties
    public $id;
    public $name;
    public $hashKey;

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // list wallet
    function myList()
    {
        // query to insert record
        $query = "SELECT * FROM " . $this->wallet_table_name . "
         ORDER BY name ASC";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    return $stmt;
    }

    // create wallet
    function create()
    {
        // query to insert record
        $query = "INSERT INTO
                " . $this->wallet_table_name . "
            SET
                name=:name, hashKey=:hashKey";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->hashKey = htmlspecialchars(strip_tags($this->hashKey));

        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":hashKey", md5($this->hashKey));

        // execute query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // delete the product
    function delete()
    {
        // delete query
        $query = "DELETE FROM " . $this->table_name . " WHERE name = ? 
        and hashKey = ? ";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        // $this->id=htmlspecialchars(strip_tags($this->id));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->hashKey = htmlspecialchars(strip_tags($this->hashKey));

        // bind id of record to delete
        // $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(1, $this->name);
        $stmt->bindParam(2, md5($this->hashKey));

        // execute query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}