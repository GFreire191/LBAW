# Database Specification

## lbaw23153
October 2023
|Name|E-mail|Number|
|---|---|---|
|Diogo Vale| up201805152@up.pt | 201805152|
|Guilherme Freire| up202004809@up.pt | 202004809|
|João Lopes| up201805078@up.pt | 201805078|
|João Correia| up201905892@edu.fc.up.pt | 201905892 |

# A4: Conceptual Data Model

## 4.1 Class Diagram
![](https://hackmd.io/_uploads/By5ZtmwMp.png)



## 4.2 Additional Business Rules

| Business Rules | Description  |
|---|---|
| BR.101 | Administrators are participating members of the community, i.e. can post or vote on questions or answers.  |
| BR.102 | Questions and answers edited after being posted should have a clear indication of the editions.|
| BR.103 | User badges are dependent on the likes and dislikes received on his questions and answers, and also on actions made by the user (first question, first answer, etc).|
| BR.104 | Post's popularity shall be gathered through its engagement metrics (likes, comments, etc).|





# A5: Relational Schema, Validation and Schema Refinement

## 5.1 Relational Schema
| Relation | |
|---|---|
| R01|users(<ins>id</ins>, username **UK NN**, email **UK NN**, password **NN**, profile_pic, is_admin)|
| R02|question(<ins>id</ins>, user_id -> users, content **NN**, date **DF** Today,up_votes **DF** 0, down_votes **DF** 0)|
| R03|answer(<ins>id</ins>, user_id -> use, question_id -> question, content **NN**, date **DF** Today, up_votes **DF** 0, down_votes **DF** 0, correct **DF** false)|
| R04|comment(<ins>id</ins>, user_id -> users,parent_id, parent_type **NN** **CK** parent_type **IN** Post_Type, content **NN**, date **DF** Today)|
| R05|vote(<ins>id</ins>, user_id -> users, parent_id, vote_type **NN CK** vote_type **IN** vote_type, parent_type **NN** **CK** parent_type **IN** post_type)|
| R06|tag(<ins>name</ins> **UK** **CK** name in tag_type)|
| R07|notification(<ins>id</ins>, user_id -> users, noti_type **NN CK** noti_type **IN** parent_type, content **NN**, isRead **DF** false, date **DF** Today)|
| R08|followTag(<ins>user_id</ins> -> users, <ins>tag_id</ins> -> tag)   |
| R09| followQuestion(<ins>user_id</ins> -> users, <ins>question_id</ins> -> question)|
| R10| questionTag(<ins>question_id</ins> -> question, <ins>tag_id</ins> -> tag)|

Legend:
- UK = UNIKE KEY
- NN = NOT NULL
- DF = DEFAULT
- CK = CHECK

## 5.2 Domains

Specification of additional domains:
| | |
|---|---|
|Today|DATE DEFAULT CURRENT_DATE|
|parent_type| ENUM ('vote', 'answer')|
|post_type| ENUM ('question', 'Answer')|
|tag_type| ENUM ('MUSIC', 'MOVIES', 'IT','MATH')
|vote_type| ENUM ('up','down')




## 5.3 Schema Validation

The schema validation with identification of the functional dependencies for all the tables and their normal forms.


| **TABLE R01**   | users |
| -----------     | ------------------ |
| **Keys**        | {id}, {username}, {email} |
| **Functional Dependencies:** |       |
| FD0101          | id -> {username, email, password, profile_pic, is_admin} |
| FD0102          | username -> {id, email, password, profile_pic, is_admin} |
| FD0103          | email -> {id, username, password, profile_pic, is_admin} |
| **NORMAL FORM** | BCNF               |


| **TABLE R02**   | question           |
| --------------  | ---                |
| **Keys**        | { id }             |
| **Functional Dependencies:** |       |
| FD0301          | id -> {user_id, content, date, up_votes, down_votes} |
| **NORMAL FORM** | BCNF               |


| **TABLE R03**   | answer             |
| --------------  | ---                |
| **Keys**        | { id }             |
| **Functional Dependencies:** |       |
| FD0401          | id → {user_id, question_id, content, date, upvotes, downvotes, correct} |
| **NORMAL FORM** | BCNF               |


| **TABLE R04**   | comment            |
| --------------  | ---                |
| **Keys**        | { id }             |
| **Functional Dependencies:** |       |
| FD0501          | id → {user_id, parent_id, parent_type, content, date} |
| **NORMAL FORM** | BCNF               |

| **TABLE R05**   | tag                |
| --------------  | ---                |
| **Keys**        | { id }, { name }   |
| **Functional Dependencies:** |       |
| FD0601          | id → {name}        |
| FD0602          | name → {id}        |
| **NORMAL FORM** | BCNF               |


| **TABLE R06**   | questionTag       |
| --------------  | ---                |
| **Keys**        | { question_id, tag_id } |
| **Functional Dependencies:** |       |
| (none) |
| **NORMAL FORM** | BCNF               |


| **TABLE R07**   | notification       |
| --------------  | ---                |
| **Keys**        | { id }             |
| **Functional Dependencies:** |       |
| FD1001          | id → {user_id, noti_type, content, isRead, date } |
| **NORMAL FORM** | BCNF               |



| **TABLE R08**   | vote       |
| --------------  | ---                |
| **Keys**        | { id }             |
| **Functional Dependencies:** |       |
| FD1001          | id → {user_id, parent_id, parent_type, vote_type } |
| **NORMAL FORM** | BCNF               |


| **TABLE R09**   | followTag         |
| --------------  | ---                |
| **Keys**        | { user_id, tag_id } |
| **Functional Dependencies:** |       |
| (none)          |                    |
| **NORMAL FORM** | BCNF               |


| **TABLE R10**   | followQuestion    |
| --------------  | ---                |
| **Keys**        | { user_id, question_id } |
| **Functional Dependencies:** |       |
| (none)          |                    |
| **NORMAL FORM** | BCNF               |



By using the BCNF, we prevent anomalies in the datatbase and we can reduce redundancy compared to 3NF.








# A6: Indexes, Triggers, Transactions and Database Population

## Database workload


| **Relation reference** | **Relation Name** | **Order of magnitude** | **Estimated growth** |
| ------------------ | ------------- | ------------------------- | -------- |
| R01| users  |10 k (tens of thousands) |10 (tens) / day|
| R02                | question      | hundreds of thousands     | hundreds per day |
| R03                | answer        | hundreds of thousands     | hundreds per day |
| R04                | comment       | millions                  | thousands per day |
| R05                | vote          | tens of millions          | hundreds of thousands per day |
| R06                | tag           | hundreds                  | units per month |
| R07                | notification  | millions                  | tens of thousands per day 
| R08                | followTag    | hundreds of thousands     | hundreds per day |
| R09                | followQuestion | tens of thousands       | tens per day |
| R10                | questionTag  | hundreds of thousands     | hundreds per day |


## Indexes

### Performance indices

| **Index**           | IDX01                                  |
| ---                 | ---                                    |
| **Relation**        | question    |
| **Attribute**       | date   |
| **Type**            | B-tree              |
| **Cardinality**     | Medium |
| **Clustering**      | No                |
| **Justification**   | This table will be frequently accessed and, since we will present the posts by chronological order, a B-tree index applied on the date is the most suitable. As this table will be updated frequently, clustering is not used.   |

**SQL Code** 
```sql= 
CREATE INDEX questions_date ON question USING btree (date);
```



| **Index**           | IDX02                                  |
| ---                 | ---                                    |
| **Relation**        | answer    |
| **Attribute**       | date   |
| **Type**            | B-tree              |
| **Cardinality**     | Medium |
| **Clustering**      | No                |
| **Justification**   | This table will be frequently accessed and, since we will present the answers by chronological order, a B-tree index applied on the date is the most suitable. As this table will be updated frequently, clustering is not used.   |

**SQL Code** 
```sql= 
CREATE INDEX answers_date ON answer USING btree (date);
```



| **Index**           | IDX03                                  |
| ---                 | ---                                    |
| **Relation**        | answer    |
| **Attribute**       | question_id   |
| **Type**            | Hash              |
| **Cardinality**     | Medium |
| **Clustering**      | No                |
| **Justification**   | This table will be frequently accessed and, we will need to retrive the answers related to a question. Since we know the exact value for question_id we use the hash type, clustering is not used.  |

**SQL Code** 
```sql= 
CREATE INDEX answers_of_question ON answer USING hash (question_id);
```


| **Index**           | IDX04                                  |
| ---                 | ---                                    |
| **Relation**        | comment    |
| **Attribute**       | parent_id   |
| **Type**            | Hash              |
| **Cardinality**     | Medium |
| **Clustering**      | No                |
| **Justification**   | This table will be frequently accessed and, we will need to retrive the comments related to a question/answer. Since we know the exact value for parent_id we use the hash type, clustering is not used.  |

**SQL Code** 
```sql= 
CREATE INDEX comments_of_parent ON comment USING hash (parent_id);
```



### Full-text Search indexes

| **Index**           | IDX05                                  |
| ---                 | ---                                    |
| **Relation**        | question    |
| **Attribute**       | title,content   |
| **Type**            | GIN              |
| **Clustering**      | No                |
| **Justification**   |  In our website, users can search for questions by their title and content, so a FST index is suitable for this feature. Since question title and content is static, a GIN FST index is the most appropriate. However, the question table will be modified often, so we won't use clustering. |

**SQL code**
```sql=
ALTER TABLE question ADD COLUMN tsvectors TSVECTOR;

CREATE FUNCTION question_search_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
            setweight(to_tsvector('english',New.title), 'A')
            setweight(to_tsvector('english', NEW.content), 'B')
        );
    END IF;
    IF TG_OP = 'UPDATE' THEN
        IF(NEW.title <> OLD.title OR NEW.content <> OLD.content) THEN
            NEW.tsvectors = (
                setweight(to_tsvector('english', NEW.title), 'A')
                setweight(to_tsvector('english', NEW.content), 'B')
            );
        END IF;
    END IF;
    RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER question_search_update
    BEFORE INSERT OR UPDATE ON question
    FOR EACH ROW
    EXECUTE PROCEDURE question_search_update();

CREATE INDEX question_search_idx ON question USING GIN(tsvectors);
```

| **Index**           | IDX06                                  |
| ---                 | ---                                    |
| **Relation**        | users    |
| **Attribute**       | username   |
| **Type**            | GIN              |
| **Clustering**      | No                |
| **Justification**   |  In our website, users can search for users by their usernames. A GIN FST index is suitable for this purpose, since usernames are static. |

**SQL code**
```sql=
ALTER TABLE users ADD COLUMN tsvectors TSVECTOR;

CREATE FUNCTION user_search_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
            setweight(to_tsvector('english',New.username), 'A')
        );
    END IF;
    IF TG_OP = 'UPDATE' THEN
        IF(NEW.username <> OLD.username) THEN
            NEW.tsvectors = (
                setweight(to_tsvector('english', NEW.username), 'A')
            );
        END IF;
    END IF;
    RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER user_search_update
    BEFORE INSERT OR UPDATE ON users
    FOR EACH ROW
    EXECUTE PROCEDURE user_search_update();

CREATE INDEX user_search_idx ON users USING GIN(tsvectors);
```







## Triggers

|Trigger1| When a vote is made, create a notification for it  |
|---|---|

```sql=  
CREATE FUNCTION process_vote_notification()
RETURNS TRIGGER AS $$
BEGIN

    DECLARE vote_user TEXT;
    DECLARE vote_type_msg TEXT;
    DECLARE content TEXT;

    SELECT INTO vote_user users.username FROM users WHERE users.id = NEW.user_id;
    
    IF NEW.vote = 'down' THEN
        vote_type_msg := 'didn''t like your';
    ELSE
        vote_type_msg := 'liked your';
    END IF;

    IF NEW.voteable_type = 'question' THEN
        content := vote_user || ' ' || vote_type_msg || ' question.';
    ELSE
        content := vote_user || ' ' || vote_type_msg || ' answer.';
    END IF;

    INSERT INTO notification (created_at, user_id, content, noti_type)
    VALUES (NOW(), (SELECT user_id FROM question WHERE id = NEW.voteable_id), content, 'vote');

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER vote_notification
AFTER INSERT
ON vote
FOR EACH ROW
EXECUTE FUNCTION process_vote_notification();
```

|Trigger2| When a question is answered, create a notification for it  |
|---|---|

```sql=
CREATE TRIGGER answer_notification
AFTER INSERT
ON answer
FOR EACH ROW
BEGIN

    DECLARE answer_user VARCHAR(50);
    SET answer_user = (SELECT username FROM users WHERE id = NEW.user_id);
    
    SET @content = CONCAT('Your question received an answer from ', answer_user, '.');
    
    INSERT INTO notification (created_at, user_id, content, noti_type)
    VALUES (NOW(), (SELECT user_id FROM question WHERE id = NEW.question_id), @content, 'answer');
END;
```

## Transactions

|Transaction1| Adding tags to a question  |
|---|---|
|Objective | Make sure that when a question is raised, all the corresponding tags are saved and linked to said question.|
| Isolation Level |Repeatable Read|

```sql=
START TRANSACTION;

INSERT INTO question (id, title, content, user_id, created_at, up_votes, down_votes)
VALUES ($id, $title, $content, $user_id, NOW(), $up_votes, $down_votes);


INSERT INTO questionTag (question_id, tag_id)
VALUES ($question_id, $tag_id_1);

INSERT INTO questionTag (question_id, tag_id)
VALUES ($question_id, $tag_id_2);

COMMIT;
```


# A. Complete SQL Code

## A.1 Database schema

```sql=
DROP TABLE IF EXISTS users CASCADE;
DROP TABLE IF EXISTS question CASCADE;
DROP TABLE IF EXISTS answer CASCADE;
DROP TABLE IF EXISTS comment CASCADE;
DROP TABLE IF EXISTS tag CASCADE;
DROP TABLE IF EXISTS vote CASCADE;
DROP TABLE IF EXISTS notification CASCADE;
DROP TABLE IF EXISTS followTag CASCADE;
DROP TABLE IF EXISTS followQuestion CASCADE;
DROP TABLE IF EXISTS questionTag CASCADE;



CREATE TYPE post_type AS ENUM('question', 'answer');
CREATE TYPE vote_type AS ENUM('up', 'down');
CREATE TYPE parent_type AS ENUM('vote', 'answer');
CREATE TYPE tag_type AS ENUM('CINEMA', 'MUSIC', 'IT', 'MATH');

CREATE TABLE users (
    id SERIAL,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    profile_picture_id TEXT,
    PRIMARY KEY (id),
    UNIQUE (username),
    UNIQUE (email)
);

CREATE TABLE question (
    id SERIAL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT,
    up_votes INT,
    down_votes INT,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE answer (
    id SERIAL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT,
    question_id INT,
    correct BOOLEAN DEFAULT FALSE,
    up_votes INT,
    down_votes INT,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (question_id) REFERENCES question(id)
);

CREATE TABLE comment (
    id SERIAL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT,
    parent_id INT,
    parent_type post_type NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE tag (
    id SERIAL,
    name tag_type NOT NULL,
    PRIMARY KEY (id),
    UNIQUE (name)
);

CREATE TABLE vote (
    id SERIAL,
    user_id INT,
    parent_id INT,
    parent_type post_type NOT NULL,
    vote vote_type NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE notification (
    id SERIAL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT,
    content TEXT NOT NULL,
    noti_type parent_type NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE followTag (
    user_id INT,
    tag_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (tag_id) REFERENCES tag(id),
    PRIMARY KEY (user_id, tag_id)
);

CREATE TABLE followQuestion (
    user_id INT,
    question_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (question_id) REFERENCES question(id),
    PRIMARY KEY (user_id, question_id)
);

CREATE TABLE questionTag (
    question_id INT,
    tag_id INT,
    FOREIGN KEY (question_id) REFERENCES question(id),
    FOREIGN KEY (tag_id) REFERENCES tag(id),
    PRIMARY KEY (question_id, tag_id)
);


--Indexes Creation--

CREATE INDEX questions_date ON question USING btree (date);
CREATE INDEX answers_date ON answer USING btree (date);
CREATE INDEX answers_of_question ON answer USING hash (question_id);
CREATE INDEX comments_of_parent ON comment USING hash (parent_id);

--Full-text Search Creation--

--FTS 1 (Questions)--
ALTER TABLE question ADD COLUMN tsvectors TSVECTOR;

CREATE FUNCTION question_search_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
            setweight(to_tsvector('english',New.title), 'A')
            setweight(to_tsvector('english', NEW.content), 'B')
        );
    END IF;
    IF TG_OP = 'UPDATE' THEN
        IF(NEW.title <> OLD.title OR NEW.content <> OLD.content) THEN
            NEW.tsvectors = (
                setweight(to_tsvector('english', NEW.title), 'A')
                setweight(to_tsvector('english', NEW.content), 'B')
            );
        END IF;
    END IF;
    RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER question_search_update
    BEFORE INSERT OR UPDATE ON question
    FOR EACH ROW
    EXECUTE PROCEDURE question_search_update();

CREATE INDEX question_search_idx ON question USING GIN(tsvectors);


-- Full-text Search 2 (Users)--
ALTER TABLE users ADD COLUMN tsvectors TSVECTOR;

CREATE FUNCTION user_search_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
            setweight(to_tsvector('english',New.username), 'A')
        );
    END IF;
    IF TG_OP = 'UPDATE' THEN
        IF(NEW.username <> OLD.username) THEN
            NEW.tsvectors = (
                setweight(to_tsvector('english', NEW.username), 'A')
            );
        END IF;
    END IF;
    RETURN NEW;
END $$
LANGUAGE plpgsql;

CREATE TRIGGER user_search_update
    BEFORE INSERT OR UPDATE ON users
    FOR EACH ROW
    EXECUTE PROCEDURE user_search_update();

CREATE INDEX user_search_idx ON users USING GIN(tsvectors);




--Triggers Creation--
CREATE TRIGGER vote_notification
AFTER INSERT
ON vote
FOR EACH ROW
BEGIN

    DECLARE vote_user VARCHAR(50);
    SET vote_user = (SELECT username FROM user WHERE id = NEW.user_id);
    
    DECLARE vote_type_msg VARCHAR(10);
    IF NEW.vote = 'down' THEN
        SET vote_type_msg = 'didn''t like your';
    ELSE
        SET vote_type_msg = 'liked your';
    END IF;
    
    SET @content = CONCAT(vote_user, ' ', vote_type_msg, ' ');
    
    IF NEW.voteable_type = 'question' THEN
        SET @content = CONCAT(@content, 'question.');
    ELSE
        SET @content = CONCAT(@content, 'answer.');
    END IF;
    
    INSERT INTO notification (created_at, user_id, content, noti_type)
    VALUES (NOW(), (SELECT user_id FROM CASE NEW.voteable_type
                            WHEN 'question' THEN 'question'
                            WHEN 'answer' THEN 'answer'
                        END
                WHERE id = NEW.voteable_id), @content, 'vote');
END;


CREATE TRIGGER answer_notification
AFTER INSERT
ON answer
FOR EACH ROW
BEGIN

    DECLARE answer_user VARCHAR(50);
    SET answer_user = (SELECT username FROM user WHERE id = NEW.user_id);
    
    SET @content = CONCAT('Your question received an answer from ', answer_user, '.');
    
    INSERT INTO notification (created_at, user_id, content, noti_type)
    VALUES (NOW(), (SELECT user_id FROM question WHERE id = NEW.question_id), @content, 'answer');
END;


```

## A.2 Database population

```sql=
INSERT INTO users (id, username, password, email, is_admin, profile_picture_id)
VALUES 
    (1, 'john_doe', 'password123', 'john.doe@example.com', false, 'profile1.jpg'),
    (2, 'jane_smith', 'securepass', 'jane.smith@example.com', false, 'profile2.jpg'),
    (3, 'admin', 'adminpassword', 'admin@example.com', true, 'profile3.jpg');

INSERT INTO question (id, title, content, user_id, up_votes, down_votes)
VALUES 
    (1, 'Favorite CINEMA Films of 2023?', 'What are some of the must-watch CINEMA films of 2023? Share your recommendations!', 1, 5, 2),
    (2, 'Best Music Albums for 2023', 'Which MUSIC albums released in 2023 have been receiving critical acclaim? I''m looking for new music to listen to.', 2, 3, 1),
    (3, 'Latest IT Innovations', 'What are some of the latest breakthroughs and innovations in the IT field for 2023?', 1, 7, 3),
    (4, 'Challenging MATH Problems', 'I''m stuck on a particularly challenging MATH problem. Can anyone provide some guidance?', 2, 8, 0);

INSERT INTO answer (id, content, user_id, question_id, correct, up_votes, down_votes)
VALUES 
    (1, 'In the world of CINEMA, "Dreamscape Unbound" has been praised for its innovative storytelling and stunning visuals.', 3, 1, true, 4, 1),
    (2, 'In the MUSIC scene, "Echoes of Harmony" by SoundScape is considered a masterpiece, pushing boundaries in sound design.', 1, 2, false, 2, 1),
    (3, 'The IT industry has seen significant advances in quantum computing, potentially revolutionizing data processing capabilities.', 3, 3, true, 5, 0),
    (4, 'Regarding the MATH problem, can you provide more details about the specific question you''re struggling with?', 1, 4, false, 4, 0);

INSERT INTO comment (id, content, user_id, parent_id, parent_type)
VALUES 
    (1, 'I absolutely loved "Dreamscape Unbound"! The storytelling was truly captivating.', 2, 1, 'answer'),
    (2, 'SoundScape always delivers exceptional music. "Echoes of Harmony" is a testament to their artistry.', 3, 2, 'answer'),
    (3, 'The potential of quantum computing in IT is incredibly exciting. It opens up a world of possibilities.', 1, 3, 'answer'),
    (4, 'I''m interested in learning more about quantum computing. Can you recommend any good resources?', 2, 3, 'answer'),
    (5, 'Could you please specify which part of the MATH problem you find challenging?', 3, 4, 'answer');

INSERT INTO tag (id, name)
VALUES 
    (1, 'CINEMA'),
    (2, 'MUSIC'),
    (3, 'IT'),
    (4, 'MATH');

INSERT INTO vote (id, user_id, parent_id, parent_type, vote)
VALUES 
    (1, 1, 1, 'question', 'up'),
    (2, 2, 1, 'question', 'down'),
    (3, 3, 1, 'answer', 'up'),
    (4, 1, 2, 'answer', 'up'),
    (5, 2, 2, 'answer', 'down');

INSERT INTO notification (id, created_at, user_id, content, noti_type, is_read)
VALUES 
    (1, '2023-10-26 10:00:00', 1, 'You received an upvote on your CINEMA question', 'question', false),
    (2, '2023-10-25 12:00:00', 2, 'Your MUSIC answer was accepted as correct', 'answer', false);

INSERT INTO followTag (user_id, tag_id)
VALUES 
    (1, 1),
    (2, 2),
    (3, 3);

INSERT INTO followQuestion (user_id, question_id)
VALUES 
    (1, 1),
    (2, 2),
    (3, 3);

INSERT INTO questionTag (question_id, tag_id)
VALUES 
    (1, 1),
    (1, 2),
    (2, 2),
    (3, 3);
```

