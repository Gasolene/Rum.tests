
	CREATE TABLE [test] (
		test_id int IDENTITY(1,1) NOT NULL,
		test_float float,
		test_double decimal,
		test_decimal decimal,
		test_bool tinyint,

		test_char char(2),
		test_varchar varchar(80),
		test_blob text,

		test_date datetime,
		test_time datetime,
		test_datetime datetime,
		test_timestamp timestamp NOT NULL,

		CONSTRAINT PK_test2 PRIMARY KEY CLUSTERED 
		(
			test_id
		)
	) ON [PRIMARY];
