<?php

class Transaction
{

    // database connection and table name
    private $conn;
    private $transaction_table_name = "transactions";
    private $wallet_table_name = "wallets";

    // object properties
    public $id;
    public $type;
    public $amount;
    public $reference;
    public $timeStamp;
    public $name;
    public $hashKey;
    public $walletLink;

    // constructor with $db as database connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // create transaction
    function create()
    {
        // query to insert record
        $query = "SELECT
                    id
                FROM
                    $this->wallet_table_name
                WHERE
                  name =:name and hashKey =:hashKey";
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->hashKey = htmlspecialchars(strip_tags($this->hashKey));

        // bind id of record to delete
        // $stmt->bindParam(":id", $this->id);
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":hashKey", md5($this->hashKey));

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->walletLink = $row['id'];

        $query = "INSERT INTO
                " . $this->transaction_table_name . "
            SET
                walletLink=:walletLink, type=:type, amount=:amount, reference=:reference, timeStamp=:timeStamp";

        // prepare query
        $stmt = $this->conn->prepare($query);

        // sanitize
        $this->walletLink = htmlspecialchars(strip_tags($this->walletLink));
        $this->type = htmlspecialchars(strip_tags($this->type));
        $this->amount = htmlspecialchars(strip_tags($this->amount));
        $this->reference = htmlspecialchars(strip_tags($this->reference));
        $this->timeStamp = htmlspecialchars(strip_tags($this->timeStamp));

        // bind values
        $stmt->bindParam(":walletLink", $this->walletLink);
        $stmt->bindParam(":type", $this->type);
        $stmt->bindParam(":amount", $this->amount);
        $stmt->bindParam(":reference", $this->reference);
        $stmt->bindParam(":timeStamp", $this->timeStamp);

        // execute query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}