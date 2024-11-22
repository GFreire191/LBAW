# PA: Product and Presentation



## A9: Product



### 1. Installation

Meter aqui o link do rep


### 2. Usage

http://lbaw23153.lbaw.fe.up.pt


#### 2.1. Administration Credentials

| Email | Password |
|----------|----------|
|admin|passadmin|

#### 2.2. User Credentials


| Type | Email |Passowrd|
|----------|----------|----|
|Logged User|john.doe@example.com|password123|
|Question Owner| john.doe@example.com | password123
|Answer Owner|john.doe@example.com| password123|
|Comment Owner|john.doe@example.com|password123|

### 3. Application Help


### 4. Input Validation

Most of the validations are performed on the client side in the controller's methods. We check for required fields as well as for maximum length. Some fields have other validations such as the email where a check for a '@' character is made.

Ex: ![image](https://hackmd.io/_uploads/HkrkCJzw6.png)

![image](https://hackmd.io/_uploads/HkvMAkzwT.png)

![image](https://hackmd.io/_uploads/rJ71DgGvT.png)

![image](https://hackmd.io/_uploads/Hy8bwxMD6.png)



### 5. Check Accessibility and Usability

Accessability - https://git.fe.up.pt/lbaw/lbaw2324/lbaw23153/-/blob/main/PAChecks/Checklist%20de%20Acessibilidade%20-%20SAPO%20UX.pdf?ref_type=heads

Usability - https://git.fe.up.pt/lbaw/lbaw2324/lbaw23153/-/blob/main/PAChecks/Checklist%20de%20Usabilidade%20-%20SAPO%20UX.pdf?ref_type=heads


### 6. HTML & CSS Validation

CSS - https://git.fe.up.pt/lbaw/lbaw2324/lbaw23153/-/tree/main/PAChecks/CSS?ref_type=heads



### 7. Revisions to the Project

-Adeed 2 new triggers to the data base 

```sql
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
```
```sql
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
```

### 8. Implementation Details


#### 8.1. Libraries Used

https://fonts.googleapis.com/css2?family=Lato&display=swap
<br/>
https://use.fontawesome.com/releases/v5.6.1/css/all.css




#### 8.2 User Stories


| US Identifier | Module      | Name                          | Priority | Team Members         | State |
| ------------- | ----------- | ----------------------------- | -------- | -------------------- | ----- |
| US01          | M01         | Sign-in                       | high     | **Diogo Vale**       | 100%  |
| US02          | M01         | Sign-up                       | high     | **Guilherme Freire** | 100%  |
| US03          | M01         | View Profile                  | high     | **João Lopes**       | 100%  |
| US04          | M02         | Add Question                  | high     | **Guilherme Freire** | 100%  |
| US05          | M02         | Edit Question                 | high     | **Guilherme Freire** | 100%  |
| US06          | M02         | Delete Question               | high     | **Guilherme Freire** | 100%  |
| US07          | M03         | Add Answer                    | high     | **Guilherme Freire** | 100%  |
| US08          | M03         | Edit Answer                   | high     | **Guilherme Freire** | 100%  |
| US09          | M03         | Delete Answer                 | high     | **Guilherme Freire** | 100%  |
| US10          | M06         | View Top Questions            | high     | **Diogo Vale**       | 100%  |
| US11          | M06         | Browse Question               | high     | **Diogo Vale**       | 100%  |
| US13          | M05         | Home Page                     | high     | **Guilherme Freire** | 100%  |
| US14          | M04         | Add Comment                   | high     | **João Lopes**       | 100%  |
| US15          | M04         | Edit Comment                  | high     | **João Lopes**       | 100%  |
| US16          | M04         | Delete Comment                | high     | **João Lopes**       | 100%  |
| US17          | M02,M03,M04 | Add Vote                      | high     | **Diogo Vale**       | 100%  |
| US17          | M02,M03,M04 | Update Vote                   | high     | **Diogo Vale**       | 100%  |
| US18          | M02,M03,M04 | Delete Vote                   | high     | **Diogo Vale**       | 100%  |
| US19          | M01         | Edit Profile                  | high     | **Diogo Vale**       | 100%  |
| US20          | M02         | Manage Question Tags          | high     | **Guilherme Freire** | 100%  |
| US21          | M07         | Create Report                 | medium   | **Guilherme Freire** | 100%  |
| US22          | M07         | Delete Report                 | medium   | **Guilherme Freire** | 100%  |
| US23          | M07         | Admin Page                    | medium   | **Guilherme Freire** | 100%  |
| US24          | M02,M03     | Notificate Answer to Question | medium   | **João Lopes**       | 100%  |
| US25          | M02,M03     | Notificate Vote               | medium   | **João Lopes**       | 100%  |



## A10: Presentation

### 1. Product presentation

We developed StackUnderflow, an interactive Q&A forum. The homepage displays questions in chronological order, providing users with a straightforward experience. The platform incorporates essential user authentication features, including login, logout, and signup options.

StackUnderflow's features include a tag-based navigation bar for topic-specific exploration. Users can actively participate by creating, editing, and deleting questions. It is also possible to give answers for these questions and to comment on both answers and questions. There is a voting system implemented for answers and questions. The website also has capabilities of Admin management like reporting features. 

### 2. Video presentation

![image](https://hackmd.io/_uploads/SytQeIGvT.png)


## GROUP 23153   21/12/2023

| Name | Email |
|----------|----------|
|Diogo Vale|up201805152@up.pt|
|Guilerme Freire| up202004809@fe.up.pt |
|João Lopes|  up201805078@up.pt |

Editor: Guilherme, Diogo, João Lopes
