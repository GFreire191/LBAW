DROP SCHEMA IF EXISTS stackunderflow CASCADE;
CREATE SCHEMA IF NOT EXISTS stackunderflow;
SET search_path TO stackunderflow;

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



CREATE TYPE vote_type AS ENUM('up', 'down');
CREATE TYPE noti_parent AS ENUM('vote', 'answer');

CREATE TABLE users (
    id SERIAL,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    is_moderator BOOLEAN DEFAULT FALSE,
    profile_picture_id TEXT,
    bio VARCHAR(255),
    PRIMARY KEY (id),
    UNIQUE (username),
    UNIQUE (email)
);

CREATE TABLE question (
    id SERIAL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    edited BOOLEAN DEFAULT FALSE,
    user_id INT,
    up_votes INT DEFAULT 0,
    down_votes INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE report (
    id SERIAL,
    motive VARCHAR(255),
    user_id INT,
    parent_id INT,
    parent_type VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
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
    up_votes INT DEFAULT 0,
    down_votes INT DEFAULT 0,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (question_id) REFERENCES question(id) ON DELETE CASCADE
);

CREATE TABLE comment (
    id SERIAL,
    content TEXT NOT NULL,
    edited BOOLEAN DEFAULT FALSE,
    user_id INT,
    parent_id INT,
    parent_type VARCHAR(255) NOT NULL,
    updated_at TIMESTAMP DEFAULT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE tag (
    id SERIAL,
    name VARCHAR(255) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE (name)
);

CREATE TABLE vote (
    id SERIAL,
    user_id INT,
    parent_id INT,
    parent_type VARCHAR(255) NOT NULL,
    vote_status vote_type NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE notification (
    id SERIAL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT,
    user_notification INT,
    content TEXT NOT NULL,
    noti_source noti_parent NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (user_notification) REFERENCES users(id)
);

CREATE TABLE followTag (
    user_id INT,
    tag_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (tag_id) REFERENCES tag(id) ON DELETE CASCADE,
    PRIMARY KEY (user_id, tag_id)
);

CREATE TABLE followQuestion (
    user_id INT,
    question_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (question_id) REFERENCES question(id) ON DELETE CASCADE,
    PRIMARY KEY (user_id, question_id)
);

CREATE TABLE questionTag (
    question_id INT,
    tag_id INT,
    FOREIGN KEY (question_id) REFERENCES question(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tag(id) ON DELETE CASCADE,
    PRIMARY KEY (question_id, tag_id)
);



----INDEX----
CREATE INDEX questions_date ON question USING btree (created_at);
CREATE INDEX answers_date ON answer USING btree (created_at);




ALTER TABLE question ADD COLUMN tsvectors TSVECTOR;

CREATE FUNCTION question_search_update() RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' THEN
        NEW.tsvectors = (
            setweight(to_tsvector('english',New.title), 'A') ||
            setweight(to_tsvector('english', NEW.content), 'B')
        );
    END IF;
    IF TG_OP = 'UPDATE' THEN
        IF(NEW.title <> OLD.title OR NEW.content <> OLD.content) THEN
            NEW.tsvectors = (
                setweight(to_tsvector('english', NEW.title), 'A') ||
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



-- When a vote is made, create a notification for it
CREATE OR REPLACE FUNCTION process_vote_notification()
RETURNS TRIGGER AS $$
DECLARE 
    vote_user TEXT;
    vote_type_msg TEXT;
    content TEXT;
BEGIN
    SELECT INTO vote_user users.username FROM users WHERE users.id = NEW.user_id;

    IF NEW.vote_status = 'down' THEN
        vote_type_msg := 'didn''t like your';
    ELSE
        vote_type_msg := 'liked your';
    END IF;

    IF NEW.parent_type = 'App\Models\Question' THEN
        content := vote_user || ' ' || vote_type_msg || ' question.';
        INSERT INTO notification (created_at, user_id, user_notification, content, noti_source)
        VALUES (NOW(), (SELECT user_id FROM question WHERE id = NEW.parent_id), NEW.user_id, content, 'vote');
    ELSIF NEW.parent_type = 'App\Models\Answer' THEN
        content := vote_user || ' ' || vote_type_msg || ' answer.';
        INSERT INTO notification (created_at, user_id, user_notification, content, noti_source)
        VALUES (NOW(), (SELECT user_id FROM answer WHERE id = NEW.parent_id), NEW.user_id, content, 'vote');
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS vote_notification ON vote;
CREATE TRIGGER vote_notification
AFTER INSERT
ON vote
FOR EACH ROW
EXECUTE FUNCTION process_vote_notification();


-- When a question is answered, create a notification for it
CREATE OR REPLACE FUNCTION process_answer_notification()
RETURNS TRIGGER AS $$
DECLARE
    answer_user TEXT;
    content TEXT;
BEGIN
    SELECT INTO answer_user users.username FROM users WHERE users.id = NEW.user_id;
    content := 'Your question received an answer from ' || answer_user || '.';

    INSERT INTO notification (created_at, user_id, user_notification, content, noti_source)
    VALUES (NOW(), (SELECT user_id FROM question WHERE id = NEW.question_id), NEW.user_id, content, 'answer');

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS answer_notification ON answer;
CREATE TRIGGER answer_notification
AFTER INSERT ON answer
FOR EACH ROW
EXECUTE FUNCTION process_answer_notification();




CREATE OR REPLACE FUNCTION process_vote_count()
RETURNS TRIGGER AS $$
BEGIN
    IF TG_OP = 'INSERT' OR TG_OP = 'UPDATE' THEN
        IF NEW.parent_type = 'App\Models\Question' THEN
            IF NEW.vote_status = 'up' THEN
                UPDATE question SET up_votes = up_votes + 1 WHERE id = NEW.parent_id;
                IF TG_OP = 'UPDATE' AND OLD.vote_status = 'down' THEN
                    UPDATE question SET down_votes = down_votes - 1 WHERE id = NEW.parent_id;
                END IF;
            ELSE
                UPDATE question SET down_votes = down_votes + 1 WHERE id = NEW.parent_id;
                IF TG_OP = 'UPDATE' AND OLD.vote_status = 'up' THEN
                    UPDATE question SET up_votes = up_votes - 1 WHERE id = NEW.parent_id;
                END IF;
            END IF;
        ELSIF NEW.parent_type = 'App\Models\Answer' THEN
            IF NEW.vote_status = 'up' THEN
                UPDATE answer SET up_votes = up_votes + 1 WHERE id = NEW.parent_id;
                IF TG_OP = 'UPDATE' AND OLD.vote_status = 'down' THEN
                    UPDATE answer SET down_votes = down_votes - 1 WHERE id = NEW.parent_id;
                END IF;
            ELSE
                UPDATE answer SET down_votes = down_votes + 1 WHERE id = NEW.parent_id;
                IF TG_OP = 'UPDATE' AND OLD.vote_status = 'up' THEN
                    UPDATE answer SET up_votes = up_votes - 1 WHERE id = NEW.parent_id;
                END IF;
            END IF;
        END IF;
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;


DROP TRIGGER IF EXISTS vote_count ON vote;
CREATE TRIGGER vote_count
AFTER INSERT OR UPDATE ON vote
FOR EACH ROW
EXECUTE FUNCTION process_vote_count();


CREATE OR REPLACE FUNCTION process_vote_count_delete()
RETURNS TRIGGER AS $$
BEGIN
    IF OLD.parent_type = 'App\Models\Question' THEN
        IF OLD.vote_status = 'up' THEN
            UPDATE question SET up_votes = up_votes - 1 WHERE id = OLD.parent_id;
        ELSE
            UPDATE question SET down_votes = down_votes - 1 WHERE id = OLD.parent_id;
        END IF;
    ELSIF OLD.parent_type = 'App\Models\Answer' THEN
        IF OLD.vote_status = 'up' THEN
            UPDATE answer SET up_votes = up_votes - 1 WHERE id = OLD.parent_id;
        ELSE
            UPDATE answer SET down_votes = down_votes - 1 WHERE id = OLD.parent_id;
        END IF;
    END IF;

    RETURN OLD;
END;
$$ LANGUAGE plpgsql;

DROP TRIGGER IF EXISTS vote_count_delete ON vote;
CREATE TRIGGER vote_count_delete
AFTER DELETE ON vote
FOR EACH ROW
EXECUTE FUNCTION process_vote_count_delete();



--
-- Insert value.
--

INSERT INTO users (id, username, password, email, is_admin, is_moderator, profile_picture_id, bio)
VALUES  -- password: password123
    (99, 'john_doe', '$2a$12$MbowM0hI3CiH7gsCEOM6CuWB4G.1gjzRxOLIGVio.HXRHrThGaeXO', 'john.doe@example.com', false, false, 'john_doe.jpg','Olá sou o Ricardo, tenho 22 anos e adoro passeios pela praia'),
    (98, 'jane_smith', '$2a$12$Pbx4EC0OfutMyQRRCg02ge7Qhr/87QgHZ0NHoqTjgQPSvZWc1y6UC', 'jane.smith@example.com', false, false, 'jane_smith.jpg','Olá sou o Tiago, tenho 32 anos e nao gosto de passeios pela praia'),
    --password: passadmin
    (97, 'admin', '$2a$12$CA10Fbn2QUZZsXdqwuinue60jVOBEFBvbf6VfyX50ZukcdYb0xIPq', 'admin@example.com', false, true, 'admin.jpg','Olá sou o Hashbulla tenho, 12 anos e adoro papagaios');

INSERT INTO question (id, title, content, edited, user_id, up_votes, down_votes)
VALUES 
    (99, 'Favorite CINEMA Films of 2023?', 'What are some of the must-watch CINEMA films of 2023? Share your recommendations!', false, 99, 0, 0),
    (98, 'Best Music Albums for 2023', 'Which MUSIC albums released in 2023 have been receiving critical acclaim? I''m looking for new music to listen to.', false, 98, 0, 0),
    (97, 'Latest IT Innovations', 'What are some of the latest breakthroughs and innovations in the IT field for 2023?', false, 99, 0, 0),
    (96, 'Challenging MATH Problems', 'I''m stuck on a particularly challenging MATH problem. Can anyone provide some guidance?', false, 98, 0, 0),
    (95, 'Challenging MATH Problems', 'I''m stuck on a particularly challenging MATH problem. Can anyone provide some guidance?', false, 98, 0, 0),
    (94, 'Challenging MATH Problems', 'I''m stuck on a particularly challenging MATH problem. Can anyone provide some guidance?', false, 98, 0, 0),
    (93, 'Challenging MATH Problems', 'I''m stuck on a particularly challenging MATH problem. Can anyone provide some guidance?', false, 98, 0, 0),
    (92, 'Challenging MATH Problems', 'I''m stuck on a particularly challenging MATH problem. Can anyone provide some guidance?', false, 98, 0, 0),
    (91, 'Challenging MATH Problems', 'I''m stuck on a particularly challenging MATH problem. Can anyone provide some guidance?', false, 98, 0, 0),
    (90, 'Challenging MATH Problems', 'I''m stuck on a particularly challenging MATH problem. Can anyone provide some guidance?', false, 98, 0, 0),
    (89, 'Challenging MATH Problems', 'I''m stuck on a particularly challenging MATH problem. Can anyone provide some guidance?', false, 98, 0, 0),
    (88, 'Challenging MATH Problems', 'I''m stuck on a particularly challenging MATH problem. Can anyone provide some guidance?', false, 98, 0, 0),
    (87, 'Reported Question', 'Guess who is reported!!!!', false, 99, 0, 0);


INSERT INTO report (id, motive, user_id, parent_id, parent_type)
VALUES
    (99, 'His question hurt my feelings', 98, 87, 'App\Models\Question'),
    (98, 'I disagree with it', 98, 99, 'App\Models\Question'),
    (97, 'Had a fight with him once, I won', 98, 98, 'App\Models\Question');
    

INSERT INTO answer (id, content, user_id, question_id, correct, up_votes, down_votes)
VALUES
    (87, 'Teste', 98,99,true,0,0),
    (99, 'In the world of CINEMA, "Dreamscape Unbound" has been praised for its innovative storytelling and stunning visuals.', 97, 99, true, 0, 0),
    (86,'Teste',99,99,true,0,0),
    (98, 'In the MUSIC scene, "Echoes of Harmony" by SoundScape is considered a masterpiece, pushing boundaries in sound design.', 99, 98, true, 0, 0),
    (97, 'The IT industry has seen significant advances in quantum computing, potentially revolutionizing data processing capabilities.', 97, 97, true, 0, 0),
    (96, 'Regarding the MATH problem, can you provide more details about the specific question you''re struggling with?', 99, 96, false, 0, 0),
    (95, 'EX', 99,99,false,0,0),
    (94, 'EX', 99,99,false,0,0),
    (93, 'EX', 99,99,false,0,0),
    (92, 'EX', 99,99,false,0,0),
    (91, 'EX', 99,99,false,0,0),
    (90, 'EX', 99,99,false,0,0),
    (89, 'EX', 99,99,false,0,0),
    (88, 'EX', 99,99,false,0,0);
    
    -- Add multiple answers to a question


INSERT INTO comment (id, content, user_id, parent_id, parent_type)
VALUES 
    (99, 'I absolutely loved "Dreamscape Unbound"! The storytelling was truly captivating.', 98, 99, 'App\Models\Answer'),
    (98, 'SoundScape always delivers exceptional music. "Echoes of Harmony" is a testament to their artistry.', 97, 98, 'App\Models\Answer'),
    (97, 'The potential of quantum computing in IT is incredibly exciting. It opens up a world of possibilities.', 99, 97, 'App\Models\Answer'),
    (96, 'I''m interested in learning more about quantum computing. Can you recommend any good resources?', 98, 97, 'App\Models\Answer'),
    (95, 'Could you please specify which part of the MATH problem you find challenging?', 97, 96, 'App\Models\Answer'),
    (94, 'I love LBAW!', 99, 99, 'App\Models\Question'),
    (93, 'Francesinha is the best', 98, 99, 'App\Models\Question'),
    (92, 'Travelling is nice', 98, 98, 'App\Models\Question'),
    (91, 'Diogo has bad taste of humor', 97, 98, 'App\Models\Question');

INSERT INTO tag (id, name)
VALUES 
    (99, 'CINEMA'),
    (98, 'MUSIC'),
    (97, 'IT'),
    (96, 'MATH'),
    (95,'SPORTS'),
    (94,'SCIENCE'),
    (93, 'GAMING');


INSERT INTO vote (id, user_id, parent_id, parent_type, vote_status)
VALUES 
    (99, 99, 99, 'App\Models\Question', 'up'),
    (98, 98, 99, 'App\Models\Question', 'down'),
    (97, 97, 99, 'App\Models\Answer', 'up'),
    (96, 99, 98, 'App\Models\Answer', 'up'),
    (95, 98, 98, 'App\Models\Answer', 'down');

 
    

INSERT INTO followTag (user_id, tag_id)
VALUES 
    (99, 99),
    (98, 98),
    (97, 97);



INSERT INTO questionTag (question_id, tag_id)
VALUES 
    (99, 99),
    (99, 98),
    (98, 98),
    (97, 97),
    (97,98),
    (96,98),
    (95,98),
    (94,98),
    (93,98),
    (92,98),
    (91,98),
    (90,98),
    (89,98);
    
    

