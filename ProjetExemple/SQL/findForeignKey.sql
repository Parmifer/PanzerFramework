use INFORMATION_SCHEMA;

select TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
from KEY_COLUMN_USAGE
where TABLE_SCHEMA = $db and TABLE_NAME = $table
and referenced_column_name is not NULL;

--------------------------------------------------------------------------------------------------------

select COLUMNS.COLUMN_KEY, COLUMNS.COLUMN_NAME, COLUMNS.TABLE_NAME from COLUMNS
join KEY_COLUMN_USAGE on COLUMNS.COLUMN_NAME = KEY_COLUMN_USAGE.COLUMN_NAME
where KEY_COLUMN_USAGE.TABLE_NAME = $table 
and referenced_table_name is not null

------------------------------------------------------------------------------------------------------------

select K.TABLE_NAME, K.COLUMN_NAME, C.COLUMN_KEY, K.REFERENCED_TABLE_NAME
from KEY_COLUMN_USAGE K, COLUMNS C
where C.COLUMN_NAME = K.COLUMN_NAME
and C.TABLE_NAME = K.TABLE_NAME
and K.TABLE_SCHEMA = $db and K.TABLE_NAME = $table
and K.referenced_column_name is not NULL;

---------------------------------------------------------------------------------------------------

select count(K.TABLE_NAME) as nbResult, K.TABLE_NAME, K.COLUMN_NAME, C.COLUMN_KEY, K.REFERENCED_TABLE_NAME
from KEY_COLUMN_USAGE K, COLUMNS C
where C.COLUMN_NAME = K.COLUMN_NAME
and C.TABLE_NAME = K.TABLE_NAME
and K.TABLE_SCHEMA = $db
and C.COLUMN_KEY = 'PRI'
and K.referenced_column_name is not NULL
having count(K.TABLE_NAME) = 2;

--------------------------------------------------------------------------------

select K.REFERENCED_TABLE_NAME
from KEY_COLUMN_USAGE K, COLUMNS C
where C.COLUMN_NAME = K.COLUMN_NAME
and C.TABLE_NAME = K.TABLE_NAME
and K.TABLE_SCHEMA = ?
and C.COLUMN_KEY = 'PRI'
and C.TABLE_NAME = ?
and K.REFERENCED_TABLE_NAME <> ?
and K.referenced_column_name is not NULL