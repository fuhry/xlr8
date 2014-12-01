<?php

namespace fuhry\Framework;
use fuhry\Application;
use fuhry\Database;
use PDO;

use InvalidArgumentException;
use RuntimeException;
use LogicException;

/**
 * Template model class. This class will auto-discover types and columns and perform
 * bare-bones data validation. It will probably not honor custom constraints, so
 * a facility is provided for custom data validation as well.
 *
 * @todo Much of the logic in here (specifically related to auto_increment handling)
 *   is MySQL-specific, this should be fixed to be generic or at least support both
 *   MySQL and PostgreSQL.
 * @author Dan Fuhry <dan@fuhry.com>
 */

abstract class AbstractModel
{
	/**
	 * Application.
	 * @var fuhry\Application\AbstractApplication
	 */
	
	protected $App;
	
	/**
	 * Connection to the database.
	 * @var PDO
	 */
	
	protected $DB;
	
	/**
	 * Columns in the table we are working with. An associative array consisting of
	 * instances of classes extending fuhry\Database\Type\AbstractType.
	 * @var array
	 */
	
	protected $columns = [];
	
	/**
	 * Values associated with the current row.
	 */
	
	protected $values = [];
	
	/**
	 * Name of the column which contains the table's primary key.
	 * @var string
	 */
	
	protected $primaryKey = false;
	
	/**
	 * Cache of table definitions. A table definition is an auto-generated list of
	 * columns and associated validation constraints.
	 * @static
	 */
	
	private static $tableDefs = [];
	
	/**
	 * List of columns changed.
	 * @var array
	 */
	
	private $changeList = [];
	
	/**
	 * If true, we are creating a new row.
	 * @var bool
	 */
	
	private $created = false;
	
	/**
	 * Constructor.
	 * 
	 * The Application is mandatory - it provides configuration info as well as the
	 * database connection.
	 * 
	 * You may choose to load an existing entry by passing the primary key's value
	 * as the second parameter. If you do not do this, you can set values on the
	 * newly created object, and then call the insert() method to insert it into
	 * the database.
	 * 
	 * @param fuhry\Application\AbstractApplication
	 * @param mixed Primary key of the row to load. If unspecified, a new row will
	 *   be created when insert() is called.
	 */

	public function __construct(Application\AbstractApplication $App, $load_id = null)
	{
		$this->App = $App;
		$this->DB = $this->App->getDatabaseConnection();
		
		$tableName = $this->getTable();
		
		$tableDef = self::getTableDef($this->DB, $tableName);
		
		$this->columns = $tableDef['columns'];
		$this->primaryKey = $tableDef['primaryKey'];
		
		if ( $load_id !== null && $this->columns[$this->primaryKey]->validate($load_id) ) {
			$this->load($load_id);
		}
		else if ( is_array($load_id) ) {
			foreach ( $load_id as $key => $value ) {
				if ( !isset($this->columns[$key]) ) {
					// ???
					continue;
				}
				
				$this->values[$key] = $this->columns[$key]->cast($value);
			}
		}
		else {
			$this->created = true;
		}
	}
	
	/**
	 * Load multiple rows that match a WHERE clause.
	 * 
	 * This can be used to search a table for multiple results. It will return
	 * an array of models corresponding to each row.
	 * loaded.
	 * 
	 * @static
	 * @final
	 * @param fuhry\Application\AbstractApplication
	 * @param string WHERE clause - i.e. "some_column = 'value'". May contain substitutable values.
	 * @param array Optional array of values to be substituted into the query
	 * @return array
	 */
	
	final public static function loadWhere(Application\AbstractApplication $App, $whereClause, $values = [])
	{
		$DB = $App->getDatabaseConnection();
		$tableName = static::getTable();
		
		$sql = "SELECT * FROM {$tableName} WHERE $whereClause;";
		$stmt = $DB->prepare($sql);
		$stmt->execute($values);
		
		$result = [];
		while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
			$result[] = new static($App, $row);
		}
		
