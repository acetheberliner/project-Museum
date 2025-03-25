<?php
require_once __DIR__ . '/../inc/require.php';

class Mostra
{
    protected $mos_id;
    static protected $tabella = 'mostre';
    public $record;
    public $dati;

    function __construct($id)
    {
        $this->mos_id = $id;
        $this->setRecord();
        $this->setDati();
    }

    private function setRecord()
    {
        global $dbo;
        $this->record = $dbo->find(self::$tabella, 'mos_id', $this->mos_id);
    }

    private function setDati()
    {
        $this->dati = $this->record;
    }

    static function getMostre($where = "", $bind = [])
    {
        global $dbo;
        $query = "SELECT * FROM " . self::$tabella . " WHERE 1 $where";
        $dbo->query($query);
        $dbo->execute($bind);
        return $dbo->resultset();
    }
}
?>
