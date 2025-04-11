<?php

class SolarGateway {
    private PDO $pdo;
    
    public function __construct(Database $database)
    {
        $this->pdo = $database->getConnection();
    }

    public function insert($data) {
        $sql = "
        INSERT INTO `solar` (`Time`, `Working_Mode`, `V_MPPT_1_V`, `V_MPPT_2_V`, `I_MPPT_1_A`, `I_MPPT_2_A`, `Ua_V`, `I_AC_1_A`, `F_AC_1_Hz`, `Power_W`, `Working_Mode_Int`, `Temperature_C`, `Total_Generation_kWh`, `H_Total_h`, `RSSI`, `PF`)
        VALUES (:Time, :Working_Mode, :V_MPPT_1_V, :V_MPPT_2_V, :I_MPPT_1_A, :I_MPPT_2_A, :Ua_V, :I_AC_1_A, :F_AC_1_Hz, :Power_W, :Working_Mode_Int, :Temperature_C, :Total_Generation_kWh, :H_Total_h, :RSSI, :PF)
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
        return $this->pdo->lastInsertId();
    }

    public function findById($id) {
        $sql = "SELECT * FROM `solar` WHERE `ID` = :ID";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['ID' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function findAll() {
        $sql = "SELECT * FROM `solar`";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $data['ID'] = $id;
        $sql = "
        UPDATE `solar`
        SET `Time` = :Time, `Working_Mode` = :Working_Mode, `V_MPPT_1_V` = :V_MPPT_1_V, `V_MPPT_2_V` = :V_MPPT_2_V, `I_MPPT_1_A` = :I_MPPT_1_A, `I_MPPT_2_A` = :I_MPPT_2_A, `Ua_V` = :Ua_V, `I_AC_1_A` = :I_AC_1_A, `F_AC_1_Hz` = :F_AC_1_Hz, `Power_W` = :Power_W, `Working_Mode_Int` = :Working_Mode_Int, `Temperature_C` = :Temperature_C, `Total_Generation_kWh` = :Total_Generation_kWh, `H_Total_h` = :H_Total_h, `RSSI` = :RSSI, `PF` = :PF
        WHERE `ID` = :ID
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
        return $stmt->rowCount();
    }

    public function delete($id) {
        $sql = "DELETE FROM `solar` WHERE `ID` = :ID";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['ID' => $id]);
        return $stmt->rowCount();
    }
}

?>