		return $result;
	}
	
	/**
	 * Returns the name of the table we will be working with. Child classes
	 * MUST implement this method.
	 * @access protected
	 * @static
	 * @abstract
	 * @return string
	 */
	
	abstract protected static function getTable();
	
	/**
	 * Set a value in this row.
	 * @final
	 * @param string Column name
	 * @param mixed Value - must pass validation
	 * @return object $this
	 * @throws InvalidArgumentException An InvalidArgumentException will be thrown if
	 *   validation fails, or if the column name specified does not exist in the table.
	 */
	
	final public function set($key, $value)
	{
		if ( !isset($this->columns[$key]) ) {
			throw new InvalidArgumentException("The table {$this->getTable()} does not contain a column named \"$key\"");
		}
		
		if ( !$this->columns[$key]->validate($value) ) {
			throw new InvalidArgumentException("An invalid value for {$this->getTable()}.{$key} was passed: $value");
		}
		if ( $this->columns[ $this->getPrimaryKey() ] instanceof Database\Types\Integer && 
				$this->columns[ $this->getPrimaryKey() ]->isAutoIncrement() &&
				$key === $this->getPrimaryKey() ) {
			throw new InvalidArgumentException("Modification of the primary key is not allowed for tables using auto_increment ({$this->getTable()}.$key).");
		}
		
		if ( !isset($this->values[$key]) || $this->values[$key] !== $value ) {
			$this->values[$key] = $value;
			$this->changeList[] = $key;
		}
		
		return $this;
	}
	
	/**
	 * Retrieve a value from the row.
	 * @final
	 * @param string Column name
	 * @return mixed Whatever the column type handler casts the value to
	 * @throws InvalidArgumentException An InvalidArgumentException will be thrown if
	 *   the column name specified does not exist in the table.
	 */
	
	final public function get($key)
	{
		if ( !isset($this->columns[$key]) ) {
			throw new InvalidArgumentException("The table {$this->getTable()} does not contain a column named \"$key\"");
		}
		
		if ( !isset($this->values[$key]) ) {
			return null;
		}
		
		return $this->columns[$key]->cast($this->values[$key]);
	}
	
	/**
	 * Load an entry from the database.
	 * @param mixed The primary key. Usually an integer, but up to the table. Will be validated
	 *   against the column handler for the primary key.
	 * @return object $this
	 * @final
	 * @throws InvalidArgumentException An InvalidArgumentException will be thrown if
	 *   the provided primary key does not validate against the column handler.
	 * @throws RuntimeException A RuntimeException will be thrown if no rows match the
	 *   query.
	 */
	
	final public function load($id)
	{
		if ( !$this->columns[ $this->getPrimaryKey() ]->validate($id) ) {
			throw new InvalidArgumentException("Column handler for {$this->getPrimaryKey()} reports that the value \"$id\" is invalid.");
		}
		
		$query = "SELECT * FROM `{$this->getTable()}` WHERE `{$this->getPrimaryKey()}` = :value;";
		$stmt = $this->DB->prepare($query);
		
		if ( $stmt->execute([ 'value' => $id ]) && $stmt->rowCount() ) {
			$this->values = $stmt->fetch(PDO::FETCH_ASSOC);
		}
		else {
			throw new RuntimeException("Failed to retrieve row from table `{$this->getTable()}`: no rows found");
		}
		
		$this->created = false;
		
		return $this;
	}
	
	/**
	 * Update the database with all changes made to the row. May only be called if
	 * updating an existing row. Calling update() without changing any rows is a
	 * no-op and will not result in an error.
	 *
	 * @throws LogicException A LogicException will be thrown if no existing row has
	 *   been loaded. This method cannot insert new rows, it can only update existing
	 *   ones.
	 */
	
	final public function update()
	{
		// if we haven't changed any columns, report success (no-op)
		if ( empty($this->changeList) ) {
			return $this;
		}
		
		if ( $this->created ) {
			throw new LogicException("To insert a newly created item, call insert().");
		}
		
		// go through each modified column and add it to the update list
		$columnList = [];
		$values = [];
		foreach ( $this->changeList as $key ) {
			$columnList[] = "$key = ?";
			$values[] = $this->get($key);
		}
		$values[] = $this->get($this->getPrimaryKey());
		
		// build SQL
		$sql = "UPDATE {$this->getTable()} SET " . implode(', ', $columnList) . " WHERE {$this->getPrimaryKey()} = ?;";
		
		// execute!
		$stmt = $this->DB->prepare($sql);
		$stmt->execute($values);
		
		// clear change list
		$this->changeList = [];
		
		return $this;
	}
	
	/**
	 * Deletes the current entry. The values associated with the entry will remain
	 * cached. The creation state will be reset, so the entry can be recreated if
	 * insert() is called.
	 * @return object $this
	 * @final
	 * @throws LogicException A LogicException will be thrown if attempting to
	 *   delete a row that does not exist.
	 */
	
	final public function delete()
	{
		if ( $this->created ) {
			throw new LogicException("Cannot delete a row that is nonexistent.");
		}
		
		$stmt = $this->prepare("DELETE FROM `{$this->getTable()}` WHERE {$this->getPrimaryKey()} = :id;");
		$stmt->execute($this->getID());
		
		if ( $this->columns[ $this->getPrimaryKey() ] instanceof Database\Types\Integer && 
				$this->columns[ $this->getPrimaryKey() ]->isAutoIncrement() ) {
			unset($this->values[ $this->getPrimaryKey() ]);
		}
		
		$this->created = true;
	}
	
	/**
	 * Insert the newly created row into the database.
	 * @return object $this
	 * @final
	 */
	
	final public function insert()
	{
		if ( !$this->created ) {
			throw new LogicException("Duplication of rows is unsupported - you are trying to call insert() on a loaded row.");
		}
		
		if ( $this->columns[ $this->getPrimaryKey() ] instanceof Database\Types\Integer && 
				$this->columns[ $this->getPrimaryKey() ]->isAutoIncrement() ) {
			unset($this->values[ $this->getPrimaryKey() ]);
		}
		else if ( !isset ( $this->values[$this->getPrimaryKey()]) ) {
			throw new LogicException("No primary key has been set. You must set the column `{$this->getPrimaryKey()}` before calling insert().");
		}
		
		// verify that all columns which are NOT NULL and have no default value, have a value set
		foreach ( $this->columns as $key => $column ) {
			if ( $key === $this->getPrimaryKey() ) {
				// skip primary key validation, this was done above
				continue;
			}
			if ( $column->getDefault() === null && !$column->isNullOk() &&
					(!isset($this->values[$key]) || (isset($this->values[$key]) && $this->values[$key] === null))
					) {
				throw new LogicException("Required column `$key` was not set, cannot insert");
			}
		}
		
		$sql = "INSERT INTO `{$this->getTable()}` ( ";
		$values = [];
		$columns = [];
		$placeholders = [];
		foreach ( $this->changeList as $column ) {
			$columns[] = "`$column`";
			$placeholders[] = '?';
			$values[] = $this->values[$column];
		}
		
		$sql .= implode(', ', $columns);
		$sql .= ' ) VALUES ( ';
		$sql .= implode(', ', $placeholders);
		$sql .= ' );';
		
		$stmt = $this->DB->prepare($sql);
		if ( !$stmt->execute($values) ) {
			throw new Database\Exception($stmt);
		}
		
		// if we inserted into a table that uses auto_increment, we need to set the
		// ID of the new row locally
		if ( $this->columns[ $this->getPrimaryKey() ] instanceof Database\Types\Integer && 
				$this->columns[ $this->getPrimaryKey() ]->isAutoIncrement() ) {
			$result = $this->DB->query("SELECT LAST_INSERT_ID();");
			
			list($id) = $result->fetch(PDO::FETCH_NUM);
			$this->values[ $this->getPrimaryKey() ] = $this->columns[ $this->getPrimaryKey() ]->cast($id);
		}
		
		$this->created = false;
	}
	
	/**
	 * Return the value of the primary key.
	 * @return mixed
	 */
	
	final public function getID()
	{
		return $this->values[ $this->getPrimaryKey() ];
	}
	
	final public function getAll()
	{
		return $this->values;
	}
	
	/**
	 * Get a table definition. A table definition is a list of columns with
	 * instances of fuhry\Database\Type\AbstractType for each column, as well
	 * as the name of the primary key.
	 * 
	 * @final
	 * @access private
	 */
	
	private static function getTableDef(PDO $DB, $tableName)
	{
		static $defs = [];
		
		if ( !isset($defs[$tableName]) ) {
			// retrieve table structure
			//   Field, Type, Null, Key, Default, Extra
			
			$tableDef = [
					'columns' => [],
					'primaryKey' => false
				];
			
			$result = $DB->query("SHOW COLUMNS IN `$tableName`;");
			while ( $row = $result->fetch(PDO::FETCH_ASSOC) ) {
				$tableDef['columns'][ $row['Field'] ] = Database\ColumnFactory::loadColumn($row);
				
				if ( $row['Key'] === 'PRI' ) {
					$tableDef['primaryKey'] = $row['Field'];
				}
			}
			
			$defs[$tableName] = $tableDef;
		}
		
		return $defs[$tableName];
	}
	
	/**
	 * Return the column list as an associative array, each member containing
	 * an instance of fuhry\Database\Type\AbstractType
	 *
	 * @final
	 * @return array
	 */
	
	final public function getColumns()
	{
		return $this->columns;
	}
	
	/**
	 * Return the name of the column which contains the primary key.
	 *
	 * @final
	 * @return string
	 */
	
	final public function getPrimaryKey()
	{
		return $this->primaryKey;
	}
}
