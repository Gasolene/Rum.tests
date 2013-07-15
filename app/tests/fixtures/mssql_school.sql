IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'classrooms')
		DROP TABLE [classrooms];
IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'School')
		DROP TABLE [School];
IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'student')
		DROP TABLE [student];
IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'student_classrooms')
		DROP TABLE [student_classrooms];
IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'page')
		DROP TABLE [page];
IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'team')
		DROP TABLE [team];
IF EXISTS (SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'player')
		DROP TABLE [player];

				CREATE TABLE classrooms (
					classroom_id int IDENTITY(1,1) NOT NULL,
					School_id int NULL,
					name varchar(80) NULL,
					CONSTRAINT PK_classroom PRIMARY KEY CLUSTERED 
					(
						classroom_id
					)
				) ON [PRIMARY];

				CREATE TABLE School (
					School_id int IDENTITY(1,1) NOT NULL,
					School_name varchar(80) NULL,
					CONSTRAINT PK_School PRIMARY KEY CLUSTERED 
					(
						School_id
					)
				) ON [PRIMARY];

				CREATE TABLE student (
					student_id int IDENTITY(1,1) NOT NULL,
					student_name varchar(80) NULL,
					student_age varchar(80) NULL,
					CONSTRAINT PK_student PRIMARY KEY CLUSTERED 
					(
						student_id
					)
				) ON [PRIMARY];

				CREATE TABLE student_classrooms (
					student_id int NOT NULL,
					classroom_id int NOT NULL,
					CONSTRAINT PK_student_class PRIMARY KEY CLUSTERED 
					(
						student_id,
						classroom_id
					)
				);

				CREATE TABLE [page] (
					page_id int IDENTITY(1,1) NOT NULL,
					parent_id int NULL,
					CONSTRAINT PK_page PRIMARY KEY CLUSTERED 
					(
						page_id
					)
				) ON [PRIMARY];

				CREATE TABLE [team] (
					team_id int IDENTITY(1,1) NOT NULL,
					player_id int NULL,
					CONSTRAINT PK_team PRIMARY KEY CLUSTERED 
					(
						team_id
					)
				) ON [PRIMARY];

				CREATE TABLE [player] (
					player_id int IDENTITY(1,1) NOT NULL,
					team_id int NULL,
					CONSTRAINT PK_player PRIMARY KEY CLUSTERED 
					(
						player_id
					)
				) ON [PRIMARY];