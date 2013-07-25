
	IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'test')
		DROP TABLE [test];

	CREATE TABLE [test] (
		test_id int IDENTITY(1,1) NOT NULL,
		test_double decimal(5,2) NOT NULL,
		test_float float NOT NULL,
		test_decimal decimal(8),
		test_bool BIT,

		test_char char(2),
		test_varchar varchar(80),
		test_blob BINARY,
		test_varbinary VARBINARY(255),

		test_date date,
		test_time time,
		test_datetime datetime,

		CONSTRAINT PK_test2 PRIMARY KEY CLUSTERED 
		(
			test_id
		),
		UNIQUE (test_float)
	) ON [PRIMARY];
