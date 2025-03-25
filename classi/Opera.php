<?php
require_once __DIR__ . '/../inc/require.php';

class Opera
{
    protected $ope_id;
    static protected $tabella = 'opere';
    public $record;
    public $dati;

    function __construct($id)
    {
        $this->ope_id = $id;
        $this->setRecord();
        $this->setDati();
    }

    private function setRecord()
    {
        global $dbo;
        $this->record = $dbo->find(self::$tabella, 'ope_id', $this->ope_id);
    }

    private function setDati()
    {
        $this->dati = $this->record;
    }

    static function getOpere($where = "", $bind = [])
    {
        global $dbo;
        $query = "SELECT * FROM " . self::$tabella . " WHERE 1 $where";
        $dbo->query($query);
        $dbo->execute($bind);
        return $dbo->resultset();
    }
}
?>
