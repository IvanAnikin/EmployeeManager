<?php

namespace App\Model;

use Nette;
use Nextras\Dbal\ForeignKeyConstraintViolationException;


/**
 * Users management.
 */
class UserManager
{
	use Nette\SmartObject;


	private $orm, $dir;

	public function __construct()
	{
	    $this->dir = __DIR__ . '/Data/employees.xml';
	}

	/**
	 * Adds new user.
	 * @param  string values - employee info
	 * @return void
	 */
	public function add($values)
	{

        $xml = simplexml_load_file($this->dir);
        $list = $xml->record;

        $max_id = 0;
        foreach ($list as $employee){
            if((int)$employee[0]->id>$max_id){
                $max_id=(int)$employee[0]->id;
            }
        }

        $record = $xml->addChild("record", "103");
        $record->addChild("name", $values->name);
        $record->addChild("sex", $values->sex);
        $record->addChild("age", $values->age);
        $record->addChild("id", $max_id+1);

        $xml->asXML($this->dir);
	}

    /**
     * Edit existing user
     * @param  string values - employee info
     * @return void
     */
    public function edit($values)
    {
        $xml = simplexml_load_file($this->dir);

        $employee = $xml->xpath('/employees/record[id='. $values->id .']');
        $employee[0]->name = $values->name;
        $employee[0]->sex = $values->sex;
        $employee[0]->age = $values->age;
        //dump($employee);

        $xml->asXML($this->dir);
    }

    /**
     * Edit existing user
     * @param  string values - employee info
     * @return void
     */
    public function delete($values)
    {
        $xml = simplexml_load_file($this->dir);

        $id=0;
        while(True){
            if((int)$xml->record[$id][0]->id == (int)$values->id){
                unset($xml->record[$id][0]);
                break;
            }
            $id+=1;
        }

        $xml->asXML($this->dir);
    }


    /**
     * Loads all users from XML file
     * @param $username
     */
    public function load_employees()
    {
        $xml = simplexml_load_file($this->dir);
        $list = $xml->record;
        return $list;
    }

}


class DuplicateNameException extends \Exception
{
}
