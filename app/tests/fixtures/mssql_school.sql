
			$this->db->execute( '
				CREATE TABLE classrooms (
					classroom_id int IDENTITY(1,1) NOT NULL,
					School_id int NULL,
					name varchar(80) NULL,
					CONSTRAINT PK_classroom PRIMARY KEY CLUSTERED 
					(
						classroom_id
					)
				) ON [PRIMARY]' );

			$this->db->execute( '
				CREATE TABLE School (
					School_id int IDENTITY(1,1) NOT NULL,
					School_name varchar(80) NULL,
					CONSTRAINT PK_School PRIMARY KEY CLUSTERED 
					(
						School_id
					)
				) ON [PRIMARY]' );

			$this->db->execute( '
				CREATE TABLE student (
					student_id int IDENTITY(1,1) NOT NULL,
					student_name varchar(80) NULL,
					student_age varchar(80) NULL,
					CONSTRAINT PK_student PRIMARY KEY CLUSTERED 
					(
						student_id
					)
				) ON [PRIMARY]' );

//$this->db->execute( 'drop table student_classrooms' );

			$this->db->execute( '
				CREATE TABLE student_classrooms (
					student_id int NOT NULL,
					classroom_id int NOT NULL,
					CONSTRAINT PK_student_class PRIMARY KEY CLUSTERED 
					(
						student_id,
						classroom_id
					)
				)' );

			$this->db->execute( '
				CREATE TABLE [page] (
					page_id int IDENTITY(1,1) NOT NULL,
					parent_id int NULL,
					CONSTRAINT PK_page PRIMARY KEY CLUSTERED 
					(
						page_id
					)
				) ON [PRIMARY]' );

			$this->db->execute( '
				CREATE TABLE [team] (
					team_id int IDENTITY(1,1) NOT NULL,
					player_id int NULL,
					CONSTRAINT PK_team PRIMARY KEY CLUSTERED 
					(
						team_id
					)
				) ON [PRIMARY]' );

			$this->db->execute( '
				CREATE TABLE [player] (
					player_id int IDENTITY(1,1) NOT NULL,
					team_id int NULL,
					CONSTRAINT PK_player PRIMARY KEY CLUSTERED 
					(
						player_id
					)
				) ON [PRIMARY]' );