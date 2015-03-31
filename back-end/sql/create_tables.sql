CREATE TABLE Publication(
	pub_id INT(16),
    title VARCHAR(256),
    pub_year	year(4),
    cite_count	INT(8),
    ISBN	VARCHAR(64),
    PRIMARY KEY (pub_id)
    );

CREATE TABLE Proceeding(
	pub_id	INT(16),
	pages	INT(8),
	PRIMARY KEY (pub_id)
);

CREATE TABLE Article(
	pub_id	INT(16),
	volume	int(4),
	PRIMARY KEY	(pub_id)
);

CREATE TABLE Book(
	pub_id	INT(16),
	version	INT(2),
	publisher	VARCHAR(64),
	PRIMARY KEY (pub_id)
);

CREATE TABLE Author(
	author_id	INT(16),
	name	VARCHAR(64),
	date_of_birth	DATE,
	cite_count	INT(8),
	pub_count	INT(8),
	interest	VARCHAR(256),
	PRIMARY KEY	(author_id)
);

CREATE TABLE Location(
	loc_id	INT(16),
	name	VARCHAR(64),
	field	VARCHAR(64),
	pub_count	INT(8),
	cite_count	INT(8),
	self_cite_count	INT(8),
	PRIMARY KEY (loc_id)
);

CREATE TABLE Tag(
	tag_id	INT(16),
	content	VARCHAR(64),
	PRIMARY KEY (tag_id)
);

CREATE TABLE Author_of(
	pub_id	INT(16),
	author_id	INT(16),
	PRIMARY KEY (pub_id, author_id)
);

-- CREATE TABLE Appear_at(
-- 	pub_id	INT(16),
-- 	loc_id	INT(16),
-- 	PRIMARY KEY (pub_id, loc_id)
-- );

CREATE TABLE Tag_of(
	pub_id INT(16),
	tag_id	INT(16),
	PRIMARY KEY (pub_id, tag_id)
);

CREATE TABLE Cite(
	citee_id	INT(16),
	citer_id	INT(16),
	note_id		INT(16),
	PRIMARY KEY (citee_id, citer_id)
);

CREATE TABLE Note(
	note_id		INT(16),
	content		VARCHAR(2048),
	note_date	DATE,
	rating		INT(1),
	PRIMARY KEY (note_id)
);